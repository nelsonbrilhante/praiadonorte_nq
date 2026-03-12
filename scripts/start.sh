#!/bin/bash

# Nazare Qualifica - Start Script
# Starts Laravel, Vite HMR, and queue worker

SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
PROJECT_DIR="$(cd "$SCRIPT_DIR/.." && pwd)"
BACKEND_DIR="$PROJECT_DIR/backend"
PID_DIR="$PROJECT_DIR/.pids"
LOG_DIR="$PROJECT_DIR/.logs"

# Flags
SEED=false
FORCE=false
for arg in "$@"; do
    case $arg in
        --seed) SEED=true ;;
        --force) FORCE=true ;;
    esac
done

# Colors
GREEN='\033[0;32m'
BLUE='\033[0;34m'
YELLOW='\033[1;33m'
RED='\033[0;31m'
DIM='\033[2m'
NC='\033[0m'

# Check if a PID file exists and the process is alive
is_running() {
    local pidfile="$PID_DIR/$1.pid"
    [ -f "$pidfile" ] && kill -0 "$(cat "$pidfile")" 2>/dev/null
}

echo -e "${BLUE}Starting Nazare Qualifica...${NC}"
echo ""

# Create directories
mkdir -p "$PID_DIR" "$LOG_DIR"

# Clean up legacy PID files
rm -f "$PID_DIR/backend.pid" "$PID_DIR/frontend.pid" 2>/dev/null

# Check if all services are already running
if is_running laravel && is_running vite && is_running queue; then
    echo -e "${YELLOW}All services already running:${NC}"
    echo -e "  Laravel  PID $(cat "$PID_DIR/laravel.pid")"
    echo -e "  Vite     PID $(cat "$PID_DIR/vite.pid")"
    echo -e "  Queue    PID $(cat "$PID_DIR/queue.pid")"
    echo ""
    echo -e "  Run ${DIM}./scripts/restart.sh${NC} to restart."
    exit 0
fi

# Kill orphaned processes on ports before starting
for port in 8000 5173 5174; do
    pids=$(lsof -ti:"$port" 2>/dev/null)
    if [ -n "$pids" ]; then
        echo -e "  ${DIM}Clearing port $port${NC}"
        echo "$pids" | xargs kill 2>/dev/null
        sleep 0.5
    fi
done

# Truncate old logs
> "$LOG_DIR/laravel.log"
> "$LOG_DIR/vite.log"
> "$LOG_DIR/queue.log"

# Run seeders if --seed flag passed
if [ "$SEED" = true ]; then
    echo -e "${GREEN}Running seeders...${NC}"
    cd "$BACKEND_DIR" || exit 1
    if [ "$FORCE" = true ]; then
        php artisan migrate:fresh --seed --force 2>&1
    else
        php artisan migrate --force 2>&1
        php artisan db:seed --force 2>&1
    fi
    php artisan db:seed --class=UserSeeder --force 2>&1
    echo -e "${GREEN}Seeders complete.${NC}"
    echo ""
fi

# Start Laravel
if is_running laravel; then
    echo -e "  ${YELLOW}Laravel already running (PID $(cat "$PID_DIR/laravel.pid"))${NC}"
else
    cd "$BACKEND_DIR" || exit 1
    php artisan serve --host=0.0.0.0 > "$LOG_DIR/laravel.log" 2>&1 &
    echo $! > "$PID_DIR/laravel.pid"
    echo -e "  ${GREEN}Laravel${NC}  started (PID $!)"
fi

# Start Vite
if is_running vite; then
    echo -e "  ${YELLOW}Vite already running (PID $(cat "$PID_DIR/vite.pid"))${NC}"
else
    cd "$BACKEND_DIR" || exit 1
    npm run dev > "$LOG_DIR/vite.log" 2>&1 &
    echo $! > "$PID_DIR/vite.pid"
    echo -e "  ${GREEN}Vite${NC}     started (PID $!)"
fi

# Start Queue Worker
if is_running queue; then
    echo -e "  ${YELLOW}Queue already running (PID $(cat "$PID_DIR/queue.pid"))${NC}"
else
    cd "$BACKEND_DIR" || exit 1
    php artisan queue:listen --tries=1 > "$LOG_DIR/queue.log" 2>&1 &
    echo $! > "$PID_DIR/queue.pid"
    echo -e "  ${GREEN}Queue${NC}    started (PID $!)"
fi

# Health check: wait up to 10s for Laravel to bind the port
echo ""
echo -ne "  ${DIM}Waiting for Laravel..."
healthy=false
for i in $(seq 1 10); do
    if lsof -ti:8000 >/dev/null 2>&1; then
        healthy=true
        break
    fi
    echo -ne "."
    sleep 1
done
echo -e "${NC}"

if [ "$healthy" = true ]; then
    echo -e "  ${GREEN}Health check passed${NC} (${i}s)"
else
    echo -e "  ${YELLOW}Health check timeout${NC} — Laravel may still be starting"
    echo -e "  ${DIM}Check logs: tail -f $LOG_DIR/laravel.log${NC}"
fi

# Detect local network IP
LOCAL_IP=$(ifconfig 2>/dev/null | grep "inet " | grep -v 127.0.0.1 | grep -v "inet 10\." | awk '{print $2}' | head -1)

# Summary
echo ""
echo -e "${GREEN}Ready!${NC}"
echo ""
echo -e "  Website (PT):   ${BLUE}http://localhost:8000/pt${NC}"
echo -e "  Website (EN):   ${BLUE}http://localhost:8000/en${NC}"
echo -e "  Filament Admin: ${BLUE}http://localhost:8000/admin${NC}"
echo ""
echo -e "  ${YELLOW}Demo users (RBAC):${NC}"
echo -e "  ${DIM}Admin:          nelson.brilhante@cm-nazare.pt / Nzr€Qu@l!f1c4-2026${NC}"
echo -e "  ${DIM}Editor:         editor@test.dev / password${NC}"
echo -e "  ${DIM}Entity (PN):    pn@test.dev / password${NC}"
echo -e "  ${DIM}Entity (NQ):    nq@test.dev / password${NC}"
echo -e "  ${DIM}Entity (CS):    carsurf@test.dev / password${NC}"
echo -e "  ${DIM}Multi-Entity:   multi@test.dev / password${NC}"
if [ -n "$LOCAL_IP" ]; then
    echo ""
    echo -e "  ${YELLOW}Network (mobile):${NC} ${BLUE}http://${LOCAL_IP}:8000/pt${NC}"
fi
echo ""
echo -e "  ${DIM}Logs: $LOG_DIR${NC}"
echo -e "  ${DIM}Stop: ./scripts/stop.sh${NC}"
