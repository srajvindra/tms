#!/bin/bash

# =============================================================================
# Laravel TMS (Task Management System) Setup Script
# =============================================================================
# This script sets up the Laravel Task Management System with all dependencies,
# configurations, and database migrations.
# 
# Usage: chmod +x setup.sh && ./setup.sh
# =============================================================================

set -e  # Exit on any error

# Color codes for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
PURPLE='\033[0;35m'
CYAN='\033[0;36m'
NC='\033[0m' # No Color

# Project information
PROJECT_NAME="Laravel TMS (Task Management System)"
VERSION="1.0.0"

# Function to print colored output
print_status() {
    echo -e "${BLUE}[INFO]${NC} $1"
}

print_success() {
    echo -e "${GREEN}[SUCCESS]${NC} $1"
}

print_warning() {
    echo -e "${YELLOW}[WARNING]${NC} $1"
}

print_error() {
    echo -e "${RED}[ERROR]${NC} $1"
}

print_header() {
    echo -e "${PURPLE}
╔════════════════════════════════════════════════════════════╗
║                   ${PROJECT_NAME}                    ║
║                      Setup Script v${VERSION}                      ║
╚════════════════════════════════════════════════════════════╝${NC}
"
}

# Function to check if a command exists
command_exists() {
    command -v "$1" >/dev/null 2>&1
}

# Function to check PHP version
check_php_version() {
    print_status "Checking PHP version..."
    if command_exists php; then
        PHP_VERSION=$(php -r "echo PHP_MAJOR_VERSION.'.'.PHP_MINOR_VERSION;")
        if [[ "$(printf '%s\n' "8.2" "$PHP_VERSION" | sort -V | head -n1)" = "8.2" ]]; then
            print_success "PHP $PHP_VERSION is installed and meets requirements (>= 8.2)"
        else
            print_error "PHP 8.2+ is required. Current version: $PHP_VERSION"
            exit 1
        fi
    else
        print_error "PHP is not installed. Please install PHP 8.2+ first."
        exit 1
    fi
}

# Function to check Node.js version
check_node_version() {
    print_status "Checking Node.js version..."
    if command_exists node; then
        NODE_VERSION=$(node -v | cut -d'v' -f2)
        print_success "Node.js $NODE_VERSION is installed"
    else
        print_warning "Node.js is not installed. Some frontend features may not work."
        read -p "Do you want to continue without Node.js? (y/N): " -n 1 -r
        echo
        if [[ ! $REPLY =~ ^[Yy]$ ]]; then
            exit 1
        fi
    fi
}

# Function to check Composer
check_composer() {
    print_status "Checking Composer..."
    if command_exists composer; then
        COMPOSER_VERSION=$(composer --version | grep -oE '[0-9]+\.[0-9]+\.[0-9]+' | head -1)
        print_success "Composer $COMPOSER_VERSION is installed"
    else
        print_error "Composer is not installed. Please install Composer first."
        exit 1
    fi
}

# Function to install PHP dependencies
install_php_dependencies() {
    print_status "Installing PHP dependencies..."
    if [ -f "composer.json" ]; then
        composer install --optimize-autoloader
        print_success "PHP dependencies installed successfully"
    else
        print_error "composer.json not found. Are you in the project root directory?"
        exit 1
    fi
}

# Function to install Node.js dependencies
install_node_dependencies() {
    if command_exists npm && [ -f "package.json" ]; then
        print_status "Installing Node.js dependencies..."
        npm install
        print_success "Node.js dependencies installed successfully"
    else
        print_warning "Skipping Node.js dependencies installation"
    fi
}

# Function to setup environment file
setup_environment() {
    print_status "Setting up environment configuration..."
    
    if [ ! -f ".env" ]; then
        if [ -f ".env.example" ]; then
            cp .env.example .env
            print_success "Environment file created from .env.example"
        else
            print_warning ".env.example not found. Creating basic .env file..."
            create_basic_env
        fi
    else
        print_warning ".env file already exists. Skipping environment setup."
        return
    fi
    
    # Generate application key
    php artisan key:generate --force
    print_success "Application key generated"
}

# Function to create basic environment file
create_basic_env() {
    cat > .env << EOF
APP_NAME="Laravel TMS"
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_TIMEZONE=UTC
APP_URL=http://localhost

APP_LOCALE=en
APP_FALLBACK_LOCALE=en
APP_FAKER_LOCALE=en_US

APP_MAINTENANCE_DRIVER=file

PHP_CLI_SERVER_WORKERS=4

BCRYPT_ROUNDS=12

LOG_CHANNEL=stack
LOG_STACK=single
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=debug

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=tms_database
DB_USERNAME=root
DB_PASSWORD=

SESSION_DRIVER=database
SESSION_LIFETIME=120
SESSION_ENCRYPT=false
SESSION_PATH=/
SESSION_DOMAIN=null

BROADCAST_CONNECTION=log
FILESYSTEM_DISK=local
QUEUE_CONNECTION=database

CACHE_STORE=database
CACHE_PREFIX=

MEMCACHED_HOST=127.0.0.1

REDIS_CLIENT=phpredis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

MAIL_MAILER=log
MAIL_HOST=127.0.0.1
MAIL_PORT=2525
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="hello@example.com"
MAIL_FROM_NAME="\${APP_NAME}"

AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=
AWS_USE_PATH_STYLE_ENDPOINT=false

VITE_APP_NAME="\${APP_NAME}"
EOF
}

