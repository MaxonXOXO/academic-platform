# Carmel Linx - Server Deployment & Disaster Recovery Guide

This repository contains full source code, database dumps, web server configurations, and deployment scripts to reproduce or configure a brand-new production server.

---

## 📋 System Prerequisites

* **Operating System**: Ubuntu 22.04 LTS / 24.04 LTS (or Linux Debian derivative)
* **PHP**: 8.2 or 8.3 with required extensions (`mbstring`, `mysql`, `xml`, `gd`, `zip`, `curl`)
* **Web Server**: Apache2 (with `mod_rewrite` enabled) or Nginx
* **Database**: MySQL 8.0+ or MariaDB
* **Storage Drive**: 1TB SATA HDD mounted at `/var/www/uploads` (for media & document archives)

---

## 🚀 Quick Automated Deployment (New Server)

To deploy on a new server, run the automated setup script:

```bash
# 1. Clone repository
git clone https://github.com/MaxonXOXO/academic-platform.git
cd academic-platform/carmel-linx-laravel

# 2. Make setup script executable and run
chmod +x deploy/setup_server.sh
./deploy/setup_server.sh
```

---

## 🛠️ Manual Deployment Steps

### 1. Web Server Virtual Host Config
Copy the included Apache configuration from `deploy/apache/carmel-linx.conf`:

```bash
sudo cp deploy/apache/carmel-linx.conf /etc/apache2/sites-available/carmel-linx.conf
sudo a2ensite carmel-linx.conf
sudo a2enmod rewrite
sudo systemctl restart apache2
```

### 2. Environment Configuration
Copy `.env.example` to `.env` and update your database credentials:

```bash
cp .env.example .env
nano .env
```

Set the database settings:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=carmel_linx_db
DB_USERNAME=carmel_user
DB_PASSWORD=your_secure_password
```

### 3. Database Restoration
Import the latest SQL dump included in `database/backups/`:

```bash
mysql -u carmel_user -p carmel_linx_db < database/backups/carmel_linx_db_backup_20260811_191442.sql
```

### 4. Storage & Permissions Setup
Ensure storage and bootstrap directories are writable by the web server:

```bash
sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache
```

---

## 💾 Backup Verification

* **Repository Database Dump**: `database/backups/carmel_linx_db_backup_20260811_191442.sql`
* **1TB SATA HDD Storage Backup**: `/var/www/uploads/backups/db_backup_20260811_191442.sql.gz`
* **Git Remote**: `https://github.com/MaxonXOXO/academic-platform.git`
