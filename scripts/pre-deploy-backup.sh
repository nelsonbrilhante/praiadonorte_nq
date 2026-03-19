#!/bin/bash
# =============================================================================
# Pre-Deploy Backup Script — Praia do Norte Platform
# =============================================================================
# Runs on the VPS before each deploy. Called by GitHub Actions CI/CD.
# Creates a timestamped backup of:
#   - Laravel MySQL database
#   - WordPress MariaDB database
#   - WordPress uploads
#   - Nginx access/error logs
#
# No credentials are hardcoded — reads from container environment variables.
# =============================================================================
set -euo pipefail

BACKUP_DIR="/root/backups"
TIMESTAMP=$(date +%Y%m%d-%H%M%S)
BACKUP_NAME="backup-${TIMESTAMP}"
WORK_DIR="${BACKUP_DIR}/${BACKUP_NAME}"
MAX_BACKUPS=10

# Coolify container name patterns (UUIDs)
LARAVEL_UUID="o4ck0w8woo4s88gg4gkg04gs"
MYSQL_UUID="gowgkc4s8wwck0kw0wk4k48o"
WP_SERVICE_UUID="egkw4sgk0gss00kcs480gg40"

echo "=== Pre-Deploy Backup: ${BACKUP_NAME} ==="
echo "Timestamp: $(date)"

mkdir -p "${WORK_DIR}"

# ---------------------------------------------------------------------------
# 1. Find containers
# ---------------------------------------------------------------------------
echo ""
echo "--- Finding containers ---"

LARAVEL_CONTAINER=$(docker ps --filter "name=${LARAVEL_UUID}" --format '{{.Names}}' | head -1)
MYSQL_CONTAINER=$(docker ps --filter "name=${MYSQL_UUID}" --format '{{.Names}}' | head -1)

# WordPress service has multiple containers (app + db) — find both
WP_DB_CONTAINER=$(docker ps --format '{{.Names}}' | grep "${WP_SERVICE_UUID}" | grep -i 'mariadb\|mysql\|db' | head -1 || true)
WP_APP_CONTAINER=$(docker ps --format '{{.Names}}' | grep "${WP_SERVICE_UUID}" | grep -iv 'mariadb\|mysql\|db' | head -1 || true)

echo "  Laravel:      ${LARAVEL_CONTAINER:-NOT FOUND}"
echo "  MySQL:        ${MYSQL_CONTAINER:-NOT FOUND}"
echo "  WordPress DB: ${WP_DB_CONTAINER:-NOT FOUND}"
echo "  WordPress:    ${WP_APP_CONTAINER:-NOT FOUND}"

# ---------------------------------------------------------------------------
# 2. MySQL dump (Laravel) — reads MYSQL_ROOT_PASSWORD from container env
# ---------------------------------------------------------------------------
if [ -n "${MYSQL_CONTAINER}" ]; then
    echo ""
    echo "--- Dumping Laravel MySQL database ---"
    docker exec "${MYSQL_CONTAINER}" sh -c \
        'mysqldump -u root -p"$MYSQL_ROOT_PASSWORD" --single-transaction --routines --triggers praia_do_norte' \
        > "${WORK_DIR}/laravel.sql" 2>/dev/null
    LARAVEL_SQL_SIZE=$(du -h "${WORK_DIR}/laravel.sql" | cut -f1)
    echo "  Done: laravel.sql (${LARAVEL_SQL_SIZE})"
else
    echo "WARNING: MySQL container not found, skipping Laravel DB backup"
fi

# ---------------------------------------------------------------------------
# 3. MariaDB dump (WordPress) — reads MYSQL_ROOT_PASSWORD from container env
# ---------------------------------------------------------------------------
if [ -n "${WP_DB_CONTAINER}" ]; then
    echo ""
    echo "--- Dumping WordPress MariaDB database ---"
    docker exec "${WP_DB_CONTAINER}" sh -c \
        'mariadb-dump -u root -p"$MYSQL_ROOT_PASSWORD" --single-transaction "$MYSQL_DATABASE"' \
        > "${WORK_DIR}/wordpress.sql" 2>/dev/null
    WP_SQL_SIZE=$(du -h "${WORK_DIR}/wordpress.sql" | cut -f1)
    echo "  Done: wordpress.sql (${WP_SQL_SIZE})"
