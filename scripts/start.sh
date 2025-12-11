#!/bin/bash

# Nazaré Qualifica - Start Script
# Inicia o servidor Laravel e Vite (arquitetura monolítica)

SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
PROJECT_DIR="$(cd "$SCRIPT_DIR/.." && pwd)"
PID_DIR="$PROJECT_DIR/.pids"
LOG_DIR="$PROJECT_DIR/.logs"

# Cores para output
GREEN='\033[0;32m'
BLUE='\033[0;34m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

echo -e "${BLUE}========================================${NC}"
echo -e "${BLUE}  Nazaré Qualifica - Iniciando Sistema${NC}"
echo -e "${BLUE}========================================${NC}"
echo ""

# Criar diretórios se não existirem
mkdir -p "$PID_DIR"
mkdir -p "$LOG_DIR"

# Verificar se já está a correr
if [ -f "$PID_DIR/laravel.pid" ] && kill -0 $(cat "$PID_DIR/laravel.pid") 2>/dev/null; then
    echo -e "${YELLOW}Laravel já está a correr (PID: $(cat "$PID_DIR/laravel.pid"))${NC}"
else
    # Iniciar Laravel
    echo -e "${GREEN}Iniciando Laravel (porta 8000)...${NC}"
    cd "$PROJECT_DIR/backend"
    php artisan serve > "$LOG_DIR/laravel.log" 2>&1 &
    echo $! > "$PID_DIR/laravel.pid"
    echo -e "  Laravel iniciado (PID: $!)"
fi

if [ -f "$PID_DIR/vite.pid" ] && kill -0 $(cat "$PID_DIR/vite.pid") 2>/dev/null; then
    echo -e "${YELLOW}Vite já está a correr (PID: $(cat "$PID_DIR/vite.pid"))${NC}"
else
    # Iniciar Vite
    echo -e "${GREEN}Iniciando Vite (porta 5173)...${NC}"
    cd "$PROJECT_DIR/backend"
    npm run dev > "$LOG_DIR/vite.log" 2>&1 &
    echo $! > "$PID_DIR/vite.pid"
    echo -e "  Vite iniciado (PID: $!)"
fi

# Aguardar servidores iniciarem
sleep 2

echo ""
echo -e "${GREEN}========================================${NC}"
echo -e "${GREEN}  Sistema iniciado com sucesso!${NC}"
echo -e "${GREEN}========================================${NC}"
echo ""
echo -e "  Website (PT):   ${BLUE}http://localhost:8000/pt${NC}"
echo -e "  Website (EN):   ${BLUE}http://localhost:8000/en${NC}"
echo -e "  Filament Admin: ${BLUE}http://localhost:8000/admin${NC}"
echo -e "  Vite Dev:       ${BLUE}http://localhost:5173${NC}"
echo ""
echo -e "  Credenciais Filament:"
echo -e "    Email:    admin@nazarequalifica.pt"
echo -e "    Password: password"
echo ""
echo -e "  Logs disponíveis em: ${YELLOW}$LOG_DIR${NC}"
echo ""
echo -e "Para parar o sistema: ${YELLOW}./scripts/stop.sh${NC}"
