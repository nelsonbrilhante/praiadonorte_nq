#!/bin/bash

# Nazaré Qualifica - Stop Script
# Para os servidores backend (Laravel) e frontend (Next.js)

SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
PROJECT_DIR="$(cd "$SCRIPT_DIR/.." && pwd)"
PID_DIR="$PROJECT_DIR/.pids"

# Cores para output
RED='\033[0;31m'
GREEN='\033[0;32m'
BLUE='\033[0;34m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

echo -e "${BLUE}========================================${NC}"
echo -e "${BLUE}  Nazaré Qualifica - Parando Servidores${NC}"
echo -e "${BLUE}========================================${NC}"
echo ""

# Parar Backend
if [ -f "$PID_DIR/backend.pid" ]; then
    PID=$(cat "$PID_DIR/backend.pid")
    if kill -0 $PID 2>/dev/null; then
        echo -e "${RED}Parando Backend (PID: $PID)...${NC}"
        kill $PID 2>/dev/null
        rm "$PID_DIR/backend.pid"
        echo -e "  Backend parado"
    else
        echo -e "${YELLOW}Backend não estava a correr${NC}"
        rm "$PID_DIR/backend.pid"
    fi
else
    echo -e "${YELLOW}Backend não estava a correr${NC}"
fi

# Parar Frontend
if [ -f "$PID_DIR/frontend.pid" ]; then
    PID=$(cat "$PID_DIR/frontend.pid")
    if kill -0 $PID 2>/dev/null; then
        echo -e "${RED}Parando Frontend (PID: $PID)...${NC}"
        kill $PID 2>/dev/null
        rm "$PID_DIR/frontend.pid"
        echo -e "  Frontend parado"
    else
        echo -e "${YELLOW}Frontend não estava a correr${NC}"
        rm "$PID_DIR/frontend.pid"
    fi
else
    echo -e "${YELLOW}Frontend não estava a correr${NC}"
fi

# Matar processos órfãos (caso existam)
echo ""
echo -e "${YELLOW}Verificando processos órfãos...${NC}"

# Matar php artisan serve na porta 8000
LARAVEL_PID=$(lsof -ti:8000 2>/dev/null)
if [ ! -z "$LARAVEL_PID" ]; then
    echo -e "  Matando processo na porta 8000 (PID: $LARAVEL_PID)"
    kill $LARAVEL_PID 2>/dev/null
fi

# Matar next dev na porta 3000
NEXT_PID=$(lsof -ti:3000 2>/dev/null)
if [ ! -z "$NEXT_PID" ]; then
    echo -e "  Matando processo na porta 3000 (PID: $NEXT_PID)"
    kill $NEXT_PID 2>/dev/null
fi

echo ""
echo -e "${GREEN}========================================${NC}"
echo -e "${GREEN}  Servidores parados com sucesso!${NC}"
echo -e "${GREEN}========================================${NC}"
