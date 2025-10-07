# Health Card Management System

A comprehensive digital health card management system built with Laravel, providing healthcare access and discounts through a network of partner hospitals.

## 🏥 Project Overview

The Health Card Management System is a full-stack web application that digitizes healthcare card issuance and usage. It serves users (patients), hospitals, staff, and admins, providing a structured way to manage health cards, hospital networks, and discounts for medical services.

## ✨ Key Features

### 🌐 Public Website
- **Modern Landing Page** with hero section, statistics, and features
- **About Us** page with company information and values
- **How It Works** step-by-step guide
- **Hospital Network** page with search and filtering
- **FAQs** comprehensive question and answer section
- **Contact Us** form with support information

### 👤 User Module
- **Registration** with OTP verification
- **Digital Health Card** generation with QR code
- **Dashboard** with health card access and download
- **Discount History** tracking
- **Hospital Search** with filters
- **Profile Management** with secure document uploads
- **Notifications** system
- **Support Tickets** for assistance

### 🏥 Hospital Module
- **Hospital Registration** and approval system
- **Service Management** with discount configuration
- **Card Verification** via QR code scanning
- **Patient Availment** tracking
- **Analytics Dashboard** with insights
- **Reports** generation

### 👨‍💼 Staff Module
- **User Registration** assistance
- **Hospital Registration** support
- **Document Verification** workflow
- **Limited Admin Rights** for support tasks

### 🔧 Admin Module
- **Super Admin Dashboard** with full system control
- **User Management** with approval workflows
- **Hospital Management** and approval system
- **Staff Management** and role assignment
- **Analytics** with charts and reports
- **Audit Logs** for security tracking
- **Notification System** for announcements

## 🛠️ Technology Stack

### Backend
- **PHP 8.2+** with Laravel 12.x framework
- **MySQL 8.x** database
- **Laravel Sanctum** for API authentication
- **DomPDF** for PDF generation
- **Simple QR Code** for QR code generation
- **Laravel Excel** for data export

### Frontend
- **HTML5, CSS3, JavaScript** (vanilla)
- **Bootstrap 5.3** for responsive design
- **Font Awesome** for icons
- **Chart.js** for analytics visualization
- **jQuery** for enhanced interactions

### Security & Features
- **bcrypt** password hashing
- **CSRF protection** on all forms
- **Role-based access control** (RBAC)
- **OTP verification** for registration
- **File encryption** for sensitive documents
- **Audit logging** for all actions
- **Session-based authentication**

## 📋 Prerequisites

- PHP 8.2 or higher
- Composer
- MySQL 8.0 or higher
- Node.js and NPM (for asset compilation)
- Web server (Apache/Nginx)

## 🚀 Installation

1. **Clone the repository**
   ```bash
   git clone https://github.com/your-username/health-card-system.git
   cd health-card-system
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

5. **Database configuration**
   - Create a MySQL database
   - Update `.env` file with database credentials:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=health_card_system
   DB_USERNAME=your_username
   DB_PASSWORD=your_password
   ```

6. **Run migrations and seeders**
   ```bash
   php artisan migrate --seed
   ```

7. **Create storage symlink**
   ```bash
   php artisan storage:link
   ```

8. **Compile assets**
   ```bash
   npm run build
   ```

9. **Start the development server**
   ```bash
   php artisan serve
   ```

## 🔐 Default Login Credentials

After running the seeders, you can use these default credentials:

### Admin (Single Admin System)
- **Email:** admin@gmail.com
- **Password:** admin123
- **Role:** Super Admin (manages all system functions)

### Staff
- **Email:** john.smith@gmail.com
- **Password:** staff123

### Hospital
- **Email:** info@apollohospitals.com
- **Password:** apollo123

### Test User
- **Email:** test@gmail.com
- **Password:** password

## 📁 Project Structure