else
    echo "WARNING: WordPress DB container not found, skipping WordPress DB backup"
fi

# ---------------------------------------------------------------------------
# 4. WordPress uploads
# ---------------------------------------------------------------------------
if [ -n "${WP_APP_CONTAINER}" ]; then
    echo ""
    echo "--- Backing up WordPress uploads ---"
    docker cp "${WP_APP_CONTAINER}:/var/www/html/wp-content/uploads" "${WORK_DIR}/wp-uploads" 2>/dev/null || {
        echo "  WARNING: Could not copy WordPress uploads"
    }
    if [ -d "${WORK_DIR}/wp-uploads" ]; then
        WP_UPLOADS_SIZE=$(du -sh "${WORK_DIR}/wp-uploads" | cut -f1)
        echo "  Done: wp-uploads/ (${WP_UPLOADS_SIZE})"
    fi
else
    echo "WARNING: WordPress app container not found, skipping uploads backup"
fi

# ---------------------------------------------------------------------------
# 5. Nginx logs (Laravel container)
# ---------------------------------------------------------------------------
if [ -n "${LARAVEL_CONTAINER}" ]; then
    echo ""
    echo "--- Extracting Nginx logs ---"
    docker cp "${LARAVEL_CONTAINER}:/var/log/nginx/access.log" "${WORK_DIR}/nginx-access.log" 2>/dev/null || {
        echo "  Note: No Nginx access log found"
    }
    docker cp "${LARAVEL_CONTAINER}:/var/log/nginx/error.log" "${WORK_DIR}/nginx-error.log" 2>/dev/null || {
        echo "  Note: No Nginx error log found"
    }
    if [ -f "${WORK_DIR}/nginx-access.log" ]; then
        LINES=$(wc -l < "${WORK_DIR}/nginx-access.log")
        echo "  Done: nginx-access.log (${LINES} lines)"
    fi
else
    echo "WARNING: Laravel container not found, skipping Nginx logs"
fi

# ---------------------------------------------------------------------------
# 6. Compress everything
# ---------------------------------------------------------------------------
echo ""
echo "--- Compressing backup ---"
cd "${BACKUP_DIR}"
tar czf "${BACKUP_NAME}.tar.gz" "${BACKUP_NAME}/"
rm -rf "${WORK_DIR}"

ARCHIVE_SIZE=$(du -h "${BACKUP_DIR}/${BACKUP_NAME}.tar.gz" | cut -f1)
echo "  Archive: ${BACKUP_NAME}.tar.gz (${ARCHIVE_SIZE})"

# ---------------------------------------------------------------------------
# 7. Create "latest" symlink (for easy download by CI/CD)
# ---------------------------------------------------------------------------
ln -sf "${BACKUP_NAME}.tar.gz" "${BACKUP_DIR}/latest-backup.tar.gz"

# ---------------------------------------------------------------------------
# 8. Rotation — keep only the last N backups
# ---------------------------------------------------------------------------
BACKUP_COUNT=$(ls -1 "${BACKUP_DIR}"/backup-*.tar.gz 2>/dev/null | wc -l)
if [ "${BACKUP_COUNT}" -gt "${MAX_BACKUPS}" ]; then
    REMOVED=$(ls -t "${BACKUP_DIR}"/backup-*.tar.gz | tail -n +"$((MAX_BACKUPS + 1))" | wc -l)
    ls -t "${BACKUP_DIR}"/backup-*.tar.gz | tail -n +"$((MAX_BACKUPS + 1))" | xargs rm -f
    echo "  Rotation: removed ${REMOVED} old backup(s), keeping last ${MAX_BACKUPS}"
fi

echo ""
echo "=== Backup complete: ${BACKUP_DIR}/${BACKUP_NAME}.tar.gz ==="
