# Setup Guide for Laravel LMS

This guide will walk you through setting up the Laravel LMS from scratch.

## Prerequisites

Before you begin, ensure you have the following installed:

- **PHP 8.2 or higher** with required extensions:
  - OpenSSL
  - PDO
  - Mbstring
  - Tokenizer
  - XML
  - Ctype
  - JSON
  - BCMath
  - Fileinfo
  
- **Composer** (latest version)
- **MySQL 8.0 or higher** (or MariaDB 10.3+)
- **Node.js 18+ and NPM** (for frontend assets)
- **Git**

## Step-by-Step Installation

### 1. Clone the Repository

```bash
git clone https://github.com/romchy222/-laravellm-sv1.git
cd -laravellm-sv1
```

### 2. Install PHP Dependencies

```bash
composer install
```

This will install all required PHP packages including:
- Laravel Framework 11
- Spatie Laravel-Permission (roles & permissions)
- Maatwebsite Excel (import/export)
- Intervention Image (image processing)
- And more...

### 3. Install Node Dependencies

```bash
npm install
```

### 4. Environment Configuration

Copy the example environment file:

```bash
cp .env.example .env
```

Generate application key:

```bash
php artisan key:generate
```

### 5. Configure Database

Edit `.env` file and set your database credentials:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravellm_sv1
DB_USERNAME=root
DB_PASSWORD=your_password_here
```

Create the database:

```bash
mysql -u root -p
CREATE DATABASE laravellm_sv1 CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
exit;
```

### 6. Run Migrations

Run all database migrations:

```bash
php artisan migrate
```

This will create all necessary tables:
- users, roles, permissions
- courses, course_modules, lessons
- enrollments, groups
- questions, user_progress
- achievements, certificates
- And 20+ more tables

### 7. Seed Initial Data

Seed the database with roles and permissions:

```bash
php artisan db:seed
```

This creates:
- **5 roles**: student, teacher, curator, moderator, admin
- **30+ permissions** with proper assignments

### 8. Create Storage Link

Create symbolic link for file storage:

```bash
php artisan storage:link
```

### 9. Build Frontend Assets

```bash
npm run build
```

For development with hot reload:

```bash
npm run dev
```

### 10. Start Development Server

```bash
php artisan serve
```

Your application will be available at: `http://localhost:8000`

## Creating Your First Admin User

### Option 1: Using Tinker

```bash
php artisan tinker
```

```php
$user = App\Models\User::create([
    'name' => 'Admin User',
    'email' => 'admin@example.com',
    'password' => Hash::make('password'),
    'status' => 'active',
]);

$user->assignRole('admin');
```

### Option 2: Using Database Seeder

Create a new seeder:

```bash
php artisan make:seeder AdminUserSeeder
```

Add to `database/seeders/AdminUserSeeder.php`:

```php
<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'status' => 'active',
        ]);

        $admin->assignRole('admin');
    }
}
```

Run the seeder:

```bash
php artisan db:seed --class=AdminUserSeeder
```

## Importing Users from CSV

### CSV Format

Create a CSV file with the following columns:
```csv
name,email,password,phone
John Doe,john@example.com,password123,+1234567890
Jane Smith,jane@example.com,password456,+0987654321
```

### Import Command

```bash
php artisan users:import users.csv --role=student
```

Options:
- `--role=student` - Assign role (student, teacher, curator, moderator, admin)

## Testing the API

### 1. Register a User

```bash
curl -X POST http://localhost:8000/register \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Test User",
    "email": "test@example.com",
    "password": "password123",
    "password_confirmation": "password123"
  }'
```

### 2. Login

```bash
curl -X POST http://localhost:8000/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "test@example.com",
    "password": "password123"
  }'
```

### 3. Get Courses (Public)

```bash
curl http://localhost:8000/api/courses
```

### 4. Get User Profile (Authenticated)

```bash
curl http://localhost:8000/api/profile \
  -H "Authorization: Bearer YOUR_TOKEN_HERE"
```

See [API_DOCUMENTATION.md](API_DOCUMENTATION.md) for complete API reference.

## Optional Configuration

### Email Configuration

For sending emails, configure your mail settings in `.env`:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@yourdomain.com"
MAIL_FROM_NAME="${APP_NAME}"
```

### Queue Configuration

For better performance, configure queues:

```env
QUEUE_CONNECTION=database
```

Run queue worker:

```bash
php artisan queue:work
```

### Cache Configuration

For production, use Redis or Memcached:

```env
CACHE_STORE=redis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379
```

### Payment Gateway Configuration

Add your payment provider credentials:

```env
# Kaspi Pay
KASPI_MERCHANT_ID=your_merchant_id
KASPI_API_KEY=your_api_key

# Stripe
STRIPE_KEY=your_stripe_key
STRIPE_SECRET=your_stripe_secret

# PayPal
PAYPAL_CLIENT_ID=your_client_id
PAYPAL_SECRET=your_secret

# YooKassa
YOOKASSA_SHOP_ID=your_shop_id
YOOKASSA_SECRET_KEY=your_secret_key
```

## Development Tips

### Running Tests

```bash
php artisan test
```

### Code Style

Format code with Laravel Pint:

```bash
./vendor/bin/pint
```

### Database Reset

To reset and reseed database:

```bash
php artisan migrate:fresh --seed
```

⚠️ **Warning**: This will delete all data!

### Optimize for Production

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
composer install --optimize-autoloader --no-dev
```

## Troubleshooting

### Common Issues

1. **Permission Denied on storage/logs**
   ```bash
   chmod -R 775 storage bootstrap/cache
   chown -R www-data:www-data storage bootstrap/cache
   ```

2. **Class not found errors**
   ```bash
   composer dump-autoload
   ```

3. **Migration errors**
   ```bash
   php artisan migrate:rollback
   php artisan migrate
   ```

4. **Spatie Permission errors**
   ```bash
   php artisan cache:clear
   php artisan config:clear
   composer dump-autoload
   ```

## Next Steps

1. **Create Categories**: Add course categories via API or tinker
2. **Create a Course**: Use the API to create your first course
3. **Add Modules and Lessons**: Structure your course content
4. **Create Questions**: Build quizzes for your lessons
5. **Test Enrollment**: Enroll in a course and test progress tracking
6. **Customize**: Modify models, controllers, and views to fit your needs

## Additional Resources

- [Laravel Documentation](https://laravel.com/docs)
- [Spatie Permission Documentation](https://spatie.be/docs/laravel-permission)
- [API Documentation](API_DOCUMENTATION.md)
- [Implementation Details](IMPLEMENTATION.md)

## Support

For issues and questions:
- GitHub Issues: https://github.com/romchy222/-laravellm-sv1/issues
- Pull Requests welcome!

## License

MIT License - see [LICENSE](LICENSE) file for details.

---

**Happy Learning! 🎓**
