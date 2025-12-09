#!/bin/bash

# Nazaré Qualifica - Start Script
# Inicia os servidores backend (Laravel) e frontend (Next.js)

SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
PROJECT_DIR="$(cd "$SCRIPT_DIR/.." && pwd)"
PID_DIR="$PROJECT_DIR/.pids"

# Cores para output
GREEN='\033[0;32m'
BLUE='\033[0;34m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

echo -e "${BLUE}========================================${NC}"
echo -e "${BLUE}  Nazaré Qualifica - Iniciando Servidores${NC}"
echo -e "${BLUE}========================================${NC}"
echo ""

# Criar diretório para PIDs se não existir
mkdir -p "$PID_DIR"

# Verificar se já está a correr
if [ -f "$PID_DIR/backend.pid" ] && kill -0 $(cat "$PID_DIR/backend.pid") 2>/dev/null; then
    echo -e "${YELLOW}Backend já está a correr (PID: $(cat "$PID_DIR/backend.pid"))${NC}"
else
    # Iniciar Backend (Laravel)
    echo -e "${GREEN}Iniciando Backend (Laravel)...${NC}"
    cd "$PROJECT_DIR/backend"
    php artisan serve > /dev/null 2>&1 &
    echo $! > "$PID_DIR/backend.pid"
    echo -e "  Backend iniciado (PID: $!)"
fi

if [ -f "$PID_DIR/frontend.pid" ] && kill -0 $(cat "$PID_DIR/frontend.pid") 2>/dev/null; then
    echo -e "${YELLOW}Frontend já está a correr (PID: $(cat "$PID_DIR/frontend.pid"))${NC}"
else
    # Iniciar Frontend (Next.js)
    echo -e "${GREEN}Iniciando Frontend (Next.js)...${NC}"
    cd "$PROJECT_DIR/frontend"
    npm run dev > /dev/null 2>&1 &
    echo $! > "$PID_DIR/frontend.pid"
    echo -e "  Frontend iniciado (PID: $!)"
fi

echo ""
echo -e "${GREEN}========================================${NC}"
echo -e "${GREEN}  Servidores iniciados com sucesso!${NC}"
echo -e "${GREEN}========================================${NC}"
echo ""
echo -e "  Frontend:       ${BLUE}http://localhost:3000/pt${NC}"
echo -e "  Backend API:    ${BLUE}http://localhost:8000/api${NC}"
echo -e "  Filament Admin: ${BLUE}http://localhost:8000/admin${NC}"
echo ""
echo -e "  Credenciais Filament:"
echo -e "    Email:    admin@nazarequalifica.pt"
echo -e "    Password: password"
echo ""
echo -e "Para parar os servidores: ${YELLOW}./scripts/stop.sh${NC}"