# Function to configure database
configure_database() {
    print_status "Configuring database..."
    
    read -p "Enter database name (default: tms_database): " DB_NAME
    DB_NAME=${DB_NAME:-tms_database}
    
    read -p "Enter database username (default: root): " DB_USER
    DB_USER=${DB_USER:-root}
    
    read -s -p "Enter database password (press Enter for no password): " DB_PASS
    echo
    
    read -p "Enter database host (default: 127.0.0.1): " DB_HOST
    DB_HOST=${DB_HOST:-127.0.0.1}
    
    read -p "Enter database port (default: 3306): " DB_PORT
    DB_PORT=${DB_PORT:-3306}
    
    # Update .env file
    sed -i "s/DB_DATABASE=.*/DB_DATABASE=$DB_NAME/" .env
    sed -i "s/DB_USERNAME=.*/DB_USERNAME=$DB_USER/" .env
    sed -i "s/DB_PASSWORD=.*/DB_PASSWORD=$DB_PASS/" .env
    sed -i "s/DB_HOST=.*/DB_HOST=$DB_HOST/" .env
    sed -i "s/DB_PORT=.*/DB_PORT=$DB_PORT/" .env
    
    print_success "Database configuration updated"
}

# Function to test database connection
test_database_connection() {
    print_status "Testing database connection..."
    
    if php artisan migrate:status >/dev/null 2>&1; then
        print_success "Database connection successful"
        return 0
    else
        print_error "Database connection failed"
        print_warning "Please check your database configuration and ensure the database server is running"
        read -p "Do you want to continue without running migrations? (y/N): " -n 1 -r
        echo
        if [[ ! $REPLY =~ ^[Yy]$ ]]; then
            exit 1
        fi
        return 1
    fi
}

# Function to run database migrations
run_migrations() {
    print_status "Running database migrations..."
    
    if php artisan migrate --force; then
        print_success "Database migrations completed successfully"
    else
        print_error "Database migrations failed"
        exit 1
    fi
}

# Function to setup modules
setup_modules() {
    print_status "Setting up Laravel modules..."
    
    # Publish module assets
    if php artisan module:publish-config; then
        print_success "Module configuration published"
    else
        print_warning "Module configuration publishing failed or not needed"
    fi
    
    # Enable Tasks module
    if php artisan module:enable Tasks; then
        print_success "Tasks module enabled"
    else
        print_warning "Tasks module enable failed or already enabled"
    fi
}

# Function to optimize application
optimize_application() {
    print_status "Optimizing application..."
    
    # Clear and cache config
    php artisan config:clear
    php artisan config:cache
    
    # Clear and cache routes
    php artisan route:clear
    php artisan route:cache
    
    # Clear and cache views
    php artisan view:clear
    php artisan view:cache
    
    # Optimize autoloader
    composer dump-autoload --optimize
    
    print_success "Application optimized successfully"
}

# Function to build frontend assets
build_frontend() {
    if command_exists npm && [ -f "package.json" ]; then
        print_status "Building frontend assets..."
        npm run build
        print_success "Frontend assets built successfully"
    else
        print_warning "Skipping frontend asset building"
    fi
}

# Function to set permissions
set_permissions() {
    print_status "Setting proper file permissions..."
    
    # Set storage and cache permissions
    chmod -R 775 storage bootstrap/cache
    
    # If running as www-data or apache user
    if [ "$USER" = "www-data" ] || [ "$USER" = "apache" ]; then
        chown -R $USER:$USER storage bootstrap/cache
    fi
    
    print_success "File permissions set successfully"
}

# Function to create initial admin user (optional)
create_admin_user() {
    read -p "Do you want to create an admin user? (y/N): " -n 1 -r
    echo
    if [[ $REPLY =~ ^[Yy]$ ]]; then
        print_status "Creating admin user..."
        # This would require a seeder or factory - placeholder for now
        print_warning "Admin user creation not implemented yet. You can create users manually."
    fi
}

# Function to show completion message
show_completion_message() {
    print_success "Setup completed successfully!"
    echo -e "${CYAN}
╔════════════════════════════════════════════════════════════╗
║                    SETUP COMPLETE!                         ║
╠════════════════════════════════════════════════════════════╣
║  Your Laravel TMS application is now ready to use!        ║
║                                                            ║
║  Next steps:                                               ║
║  1. Start the development server: php artisan serve       ║
║  2. Visit: http://localhost:8000                           ║
║  3. Start frontend dev server: npm run dev (optional)     ║
║                                                            ║
║  Available routes:                                         ║
║  • Tasks List: /tasks                                      ║
║  • Create Task: /tasks/create                              ║
║                                                            ║
║  For production deployment, remember to:                   ║
║  • Set APP_ENV=production in .env                          ║
║  • Set APP_DEBUG=false in .env                             ║
║  • Configure your web server                               ║
║  • Set up proper SSL certificates                          ║
╚════════════════════════════════════════════════════════════╝${NC}
"
}

# Main setup function
main() {
    print_header
    
    # Check prerequisites
    check_php_version
    check_composer
    check_node_version
    
    # Install dependencies
    install_php_dependencies
    install_node_dependencies
    
    # Setup application
    setup_environment
    configure_database
    
    # Test database and run migrations
    if test_database_connection; then
        run_migrations
    fi
    
    # Setup modules
    setup_modules
    
    # Optimize application
    optimize_application
    
    # Build frontend assets
    build_frontend
    
    # Set permissions
    set_permissions
    
    # Optional admin user creation
    create_admin_user
    
    # Show completion message
    show_completion_message
}

# Handle script interruption
trap 'print_error "Setup interrupted!"; exit 1' INT TERM

# Run main function
main "$@"