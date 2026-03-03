# WooCommerce Local Development

Local WordPress + WooCommerce setup for the Praia do Norte shop integration.

## Prerequisites

- Docker Desktop running
- `backend/.env` exists (Laravel project)

## Quick Start

```bash
cd wordpress
make setup
```

This single command will:
1. Build and start WordPress + MariaDB containers
2. Install WordPress (no browser wizard)
3. Install and activate WooCommerce
4. Configure store settings (EUR, Portugal)
5. Generate REST API keys
6. Seed 5 sample products
7. Update `backend/.env` with WooCommerce credentials
8. Verify the REST API responds

## Commands

| Command | Description |
|---------|-------------|
| `make setup` | Full automated setup |
| `make start` | Start containers |
| `make stop` | Stop containers |
| `make status` | Show container and WordPress info |
| `make logs` | Tail container logs |
| `make seed` | Re-seed products (idempotent) |
| `make teardown` | Destroy everything (confirmation required) |
| `make reset` | Teardown + fresh setup |

## URLs

| Service | URL |
|---------|-----|
| WordPress Admin | http://localhost:8080/wp-admin |
| WooCommerce API | http://localhost:8080/wp-json/wc/v3/ |
| Laravel Shop | http://localhost:8000/pt/loja |

Default admin credentials: `admin` / `admin123`

## Sample Products

| Product | Price | SKU | Category |
|---------|-------|-----|----------|
| T-Shirt Praia do Norte - Onda Gigante | 29.90 | PN-TS-001 | Vestuario |
| Hoodie Nazare Big Wave | 59.90 (sale: 49.90) | PN-HD-001 | Vestuario |
| Bone Praia do Norte | 19.90 | PN-CAP-001 | Acessorios |
| Poster Onda Gigante (Edicao Limitada) | 15.00 | PN-POST-001 | Acessorios |
| Cera de Surf - Pack 3 | 12.50 | PN-WAX-001 | Equipamento |

## Verification

```bash
# From backend/
php artisan tinker
>>> app(\App\Services\WooCommerceService::class)->isAvailable()
// => true
>>> count(app(\App\Services\WooCommerceService::class)->getProducts()['products'])
// => 5
```

## Configuration

Copy `.env.example` to `.env` to customize:
- `WP_PORT` — WordPress port (default: 8080)
- `WP_ADMIN_USER` / `WP_ADMIN_PASSWORD` — Admin credentials
- `LARAVEL_ENV_PATH` — Relative path to Laravel `.env`
