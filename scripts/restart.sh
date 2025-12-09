#!/bin/bash

# Nazaré Qualifica - Restart Script
# Para e reinicia os servidores

SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"

# Cores para output
BLUE='\033[0;34m'
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

# Executar start.sh
"$SCRIPT_DIR/start.sh"
