# Health Card Management System

A comprehensive Health Card Management System built with Laravel 12, featuring user registration, hospital management, staff administration, and real-time analytics.

## Features

### 🏥 Core Modules
- **User Module**: Registration, health card generation, discount tracking
- **Hospital Module**: Service management, patient verification, analytics
- **Staff Module**: User registration, document verification, health card approval
- **Admin Module**: Complete system management, analytics, reporting

### 🔐 Security Features
- Multi-guard authentication (User, Hospital, Staff, Admin)
- Role-based access control
- OTP verification for registration
- Audit logging for all critical actions
- CSRF protection and secure file uploads

### 📊 Analytics & Reporting
- Real-time dashboard analytics
- Exportable reports (CSV, Excel, PDF)
- Hospital performance metrics
- User engagement tracking
- Financial impact analysis

## Quick Start

### Prerequisites
- PHP 8.2 or higher
- Composer
- SQLite (included) or MySQL
- Web server (Apache/Nginx) or PHP built-in server

### Installation

#### Option 1: Using the Start Script (Recommended)
```bash
# For Windows
start.bat

# For Linux/Mac
chmod +x start.sh
./start.sh
```

#### Option 2: Manual Setup
```bash
# Clone the repository
git clone <repository-url>
cd health-card-system

# Install dependencies
composer install

# Create environment file
cp .env.example .env

# Generate application key
php artisan key:generate

# Run migrations
php artisan migrate

# Seed database with sample data
php artisan db:seed

# Start the development server
php artisan serve
```

### Access the Application
- **URL**: http://127.0.0.1:8000
- **Admin Login**: admin@gmail.com / admin123
- **Staff Login**: john.smith@gmail.com / staff123
- **Hospital Login**: apollo.hospital@gmail.com / apollo123

### Test Login Page
Visit `http://127.0.0.1:8000/test-login.html` to easily test all login types with pre-filled credentials.

## System Architecture

### Database Structure
- **Users**: Patient information and health card data
- **Hospitals**: Hospital profiles and service offerings
- **Health Cards**: Generated cards with QR codes
- **Patient Availments**: Service usage and discount tracking
- **Audit Logs**: Complete activity tracking
- **Notifications**: System-wide communication

### Authentication Guards
- `web`: Regular users (patients)
- `hospital`: Hospital staff
- `staff`: System staff
- `admin`: System administrators

## Key Features

### User Registration & Health Card
1. **Registration Process**:
   - Personal information collection
   - Document upload (Aadhaar, photo)
   - OTP verification
   - Automatic health card generation

2. **Health Card Features**:
   - PDF generation with QR code
   - Digital download
   - QR code verification at hospitals
   - Validity tracking

### Hospital Management
1. **Service Management**:
   - Add/edit medical services
   - Set discount percentages
   - Manage service categories

2. **Patient Verification**:
   - QR code scanning
   - Card ID verification
   - Real-time validation

3. **Availment Tracking**:
   - Record patient visits
   - Track services used
   - Calculate discounts applied

### Admin Dashboard
1. **Analytics**:
   - Total cards issued
   - Hospital registrations
   - Service usage statistics
   - Financial impact reports

2. **Management**:
   - User approval/rejection
   - Hospital approval
   - Staff management
   - System settings

## API Endpoints

### Public Routes
- `GET /` - Homepage
- `GET /about` - About page
- `GET /hospital-network` - Hospital listings
- `POST /contact` - Contact form submission

### Authentication Routes
- `GET /login` - Login form
- `POST /login` - Login processing
- `GET /register` - Registration form
- `POST /register` - Registration processing
- `GET /verify-otp` - OTP verification

### Protected Routes
- `/user/*` - User dashboard and features
- `/hospital/*` - Hospital management
- `/staff/*` - Staff operations
- `/admin/*` - Admin panel

## Configuration

### Environment Variables
Key configuration options in `.env`:

```env
APP_NAME="Health Card Management System"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=sqlite
DB_DATABASE=database/database.sqlite

MAIL_MAILER=log
```

### File Storage
- **Public Files**: `storage/app/public/`
- **Private Files**: `storage/app/private/`
- **Health Cards**: `storage/app/health-cards/`

## Development

### Code Structure
```
app/
├── Http/Controllers/
│   ├── Admin/          # Admin functionality
│   ├── Auth/           # Authentication
│   ├── Hospital/       # Hospital management
│   ├── Public/         # Public pages
│   ├── Staff/          # Staff operations
│   └── User/           # User features
├── Models/             # Eloquent models
├── Services/           # Business logic
└── Mail/              # Email templates

resources/views/
├── admin/             # Admin views
├── auth/              # Authentication views
├── hospital/          # Hospital views
├── public/            # Public pages
├── staff/             # Staff views
└── user/              # User views
```

### Adding New Features
1. Create migration: `php artisan make:migration create_feature_table`
2. Create model: `php artisan make:model Feature`
3. Create controller: `php artisan make:controller FeatureController`
4. Add routes in `routes/web.php`
5. Create views in `resources/views/`

## Testing

### Running Tests
```bash
# Run all tests
php artisan test

# Run specific test
php artisan test --filter=FeatureTest
```

### Test Coverage
- Unit tests for models and services
- Feature tests for API endpoints
- Browser tests for user interactions

## Deployment

### Production Setup
1. Set `APP_ENV=production` in `.env`
2. Set `APP_DEBUG=false`
3. Configure proper database connection
4. Set up SSL certificate
5. Configure web server (Apache/Nginx)
6. Set up queue workers for background jobs

### Performance Optimization
- Enable OPcache
- Use Redis for caching
- Configure database indexing
- Optimize images and assets
- Use CDN for static files

## Troubleshooting

### Common Issues

1. **Database Connection Error**:
   - Check database configuration in `.env`
   - Ensure database file exists and is writable
   - Run `php artisan migrate:fresh`

2. **File Upload Issues**:
   - Check storage directory permissions
   - Verify file size limits in PHP configuration
   - Ensure proper file type validation

3. **Authentication Problems**:
   - Clear application cache: `php artisan cache:clear`
   - Regenerate application key: `php artisan key:generate`
   - Check session configuration

### Logs
- Application logs: `storage/logs/laravel.log`
- Web server logs: Check your web server configuration
- Database logs: Enable query logging in development

## Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Add tests for new functionality
5. Submit a pull request

## License

This project is licensed under the MIT License.

## Support

For support and questions:
- Create an issue in the repository
- Check the documentation
- Review the troubleshooting section

---

**Health Card Management System** - Streamlining healthcare access through digital innovation.