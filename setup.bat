@echo off
echo 🏥 Health Card Management System Setup
echo ======================================

REM Check if PHP is installed
php --version >nul 2>&1
if %errorlevel% neq 0 (
    echo ❌ PHP is not installed. Please install PHP 8.2 or higher.
    pause
    exit /b 1
)

echo ✅ PHP is installed

REM Check if Composer is installed
composer --version >nul 2>&1
if %errorlevel% neq 0 (
    echo ❌ Composer is not installed. Please install Composer.
    pause
    exit /b 1
)

echo ✅ Composer is installed

REM Check if Node.js is installed
node --version >nul 2>&1
if %errorlevel% neq 0 (
    echo ❌ Node.js is not installed. Please install Node.js.
    pause
    exit /b 1
)

echo ✅ Node.js is installed

REM Install PHP dependencies
echo 📦 Installing PHP dependencies...
composer install

REM Install Node.js dependencies
echo 📦 Installing Node.js dependencies...
npm install

REM Check if .env file exists
if not exist .env (
    echo 📝 Creating .env file...
    copy .env.example .env
    echo ✅ .env file created. Please update database credentials.
) else (
    echo ✅ .env file already exists
)

REM Generate application key
echo 🔑 Generating application key...
php artisan key:generate

REM Create storage symlink
echo 🔗 Creating storage symlink...
php artisan storage:link

REM Ask about database setup
echo.
echo 🗄️  Database Setup
echo ==================
set /p run_migrations="Do you want to run migrations and seeders? (y/n): "
if /i "%run_migrations%"=="y" (
    echo 🔄 Running migrations...
    php artisan migrate
    
    echo 🌱 Running seeders...
    php artisan db:seed
    
    echo ✅ Database setup completed!
    echo.
    echo 🔐 Default Login Credentials:
    echo =============================
    echo Admin: admin@healthcardsystem.com / admin123
    echo Staff: john.smith@healthcardsystem.com / staff123
    echo Hospital: info@apollohospitals.com / apollo123
    echo User: test@example.com / password
) else (
    echo ⏭️  Skipping database setup. Run 'php artisan migrate --seed' when ready.
)

REM Compile assets
echo.
echo 🎨 Compiling assets...
npm run build

echo.
echo 🎉 Setup completed successfully!
echo.
echo 🚀 To start the development server, run:
echo    php artisan serve
echo.
echo 🌐 Then visit: http://localhost:8000
echo.
echo 📚 For more information, check the README.md file
pause
