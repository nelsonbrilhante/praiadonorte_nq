#!/bin/bash

# Nazaré Qualifica - Stop Script
# Para o servidor Laravel e Vite

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
echo -e "${BLUE}  Nazaré Qualifica - Parando Sistema${NC}"
echo -e "${BLUE}========================================${NC}"
echo ""

# Parar Laravel
if [ -f "$PID_DIR/laravel.pid" ]; then
    PID=$(cat "$PID_DIR/laravel.pid")
    if kill -0 $PID 2>/dev/null; then
        echo -e "${RED}Parando Laravel (PID: $PID)...${NC}"
        kill $PID 2>/dev/null
        rm "$PID_DIR/laravel.pid"
        echo -e "  Laravel parado"
    else
        echo -e "${YELLOW}Laravel não estava a correr${NC}"
        rm "$PID_DIR/laravel.pid"
    fi
else
    echo -e "${YELLOW}Laravel não estava a correr${NC}"
fi

# Parar Vite
if [ -f "$PID_DIR/vite.pid" ]; then
    PID=$(cat "$PID_DIR/vite.pid")
    if kill -0 $PID 2>/dev/null; then
        echo -e "${RED}Parando Vite (PID: $PID)...${NC}"
        kill $PID 2>/dev/null
        rm "$PID_DIR/vite.pid"
        echo -e "  Vite parado"
    else
        echo -e "${YELLOW}Vite não estava a correr${NC}"
        rm "$PID_DIR/vite.pid"
    fi
else
    echo -e "${YELLOW}Vite não estava a correr${NC}"
fi

# Matar processos órfãos (caso existam)
echo ""
echo -e "${YELLOW}Verificando processos órfãos...${NC}"

# Matar php artisan serve
pkill -f "artisan serve" 2>/dev/null && echo -e "  Processos 'artisan serve' terminados"

# Matar vite
pkill -f "vite" 2>/dev/null && echo -e "  Processos 'vite' terminados"

# Matar npm run dev (processos pai)
pkill -f "npm run dev" 2>/dev/null && echo -e "  Processos 'npm run dev' terminados"

# Verificar portas
LARAVEL_PID=$(lsof -ti:8000 2>/dev/null)
if [ ! -z "$LARAVEL_PID" ]; then
    echo -e "  Matando processo na porta 8000 (PID: $LARAVEL_PID)"
    kill $LARAVEL_PID 2>/dev/null
fi

VITE_PID=$(lsof -ti:5173 2>/dev/null)
if [ ! -z "$VITE_PID" ]; then
    echo -e "  Matando processo na porta 5173 (PID: $VITE_PID)"
    kill $VITE_PID 2>/dev/null
fi

VITE_PID2=$(lsof -ti:5174 2>/dev/null)
if [ ! -z "$VITE_PID2" ]; then
    echo -e "  Matando processo na porta 5174 (PID: $VITE_PID2)"
    kill $VITE_PID2 2>/dev/null
fi

echo ""
echo -e "${GREEN}========================================${NC}"
echo -e "${GREEN}  Sistema parado com sucesso!${NC}"
echo -e "${GREEN}========================================${NC}"
