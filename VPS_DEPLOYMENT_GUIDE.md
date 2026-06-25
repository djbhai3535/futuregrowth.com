# VPS Deployment Guide (Ubuntu + Nginx + MySQL)

Follow these steps to deploy the USDT Investment Platform on your Ubuntu VPS.

## 1. Prerequisites Installation

Connect to your VPS via SSH and install the necessary components:

```bash
sudo apt update && sudo apt upgrade -y
sudo apt install nginx mysql-server -y
sudo apt install php8.4 php8.4-fpm php8.4-mysql php8.4-mbstring php8.4-xml php8.4-bcmath php8.4-curl php8.4-zip unzip curl -y

# Install Composer
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer
```

## 2. Database Setup

Log into MySQL and create the database:

```bash
sudo mysql
```

Run the following SQL commands:
```sql
CREATE DATABASE usdt_platform;
CREATE USER 'usdt_user'@'localhost' IDENTIFIED BY 'StrongPassword123!';
GRANT ALL PRIVILEGES ON usdt_platform.* TO 'usdt_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

## 3. Upload Project & Configure

Upload the `THAIR PLATRFORM` folder to `/var/www/usdt_platform`.

```bash
cd /var/www/usdt_platform

# Install Laravel Dependencies
composer install --optimize-autoloader --no-dev

# Set Permissions
sudo chown -R www-data:www-data /var/www/usdt_platform
sudo chmod -R 775 /var/www/usdt_platform/storage
sudo chmod -R 775 /var/www/usdt_platform/bootstrap/cache

# Setup Environment File
cp .env.example .env
```

Edit your `.env` file to match your database:
```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=usdt_platform
DB_USERNAME=usdt_user
DB_PASSWORD=StrongPassword123!
```

```bash
# Generate Key and Migrate Database
php artisan key:generate
php artisan migrate --seed
```
*(The `--seed` flag will automatically generate the Admin Account, Default Plans, and Platform Settings!)*

## 4. Nginx Configuration

Create a new Nginx block:
```bash
sudo nano /etc/nginx/sites-available/usdt_platform
```

Paste the following (replace `yourdomain.com`):
```nginx
server {
    listen 80;
    listen [::]:80;
    server_name yourdomain.com;
    root /var/www/usdt_platform/public;
 
    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";
 
    index index.php;
 
    charset utf-8;
 
    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }
 
    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }
 
    error_page 404 /index.php;
 
    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.4-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
        fastcgi_hide_header X-Powered-By;
    }
 
    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

Enable the site and restart Nginx:
```bash
sudo ln -s /etc/nginx/sites-available/usdt_platform /etc/nginx/sites-enabled/
sudo nginx -t
sudo systemctl restart nginx
```

## 5. Cron Job Setup (CRITICAL FOR DAILY ROI)

The Daily ROI engine depends on Laravel's Task Scheduler. You must add it to the server's cron jobs.

```bash
crontab -e
```

Add this exact line to the bottom of the file:
```bash
* * * * * cd /var/www/usdt_platform && php artisan schedule:run >> /dev/null 2>&1
```

**Your platform is now live and fully automated!**
Log in with the default admin account:
**Email:** admin@admin.com
**Password:** password123
