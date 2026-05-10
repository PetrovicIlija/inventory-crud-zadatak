#!/usr/bin/env bash
# ============================================================
#  Inventar — automatski setup u GitHub Codespaces
#  Pokreće se jednom, kad se Codespace prvi put kreira.
# ============================================================
set -e

echo ""
echo "=========================================="
echo " Inventar — postavljanje Laravel projekta"
echo "=========================================="
echo ""

# 1. Composer install (vendor/ se ne shipa u repu)
if [ ! -d vendor ]; then
  echo "==> composer install (može potrajati 1-2 min)..."
  composer install --no-interaction --prefer-dist --optimize-autoloader
fi

# 2. .env iz .env.codespaces (SQLite, bez MySQL-a)
if [ ! -f .env ]; then
  echo "==> Kreiram .env iz .env.codespaces..."
  cp .env.codespaces .env
fi

# 3. App key
if ! grep -q "^APP_KEY=base64:" .env; then
  echo "==> Generiram APP_KEY..."
  php artisan key:generate --force
fi

# 4. SQLite baza (samo prazna datoteka, migracije je popune)
if [ ! -f database/database.sqlite ]; then
  echo "==> Kreiram praznu SQLite bazu..."
  touch database/database.sqlite
fi

# 5. Migracije + seed
echo "==> Pokrećem migracije i seed..."
php artisan migrate --seed --force

# 6. Storage symlink
php artisan storage:link 2>/dev/null || true

# 7. Cache clear (rješava 404 na rutama)
php artisan optimize:clear >/dev/null 2>&1 || true

echo ""
echo "=========================================="
echo " Gotovo! Sve je spremno."
echo ""
echo " Sljedeći korak — u terminalu pokreni:"
echo "   php artisan serve --host=0.0.0.0"
echo ""
echo " Codespaces će ti automatski otvoriti preview tab."
echo "=========================================="
echo ""
