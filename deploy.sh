#!/bin/bash

# DEPLOYMENT SCRIPT - SISTEM LOGISTIK PT DADS
# Jalankan script ini setelah upload kode ke server production

echo "🚀 Starting deployment process..."

# 1. Check if we're in the right directory
if [ ! -f "artisan" ]; then
    echo "❌ Error: artisan file not found. Are you in the Laravel project directory?"
    exit 1
fi

echo "✅ Laravel project detected"

# 2. Install/Update Composer Dependencies
echo "📦 Installing Composer dependencies..."
composer install --no-dev --optimize-autoloader --no-interaction --quiet

# 3. Generate application key if not exists
if grep -q "APP_KEY=" .env && [ -z "$(grep '^APP_KEY=' .env | cut -d '=' -f2)" ]; then
    echo "🔑 Generating application key..."
    php artisan key:generate --force
fi

# 4. Run database migrations
echo "🗄️ Running database migrations..."
php artisan migrate --force --no-interaction

# 5. Seed database if needed (uncomment if required)
# echo "🌱 Seeding database..."
# php artisan db:seed --force --no-interaction

# 6. Create storage link
echo "🔗 Creating storage symlink..."
php artisan storage:link

# 7. Cache optimization commands
echo "⚡ Optimizing application cache..."
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache

# 8. Build frontend assets (if npm is available)
if command -v npm &> /dev/null; then
    echo "🎨 Building frontend assets..."
    npm install --production
    npm run build
fi

# 9. Set proper permissions
echo "🔐 Setting file permissions..."
chmod -R 755 storage bootstrap/cache
chmod -R 644 storage/logs

# 10. Clear all caches one more time
echo "🧹 Final cache clearing..."
php artisan optimize:clear

echo "🎉 Deployment completed successfully!"
echo ""
echo "📋 Post-deployment checklist:"
echo "   1. Verify .env file configuration"
echo "   2. Test website functionality"
echo "   3. Check error logs: storage/logs/laravel.log"
echo "   4. Monitor application performance"
echo ""
echo "🌐 Application should now be ready at: $(grep APP_URL .env | cut -d '=' -f2)"
