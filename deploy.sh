#!/bin/bash
set -e

chmod +x "$0"

ENV="${1:-testing}"

if [ "$ENV" = "production" ]; then
    DIR="/var/www/iawt"
else
    DIR="/var/www/testing"
fi

echo "🔄 Déploiement de InAfrikaWeTrust ($ENV)..."

cd "$DIR"

echo "→ Récupération des derniers changements..."
git stash --include-untracked -q 2>/dev/null || true
git pull origin main
git stash pop -q 2>/dev/null || true

chmod +x deploy.sh

echo "→ Permissions storage..."
sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache

echo "→ Installation des dépendances PHP..."
composer install --optimize-autoloader --no-dev

echo "→ Installation des dépendances JS..."
npm install

echo "→ Build des assets front..."
npm run build

echo "→ Application des migrations..."
php artisan migrate --force

echo "→ Nettoyage des caches..."
php artisan config:clear
php artisan route:clear
php artisan view:clear

echo "→ Reconstruction des caches de production..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "→ Permissions finales..."
sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache

echo "✅ Déploiement terminé ($ENV) !"