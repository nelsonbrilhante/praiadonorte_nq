.PHONY: setup start stop status logs seed teardown reset help

help: ## Show this help
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}; {printf "  \033[36m%-12s\033[0m %s\n", $$1, $$2}'

setup: ## Full automated setup (build, install, configure, seed)
	@bash scripts/setup.sh

start: ## Start containers
	@docker compose up -d

stop: ## Stop containers
	@docker compose stop

status: ## Show container and WordPress info
	@echo "=== Containers ==="
	@docker compose ps
	@echo ""
	@echo "=== WordPress ==="
	@docker compose exec -T wordpress wp --allow-root --path=/var/www/html core version 2>/dev/null || echo "WordPress not ready"
	@docker compose exec -T wordpress wp --allow-root --path=/var/www/html plugin list --status=active 2>/dev/null || true

logs: ## Tail container logs
	@docker compose logs -f

seed: ## Re-seed products (idempotent)
	@bash scripts/seed-products.sh

teardown: ## Destroy everything (containers + volumes)
	@bash scripts/teardown.sh

reset: teardown setup ## Teardown + fresh setup
