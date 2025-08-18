# LoveSong 🎵

A Laravel-based web application for creating and managing custom song requests. Users can submit detailed song specifications, make payments, and receive their personalized songs.

## Features

### 🎼 Song Request Management
- **Custom Song Creation**: Submit detailed song requests with specifications including:
  - Recipient name and personal details
  - Musical style and mood preferences
  - Genre details and tempo specifications
  - Vocal and instrumental requirements
  - Song structure and inspiration notes
  - Special instructions

### 💳 Payment Processing
- **Stripe Integration**: Secure payment processing with Stripe
- **Multiple Payment Methods**: Support for various payment options
- **Payment Status Tracking**: Real-time payment status updates
- **Download Protection**: Secure file delivery after payment completion

### 👤 User Management
- **Authentication**: Laravel Jetstream with Sanctum
- **Email Verification**: Required for payment processing
- **User Profiles**: Track song request history and statistics
- **Admin Panel**: Administrative interface for managing requests

### 📁 File Management
- **S3 Integration**: Secure file storage and delivery
- **Download URLs**: Time-limited secure download links
- **File Tracking**: Monitor file sizes and delivery status

### 🎨 Modern UI/UX
- **Livewire Components**: Dynamic, reactive user interface
- **Tailwind CSS**: Modern, responsive design
- **Mobile Optimized**: Responsive design for all devices
- **Real-time Updates**: Live activity feeds and status updates

## Tech Stack

- **Backend**: Laravel 12 (PHP 8.2+)
- **Frontend**: Livewire 3, Tailwind CSS 3
- **Authentication**: Laravel Jetstream with Sanctum
- **Database**: MySQL/PostgreSQL/SQLite
- **File Storage**: AWS S3
- **Payment Processing**: Stripe
- **Email**: Resend
- **Development**: Laravel Sail, Vite

## Prerequisites

- PHP 8.2 or higher
- Composer
- Node.js 18+ and npm
- Docker (optional, for Laravel Sail)

## Installation

1. **Clone the repository**
   ```bash
   git clone <repository-url>
   cd lovesong
   ```

2. **Install PHP dependencies**
   ```bash
   composer install
   ```

3. **Install Node.js dependencies**
   ```bash
   npm install
   ```

4. **Environment setup**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. **Configure your environment variables**
   Edit `.env` file with your configuration:
   ```env
   APP_NAME="LoveSong"
   APP_ENV=local
   APP_DEBUG=true
   APP_URL=http://localhost:8000

   # Database
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=lovesong
   DB_USERNAME=root
   DB_PASSWORD=

   # AWS S3
   AWS_ACCESS_KEY_ID=your_access_key
   AWS_SECRET_ACCESS_KEY=your_secret_key
   AWS_DEFAULT_REGION=us-east-1
   AWS_BUCKET=your_bucket_name

   # Stripe
   STRIPE_KEY=your_stripe_publishable_key
   STRIPE_SECRET=your_stripe_secret_key
   STRIPE_WEBHOOK_SECRET=your_webhook_secret

   # Resend (Email)
   RESEND_API_KEY=your_resend_api_key
   ```

6. **Run database migrations**
   ```bash
   php artisan migrate
   ```

7. **Seed the database (optional)**
   ```bash
   php artisan db:seed
   ```

8. **Build frontend assets**
   ```bash
   npm run build
   ```

## Development

### Starting the development server

**Option 1: Using Laravel Sail (Docker)**
```bash
./vendor/bin/sail up
```

**Option 2: Using Laravel's built-in server**
```bash
php artisan serve
```

### Development workflow

1. **Start all development services**
   ```bash
   composer run dev
   ```
   This command starts:
   - Laravel development server
   - Queue listener
   - Log viewer (Pail)
   - Vite development server

2. **Run tests**
   ```bash
   php artisan test
   ```

3. **Code formatting**
   ```bash
   vendor/bin/pint
   ```

## Project Structure

```
lovesong/
├── app/
│   ├── Actions/           # Business logic actions
│   ├── Constants/         # Application constants
│   ├── Console/           # Artisan commands
│   ├── Http/              # Controllers and middleware
│   ├── Livewire/          # Livewire components
│   │   └── Admin/         # Admin-specific components
│   ├── Mail/              # Email templates
│   ├── Models/            # Eloquent models
│   ├── Providers/         # Service providers
│   ├── Services/          # Business services
│   ├── Traits/            # Reusable traits
│   └── View/              # View components
├── database/
│   ├── factories/         # Model factories
│   ├── migrations/        # Database migrations
│   └── seeders/           # Database seeders
├── resources/
│   ├── css/               # Stylesheets
│   ├── js/                # JavaScript files
│   └── views/             # Blade templates
├── routes/
│   ├── web.php            # Web routes
│   ├── api.php            # API routes
│   └── console.php        # Console routes
└── tests/                 # Application tests
```

## API Endpoints

### Public Routes
- `GET /` - Landing page
- `GET /privacy` - Privacy policy
- `GET /terms` - Terms of service
- `GET /contact` - Contact page
- `POST /contact` - Submit contact form

### Authenticated Routes
- `GET /dashboard` - User dashboard
- `GET /song-requests` - List song requests
- `POST /song-requests` - Create new song request
- `GET /song-requests/{id}` - View song request
- `PUT /song-requests/{id}` - Update song request
- `DELETE /song-requests/{id}` - Delete song request
- `GET /song-requests/{id}/download` - Download song file

### Payment Routes (Email verified)
- `GET /song-requests/{id}/payment` - Payment page
- `POST /song-requests/{id}/payment-intent` - Create payment intent
- `GET /song-requests/{id}/payment/success` - Payment success
- `GET /song-requests/{id}/payment/cancel` - Payment cancellation

### Admin Routes
- `GET /admin/dashboard` - Admin dashboard
- `GET /admin/song-requests` - Admin song requests list
- `PATCH /admin/song-requests/{id}/status` - Update request status
- `GET /admin/settings` - Admin settings

## Testing

The application includes comprehensive tests for all major functionality:

```bash
# Run all tests
php artisan test

# Run specific test file
php artisan test tests/Feature/SongRequestTest.php

# Run tests with coverage
php artisan test --coverage
```

## Deployment

### Production Checklist

1. **Environment Configuration**
   - Set `APP_ENV=production`
   - Set `APP_DEBUG=false`
   - Configure production database
   - Set up SSL certificates

2. **Security**
   - Generate application key: `php artisan key:generate`
   - Set secure session configuration
   - Configure proper file permissions

3. **Performance**
   - Run `php artisan config:cache`
   - Run `php artisan route:cache`
   - Run `php artisan view:cache`
   - Build production assets: `npm run build`

4. **Database**
   - Run migrations: `php artisan migrate --force`
   - Set up database backups

5. **File Storage**
   - Configure S3 bucket and permissions
   - Set up CDN if needed

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## Support

For support, email support@lovesong.com or create an issue in the repository.

## Roadmap

See [TODO.md](TODO.md) for current development priorities and planned features.
