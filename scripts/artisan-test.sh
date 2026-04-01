#!/usr/bin/env bash
set -euo pipefail

ROOT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
cd "$ROOT_DIR"

if command -v php >/dev/null 2>&1; then
    exec php artisan test "$@"
fi

if command -v docker >/dev/null 2>&1 && docker compose version >/dev/null 2>&1; then
    COMPOSE_FILE=".devcontainer/docker-compose.yml"

    if [ ! -f "$COMPOSE_FILE" ]; then
        echo "PHP neni v PATH a nenalezl jsem $COMPOSE_FILE pro Docker fallback." >&2
        exit 1
    fi

    if [ ! -f "vendor/autoload.php" ]; then
        echo "Chybi vendor/autoload.php. Spoustim composer install v kontejneru..."
        docker compose -f "$COMPOSE_FILE" run --rm -w /workspace app composer install --no-interaction --prefer-dist
    fi

    if docker compose -f "$COMPOSE_FILE" ps --status running --services app 2>/dev/null | grep -qx "app"; then
        exec docker compose -f "$COMPOSE_FILE" exec -T -w /workspace app php artisan test "$@"
    fi

    echo "PHP neni v PATH. Spoustim testy v jednorazovem kontejneru z .devcontainer..."
    exec docker compose -f "$COMPOSE_FILE" run --rm -w /workspace app php artisan test "$@"
fi

echo "PHP neni v PATH a Docker Compose neni dostupny." >&2
echo "Pouzij devcontainer (Reopen in Container) nebo nainstaluj PHP lokalne." >&2
exit 1
