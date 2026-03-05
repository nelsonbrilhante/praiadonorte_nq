#!/bin/bash

# Nazare Qualifica - Restart Script
# Stops all services, then starts them again

SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"

echo ""
"$SCRIPT_DIR/stop.sh"

sleep 2

"$SCRIPT_DIR/start.sh" "$@"
