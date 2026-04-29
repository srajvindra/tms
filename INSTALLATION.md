# Installation Guide - Laravel TMS

This guide provides comprehensive instructions for setting up the Laravel Task Management System in different environments.

## 📋 Table of Contents

- [System Requirements](#system-requirements)
- [Quick Setup (Automated)](#quick-setup-automated)
- [Manual Installation](#manual-installation)
- [Docker Installation](#docker-installation)
- [Production Deployment](#production-deployment)
- [Troubleshooting](#troubleshooting)

## 🔧 System Requirements

### Minimum Requirements

- **PHP**: 8.2 or higher
- **Composer**: 2.0 or higher  
- **Database**: MySQL 5.7+ / PostgreSQL 12+ / SQLite 3.8+
- **Web Server**: Apache 2.4+ / Nginx 1.18+
- **Memory**: 512MB RAM minimum (1GB recommended)
- **Disk Space**: 500MB free space

### Optional Requirements

- **Node.js**: 16+ (for frontend asset compilation)
- **NPM**: 8+ (comes with Node.js)
- **Redis**: 6+ (for caching and sessions)
- **Supervisor**: (for queue processing in production)

### PHP Extensions

Required PHP extensions:
```bash
- BCMath PHP Extension
- Ctype PHP Extension
- Fileinfo PHP Extension
- JSON PHP Extension
- Mbstring PHP Extension
- OpenSSL PHP Extension
- PDO PHP Extension
- Tokenizer PHP Extension
- XML PHP Extension
- GD PHP Extension
- Zip PHP Extension
```

## 🚀 Quick Setup (Automated)

### 1. Download and Extract

```bash
# Clone or download the project
git clone <repository-url> tms-app
cd tms-app
```

### 2. Run Setup Script

```bash
# Make script executable
chmod +x setup.sh

# Run automated setup
./setup.sh
```

The setup script will:
- ✅ Check system requirements
- ✅ Install PHP and Node.js dependencies
- ✅ Create and configure environment file
- ✅ Set up database configuration
- ✅ Run database migrations
- ✅ Build frontend assets
- ✅ Set proper file permissions
- ✅ Optimize the application

### 3. Start the Application

```bash
php artisan serve
```

Visit: http://localhost:8000

## 🛠️ Manual Installation

### Step 1: Install Dependencies

```bash
# Install PHP dependencies
composer install

# Install Node.js dependencies (optional)
npm install
```

### Step 2: Environment Configuration

```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate
```

### Step 3: Configure Database

Edit `.env` file with your database credentials:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=tms_database
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

### Step 4: Database Setup

```bash
# Create database (if using MySQL)
mysql -u root -p -e "CREATE DATABASE tms_database CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

# Run migrations
php artisan migrate

# Enable modules
php artisan module:enable Tasks
```

### Step 5: Build Assets (Optional)

```bash
# Development build
npm run dev

# Production build
npm run build
```

### Step 6: Set Permissions

```bash
# Set storage and cache permissions
chmod -R 775 storage bootstrap/cache

# If using Apache/Nginx, set ownership
sudo chown -R www-data:www-data storage bootstrap/cache
```

### Step 7: Application Optimization

```bash
# Cache configuration
php artisan config:cache

# Cache routes
php artisan route:cache

# Cache views
php artisan view:cache
```

## 🐳 Docker Installation

### Prerequisites

- Docker 20.10+
- Docker Compose 2.0+

### Quick Start with Docker

```bash
# Clone the repository
git clone <repository-url> tms-app
cd tms-app

# Start with Docker Compose
docker-compose up -d

# Run migrations
docker-compose exec app php artisan migrate

# Enable modules
docker-compose exec app php artisan module:enable Tasks
```

### Docker Services

The Docker setup includes:

- **app**: Laravel application (PHP-FPM + Nginx)
- **mysql**: MySQL 8.0 database
- **redis**: Redis for caching
- **nginx**: Web server (production profile)
- **node**: Node.js for asset building
- **queue**: Queue worker (production profile)
- **scheduler**: Task scheduler (production profile)

### Environment-specific Commands

```bash
# Development environment
docker-compose up -d

# Production environment
docker-compose --profile production up -d

# Build frontend assets
docker-compose run --rm node

# Access application shell
docker-compose exec app bash

# View logs
docker-compose logs -f app
```

### Docker Environment Variables

Create `.env` file with Docker-specific settings:

```env
# Application
APP_NAME="Laravel TMS"
APP_ENV=production
APP_DEBUG=false
APP_URL=http://localhost

# Database (Docker)
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=tms_database
DB_USERNAME=tms_user
DB_PASSWORD=tms_password

# Redis (Docker)
REDIS_HOST=redis
REDIS_PORT=6379

# Cache
CACHE_DRIVER=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis
```

## 🌐 Production Deployment

### Server Requirements

- Ubuntu 20.04+ / CentOS 8+ / Debian 11+
- 2GB+ RAM
- 2+ CPU cores
- 20GB+ disk space
- SSL certificate (recommended)

### 1. Server Setup

```bash
# Update system packages
sudo apt update && sudo apt upgrade -y

# Install required packages
sudo apt install -y nginx mysql-server redis-server supervisor curl git unzip

# Install PHP 8.2
sudo apt install -y php8.2-fpm php8.2-mysql php8.2-redis php8.2-xml php8.2-gd php8.2-zip php8.2-mbstring php8.2-curl php8.2-bcmath

# Install Composer
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer
```

### 2. Application Deployment

```bash
# Clone application
git clone <repository-url> /var/www/tms-app
cd /var/www/tms-app

# Set ownership
sudo chown -R www-data:www-data /var/www/tms-app

# Install dependencies
sudo -u www-data composer install --optimize-autoloader --no-dev

# Set permissions
sudo chmod -R 775 storage bootstrap/cache
```

### 3. Environment Configuration

```bash
# Copy and configure environment
sudo -u www-data cp .env.example .env
sudo -u www-data php artisan key:generate

# Configure database in .env
sudo nano .env
```

### 4. Database Setup

```bash
# Create database
sudo mysql -e "CREATE DATABASE tms_database CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
sudo mysql -e "CREATE USER 'tms_user'@'localhost' IDENTIFIED BY 'secure_password';"
sudo mysql -e "GRANT ALL PRIVILEGES ON tms_database.* TO 'tms_user'@'localhost';"
sudo mysql -e "FLUSH PRIVILEGES;"

# Run migrations
sudo -u www-data php artisan migrate --force
sudo -u www-data php artisan module:enable Tasks
```

### 5. Web Server Configuration

Create Nginx configuration:

```bash
sudo nano /etc/nginx/sites-available/tms-app
```

```nginx
server {
    listen 80;
    server_name your-domain.com;
    root /var/www/tms-app/public;
    index index.php;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.ht {
        deny all;
    }
}
```

Enable the site:
```bash
sudo ln -s /etc/nginx/sites-available/tms-app /etc/nginx/sites-enabled/
sudo nginx -t
sudo systemctl reload nginx
```

### 6. SSL Certificate (Let's Encrypt)

```bash
sudo apt install certbot python3-certbot-nginx
sudo certbot --nginx -d your-domain.com
```

### 7. Process Management

Create Supervisor configuration:

```bash
sudo nano /etc/supervisor/conf.d/tms-queue.conf
```

```ini
[program:tms-queue]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/tms-app/artisan queue:work --sleep=3 --tries=3
directory=/var/www/tms-app
autostart=true
autorestart=true
user=www-data
numprocs=2
redirect_stderr=true
stdout_logfile=/var/www/tms-app/storage/logs/queue.log
```

```bash
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start tms-queue:*
```

### 8. Cron Jobs

Add to crontab:
```bash
sudo -u www-data crontab -e
```

```cron
* * * * * cd /var/www/tms-app && php artisan schedule:run >> /dev/null 2>&1
```

### 9. Application Optimization

```bash
# Optimize for production
sudo -u www-data php artisan config:cache
sudo -u www-data php artisan route:cache
sudo -u www-data php artisan view:cache
sudo -u www-data php artisan event:cache
```

## 🔧 Troubleshooting

### Common Issues

#### Permission Errors
```bash
# Fix storage permissions
sudo chmod -R 775 storage bootstrap/cache
sudo chown -R www-data:www-data storage bootstrap/cache
```

#### Database Connection Errors
```bash
# Test database connection
php artisan migrate:status

# Check database credentials in .env
# Ensure database server is running
sudo systemctl status mysql
```

#### Module Not Found Errors
```bash
# Clear caches
php artisan config:clear
php artisan cache:clear
php artisan route:clear

# Enable modules
php artisan module:enable Tasks

# Dump autoload
composer dump-autoload
```

#### Frontend Assets Missing
```bash
# Build assets
npm run build

# Check public/build directory exists
ls -la public/build/
```

#### Queue Jobs Not Processing
```bash
# Check queue worker status
sudo supervisorctl status tms-queue:*

# Restart queue workers
sudo supervisorctl restart tms-queue:*

# Check queue logs
tail -f storage/logs/queue.log
```

### Debug Mode

Enable debug mode temporarily:

```bash
# In .env file
APP_DEBUG=true
LOG_LEVEL=debug

# Clear config cache
php artisan config:clear
```

Check logs:
```bash
tail -f storage/logs/laravel.log
```

### Performance Issues

```bash
# Enable OPcache
# Add to PHP configuration:
opcache.enable=1
opcache.memory_consumption=256
opcache.max_accelerated_files=20000

# Monitor performance
php artisan route:list
php artisan config:show
```

### Getting Help

1. Check the logs in `storage/logs/`
2. Verify environment configuration
3. Test each component individually
4. Check file permissions
5. Review server error logs
6. Use debug mode for detailed error information

## 🎉 Post-Installation

After successful installation:

1. **Access the application**: Visit your configured URL
2. **Create your first task**: Go to `/tasks/create`
3. **Explore features**: Navigate through the task management interface
4. **Set up monitoring**: Configure log monitoring and performance tracking
5. **Regular backups**: Set up automated database and file backups
6. **Security updates**: Keep the system and dependencies updated

---

**Need help?** Check the main [README.md](README.md) file or create an issue in the project repository.