```
health-card-system/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/          # Admin module controllers
│   │   │   ├── Hospital/       # Hospital module controllers
│   │   │   ├── Staff/          # Staff module controllers
│   │   │   ├── User/           # User module controllers
│   │   │   ├── Auth/           # Authentication controllers
│   │   │   └── Public/         # Public page controllers
│   │   └── Middleware/         # Custom middleware
│   ├── Models/                 # Eloquent models
│   ├── Services/               # Business logic services
│   └── Jobs/                   # Background jobs
├── database/
│   ├── migrations/             # Database migrations
│   └── seeders/                # Database seeders
├── resources/
│   ├── views/                  # Blade templates
│   │   ├── admin/              # Admin views
│   │   ├── hospital/           # Hospital views
│   │   ├── staff/              # Staff views
│   │   ├── user/               # User views
│   │   ├── auth/               # Authentication views
│   │   ├── public/             # Public pages
│   │   ├── components/         # Reusable components
│   │   └── layouts/            # Layout templates
│   ├── css/                    # Custom CSS
│   └── js/                     # Custom JavaScript
├── public/
│   ├── css/                    # Compiled CSS
│   ├── js/                     # Compiled JavaScript
│   └── storage/                # File storage
└── routes/
    ├── web.php                 # Web routes
    └── api.php                 # API routes
```

## 🔧 Configuration

### Mail Configuration
Update your `.env` file for email functionality:
```env
MAIL_MAILER=smtp
MAIL_HOST=your-smtp-host
MAIL_PORT=587
MAIL_USERNAME=your-email
MAIL_PASSWORD=your-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@healthcardsystem.com
MAIL_FROM_NAME="Health Card System"
```

### File Storage
The system uses Laravel's file storage for:
- User photos and Aadhaar documents
- Health card PDFs and QR codes
- Hospital logos and documents

## 📊 Database Schema

### Core Tables
- `users` - User accounts and profiles
- `hospitals` - Hospital information and credentials
- `staff` - Staff member accounts
- `admins` - Administrator accounts
- `health_cards` - Generated health cards
- `services` - Available medical services
- `hospital_services` - Hospital-specific services and discounts
- `patient_availments` - Service usage records
- `audit_logs` - System activity logs
- `notifications` - User notifications
- `support_tickets` - Customer support system

## 🔒 Security Features

- **Password Hashing:** All passwords are hashed using bcrypt
- **CSRF Protection:** All forms include CSRF tokens
- **File Encryption:** Sensitive documents are encrypted
- **Role-based Access:** Different access levels for different user types
- **Audit Logging:** All critical actions are logged
- **Session Security:** Secure session management
- **Input Validation:** All user inputs are validated and sanitized

## 📱 API Endpoints

The system includes RESTful API endpoints for mobile app integration:

### Authentication
- `POST /api/v1/register` - User registration
- `POST /api/v1/login` - User login
- `POST /api/v1/logout` - User logout
- `POST /api/v1/verify-otp` - OTP verification

### User Management
- `GET /api/v1/user` - Get user profile
- `PUT /api/v1/user` - Update user profile
- `GET /api/v1/health-card` - Get health card
- `GET /api/v1/hospitals` - List hospitals
- `GET /api/v1/services` - List services

## 🧪 Testing

Run the test suite:
```bash
php artisan test
```

## 📈 Performance Optimization

- **Database Indexing:** Optimized database queries
- **Caching:** Frequently accessed data is cached
- **Image Optimization:** Automatic image compression
- **Lazy Loading:** Images and content are loaded on demand
- **CDN Ready:** Static assets can be served via CDN

## 🚀 Deployment

### Production Deployment
1. Set up a production server with PHP 8.2+, MySQL, and web server
2. Clone the repository and install dependencies
3. Configure environment variables for production
4. Run migrations and seeders
5. Set up SSL certificate
6. Configure web server virtual host
7. Set up cron jobs for scheduled tasks

### Docker Deployment
```bash
# Build and run with Docker Compose
docker-compose up -d
```

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add some amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

## 📄 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## 🆘 Support

For support and questions:
- **Email:** support@healthcardsystem.com
- **Phone:** +91-800-123-4567
- **Documentation:** [Wiki](https://github.com/your-username/health-card-system/wiki)

## 🔮 Future Enhancements

- **Mobile App** (React Native/Flutter)
- **AI-powered Health Insights**
- **Telemedicine Integration**
- **Blockchain-based Card Verification**
- **Multi-language Support**
- **Advanced Analytics Dashboard**
- **Integration with Government Health Schemes**

## 🙏 Acknowledgments

- Laravel framework and community
- Bootstrap for UI components
- Font Awesome for icons
- All contributors and testers

---

**Made with ❤️ for better healthcare access**