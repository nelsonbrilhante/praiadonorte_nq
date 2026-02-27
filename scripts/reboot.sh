#!/bin/bash

# Nazaré Qualifica - Restart Script
# Para e reinicia os servidores

SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
PROJECT_DIR="$(cd "$SCRIPT_DIR/.." && pwd)"

# Flags
SEED=false
FORCE=false
for arg in "$@"; do
    case $arg in
        --seed) SEED=true ;;
        --force) FORCE=true ;;
    esac
done

# Cores para output
BLUE='\033[0;34m'
GREEN='\033[0;32m'
NC='\033[0m' # No Color

echo -e "${BLUE}========================================${NC}"
echo -e "${BLUE}  Nazaré Qualifica - Reiniciando...${NC}"
echo -e "${BLUE}========================================${NC}"
echo ""

# Executar stop.sh
"$SCRIPT_DIR/stop.sh"

echo ""
echo "Aguardando 2 segundos..."
sleep 2
echo ""

# Executar seeders se --seed flag passada
if [ "$SEED" = true ]; then
    echo -e "${GREEN}Executando seeders...${NC}"
    cd "$PROJECT_DIR/backend"
    if [ "$FORCE" = true ]; then
        echo -e "${GREEN}Modo --force: migrate:fresh + seed${NC}"
        php artisan migrate:fresh --seed --force 2>&1
    else
        php artisan migrate --force 2>&1
        php artisan db:seed --force 2>&1
    fi
    echo -e "${GREEN}Seeders concluídos.${NC}"
    echo ""
fi

# Executar start.sh
"$SCRIPT_DIR/start.sh"
