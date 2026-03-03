#!/usr/bin/env bash
set -euo pipefail

SCRIPT_DIR="$(cd "$(dirname "$0")" && pwd)"
PROJECT_DIR="$(cd "$SCRIPT_DIR/.." && pwd)"

echo ""
echo "WARNING: This will destroy ALL WordPress data (database + files)."
echo ""
read -p "Are you sure? (y/N) " -n 1 -r
echo ""

if [[ ! $REPLY =~ ^[Yy]$ ]]; then
    echo "Aborted."
    exit 0
fi

echo "Stopping and removing containers + volumes..."
docker compose -f "$PROJECT_DIR/docker-compose.yml" down -v

echo ""
echo "Teardown complete. Run 'make setup' to start fresh."
