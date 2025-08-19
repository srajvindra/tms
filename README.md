# Laravel TMS (Task Management System)

![Laravel](https://img.shields.io/badge/Laravel-12-red.svg)
![PHP](https://img.shields.io/badge/PHP-8.2+-blue.svg)
![License](https://img.shields.io/badge/License-MIT-green.svg)

A modern, feature-rich Task Management System built with Laravel 12, Livewire 3, and Tailwind CSS 4. Manage your tasks efficiently with a beautiful, responsive interface.

## ✨ Features

- **🎯 Task Management**: Create, edit, and manage tasks with detailed information
- **🔄 Recurring Tasks**: Support for recurring task patterns
- **📊 Priority System**: Visual priority indicators (Low, Medium, High, Urgent)
- **📈 Status Tracking**: Track task progress through various states
- **🔍 Advanced Search**: Filter and search tasks with multiple criteria
- **📱 Responsive Design**: Beautiful UI that works on all devices
- **⚡ Real-time Updates**: Livewire-powered reactive interface
- **🎨 Modern UI**: Glass-morphism design with gradient backgrounds
- **📋 Categories**: Organize tasks with primary and secondary categories

## 🛠️ Technology Stack

- **Backend**: Laravel 12 with PHP 8.2+
- **Frontend**: Livewire 3 + Tailwind CSS 4
- **Database**: MySQL/PostgreSQL/SQLite
- **Architecture**: Modular structure using nwidart/laravel-modules
- **Testing**: Pest PHP testing framework
- **Code Quality**: Laravel Pint for code formatting

## 📋 Requirements

- PHP 8.2 or higher
- Composer 2.x
- Node.js 16+ (optional, for frontend assets)
- MySQL 5.7+ / PostgreSQL 12+ / SQLite 3.8+
- Web server (Apache/Nginx) for production

## 🚀 Quick Start

### Automated Setup

1. **Clone the repository**:
   ```bash
   git clone <repository-url> tms-app
   cd tms-app
   ```

2. **Run the setup script**:
   ```bash
   chmod +x setup.sh
   ./setup.sh
   ```

3. **Start the development server**:
   ```bash
   php artisan serve
   ```

4. **Visit your application**: Open http://localhost:8000

### Manual Setup

If you prefer manual installation:

1. **Install PHP dependencies**:
   ```bash
   composer install
   ```

2. **Install Node.js dependencies** (optional):
   ```bash
   npm install
   ```

3. **Create environment file**:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Configure database** in `.env`:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=tms_database
   DB_USERNAME=your_username
   DB_PASSWORD=your_password
   ```

5. **Run migrations**:
   ```bash
   php artisan migrate
   ```

6. **Enable modules**:
   ```bash
   php artisan module:enable Tasks
   ```

7. **Build frontend assets** (optional):
   ```bash
   npm run build
   ```

## 📁 Project Structure

```
tms-app/
├── app/
│   └── Livewire/
│       └── Tasks/           # Task Livewire components
├── Modules/
│   └── Tasks/               # Tasks module
│       ├── app/
│       │   ├── Models/      # Task models
│       │   ├── Http/        # Controllers & Requests
│       │   └── Providers/   # Service providers
│       └── resources/
│           └── views/       # Module views
├── database/
│   └── migrations/          # Database migrations
├── resources/
│   └── views/
│       └── livewire/        # Livewire component views
├── public/                  # Public assets
├── setup.sh                 # Automated setup script
└── README.md               # This file
```

## 🎯 Usage

### Creating Tasks

1. Navigate to `/tasks/create`
2. Fill in the task details:
   - **What**: Task description (required)
   - **Source**: Where the task originated
   - **Action**: Required action
   - **Type**: Task type
   - **Category**: Primary category
   - **Category II**: Secondary category (optional)
   - **Priority**: Low, Medium, High, or Urgent
   - **Status**: Current status
   - **Comments**: Additional notes (optional)
   - **Recurring**: Set up recurring patterns

### Managing Tasks

1. Navigate to `/tasks` to view all tasks
2. Use the search and filter options to find specific tasks
3. Click on tasks to view or edit them
4. Tasks are displayed with visual priority indicators

### Task Status Flow

- ⏳ **Pending**: Newly created tasks
- 🔄 **In Progress**: Active tasks being worked on
- ✅ **Completed**: Finished tasks
- ❌ **Cancelled**: Cancelled tasks
- ⏸️ **On Hold**: Temporarily paused tasks

## 🧪 Testing

Run the test suite:

```bash
php artisan test
```

Run specific test files:

```bash
php artisan test tests/Feature/TaskTest.php
```

## 🎨 Code Style

Format your code using Laravel Pint:

```bash
./vendor/bin/pint
```

Check for style issues:

```bash
./vendor/bin/pint --test
```

## 📚 API Documentation

### Task Model

```php
Task::create([
    'what' => 'Task description',
    'source' => 'Source of task',
    'action' => 'Required action',
    'type' => 'Task type',
    'category' => 'Primary category',
    'category_ii' => 'Secondary category',
    'priority' => 'low|medium|high|urgent',
    'status' => 'pending|in_progress|completed|cancelled|on_hold',
    'comments' => 'Additional notes',
    'is_recurring' => true/false,
    'recurring_type' => 'Recurring pattern'
]);
```

### Available Routes

- `GET /tasks` - List all tasks
- `GET /tasks/create` - Show create task form
- `POST /tasks` - Store new task
- `GET /tasks/{id}` - Show specific task
- `GET /tasks/{id}/edit` - Show edit task form
- `PUT /tasks/{id}` - Update task
- `DELETE /tasks/{id}` - Delete task

## 🔧 Configuration

### Environment Variables

Key environment variables:

```env
APP_NAME="Laravel TMS"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_DATABASE=tms_database
DB_USERNAME=root
DB_PASSWORD=

CACHE_STORE=database
QUEUE_CONNECTION=database
SESSION_DRIVER=database
```

### Module Configuration

Tasks module configuration is located in:
- `Modules/Tasks/config/config.php`

## 🚀 Deployment

### Production Setup

1. **Set production environment**:
   ```bash
   APP_ENV=production
   APP_DEBUG=false
   ```

2. **Optimize for production**:
   ```bash
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   composer install --optimize-autoloader --no-dev
   ```

3. **Set proper permissions**:
   ```bash
   chmod -R 775 storage bootstrap/cache
   ```

4. **Configure web server** (Apache/Nginx) to point to `public/` directory

### Docker Support

Docker configuration files can be added for containerized deployment.

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

## 🐛 Troubleshooting

### Common Issues

1. **Permission errors**: Ensure storage and cache directories are writable
2. **Database connection errors**: Check database credentials in `.env`
3. **Module not found**: Run `php artisan module:enable Tasks`
4. **Frontend assets missing**: Run `npm run build`

### Debug Mode

Enable debug mode in `.env`:
```env
APP_DEBUG=true
LOG_LEVEL=debug
```

Check logs at `storage/logs/laravel.log`

## 📝 Changelog

### Version 1.0.0
- Initial release with task management functionality
- Livewire-powered reactive interface
- Modern UI with Tailwind CSS
- Modular architecture
- Comprehensive testing suite

## 📄 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## 🙏 Acknowledgments

- Laravel Framework
- Livewire
- Tailwind CSS
- Laravel Modules
- Pest PHP
- Laravel Pint

## 📞 Support

For support and questions:
- Check the [Issues](../../issues) page
- Review the documentation
- Run the setup script for automated configuration

---

**Happy Task Managing! 🎉**