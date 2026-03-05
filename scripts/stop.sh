#!/bin/bash

# Nazare Qualifica - Stop Script
# Stops Laravel, Vite, and queue worker

SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
PROJECT_DIR="$(cd "$SCRIPT_DIR/.." && pwd)"
PID_DIR="$PROJECT_DIR/.pids"

# Colors
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
DIM='\033[2m'
NC='\033[0m'

STOPPED=()
ALREADY_STOPPED=()

# Graceful kill: SIGTERM, wait up to 5s, then SIGKILL
kill_graceful() {
    local pid=$1
    local name=$2
    if kill -0 "$pid" 2>/dev/null; then
        kill "$pid" 2>/dev/null
        local waited=0
        while kill -0 "$pid" 2>/dev/null && [ $waited -lt 5 ]; do
            sleep 1
            waited=$((waited + 1))
        done
        if kill -0 "$pid" 2>/dev/null; then
            kill -9 "$pid" 2>/dev/null
        fi
        STOPPED+=("$name (PID $pid)")
        return 0
    fi
    return 1
}

echo -e "${RED}Stopping services...${NC}"
echo ""

# Stop services from PID files
for service in laravel vite queue; do
    pidfile="$PID_DIR/$service.pid"
    if [ -f "$pidfile" ]; then
        pid=$(cat "$pidfile")
        if kill_graceful "$pid" "$service"; then
            :
        else
            ALREADY_STOPPED+=("$service")
        fi
        rm -f "$pidfile"
    else
        ALREADY_STOPPED+=("$service")
    fi
done

# Clean up legacy PID files
rm -f "$PID_DIR/backend.pid" "$PID_DIR/frontend.pid" 2>/dev/null

# Kill orphaned processes
pkill -f "artisan serve" 2>/dev/null
pkill -f "artisan queue:listen" 2>/dev/null
pkill -f "artisan queue:work" 2>/dev/null
pkill -f "vite" 2>/dev/null
pkill -f "npm run dev" 2>/dev/null

# Port cleanup
for port in 8000 5173 5174; do
    pids=$(lsof -ti:"$port" 2>/dev/null)
    if [ -n "$pids" ]; then
        echo "$pids" | xargs kill 2>/dev/null
    fi
done

# Summary
echo ""
if [ ${#STOPPED[@]} -gt 0 ]; then
    for s in "${STOPPED[@]}"; do
        echo -e "  ${RED}Stopped${NC} $s"
    done
fi
if [ ${#ALREADY_STOPPED[@]} -gt 0 ]; then
    echo -e "  ${DIM}Already stopped: ${ALREADY_STOPPED[*]}${NC}"
fi
echo ""
echo -e "${GREEN}All services stopped.${NC}"
