#!/bin/bash
set -e

echo "🔄 Déploiement de testing..."

cd /var/www/testing

echo "→ Passage de la propriété à ubuntu..."
sudo chown -R ubuntu:www-data /var/www/testing
sudo chmod -R 775 /var/www/testing/storage /var/www/testing/bootstrap/cache

echo "→ Récupération des derniers changements..."
git checkout -- .
git pull origin main

echo "→ Installation des dépendances PHP..."
php8.2 /usr/local/bin/composer install --optimize-autoloader --no-dev

echo "→ Installation des dépendances JS..."
npm install

echo "→ Build des assets front..."
npm run build

echo "→ Application des migrations..."
php8.2 artisan migrate --force

echo "→ Nettoyage des caches..."
php8.2 artisan config:clear
php8.2 artisan route:clear
php8.2 artisan view:clear

echo "→ Reconstruction des caches de production..."
php8.2 artisan config:cache
php8.2 artisan route:cache
php8.2 artisan view:cache

echo "→ Passage de la propriété à www-data..."
sudo chown -R ubuntu:www-data /var/www/testing
sudo chmod -R 775 /var/www/testing/storage /var/www/testing/bootstrap/cache

echo "✅ Déploiement terminé !"