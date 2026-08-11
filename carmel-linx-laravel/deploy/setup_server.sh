#!/bin/bash
# ==============================================================================
# Carmel Linx - Automated Server Setup & Deployment Script
# ==============================================================================
set -e

echo "🚀 Starting Carmel Linx Automated Deployment Setup..."

# 1. Update packages and install core dependencies
echo "📦 Installing system packages (PHP 8.2+, Apache2, MySQL, Composer, Git)..."
sudo apt-get update
sudo apt-get install -y php8.2 php8.2-cli php8.2-fpm php8.2-mysql php8.2-mbstring \
                        php8.2-xml php8.2-curl php8.2-zip php8.2-gd php8.2-bcmath \
                        apache2 libapache2-mod-php8.2 mysql-server composer git unzip

# 2. Enable Apache Modules
echo "⚙️ Enabling Apache modules (rewrite, headers, expires)..."
sudo a2enmod rewrite headers expires

# 3. Deploy Apache VirtualHost Config
echo "🌐 Configuring Apache VirtualHost..."
if [ -f "deploy/apache/carmel-linx.conf" ]; then
    sudo cp deploy/apache/carmel-linx.conf /etc/apache2/sites-available/carmel-linx.conf
    sudo a2ensite carmel-linx.conf
    sudo a2dissite 000-default.conf || true
fi

# 4. Storage & Directory Permissions
echo "🔒 Setting up file permissions for storage and bootstrap/cache..."
mkdir -p storage/framework/{sessions,views,cache} storage/logs
sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache

# 5. Environment File Setup
if [ ! -f ".env" ]; then
    echo "📄 Creating .env file from .env.example..."
    cp .env.example .env
fi

# 6. Install PHP Composer Dependencies
echo "⚡ Installing Composer dependencies..."
composer install --no-dev --optimize-autoloader

# 7. Generate Application Key
echo "🔑 Generating Application Key..."
php artisan key:generate --force

# 8. Restore Latest Database Backup if Available
echo "🗄️ Checking for latest Database Backup SQL file..."
LATEST_BACKUP=$(ls -t database/backups/*.sql 2>/dev/null | head -n 1 || true)
if [ -n "$LATEST_BACKUP" ]; then
    echo "📥 Found latest DB backup: $LATEST_BACKUP"
    echo "Notice: Execute mysql -u <user> -p <dbname> < $LATEST_BACKUP to restore database."
else
    echo "⚠️ No SQL dump found in database/backups/. Running standard migrations..."
    php artisan migrate --force || true
fi

# 9. Restart Web Server
echo "🔄 Restarting Apache Web Server..."
sudo systemctl restart apache2

echo "✅ Carmel Linx Server Deployment Setup Complete!"
