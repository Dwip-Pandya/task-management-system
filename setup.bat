@echo off
SETLOCAL

echo =========================================
echo  Task Management System - Local Setup
echo =========================================

:: Check PHP
php -v >nul 2>&1
IF ERRORLEVEL 1 (
    echo PHP is not installed or not in PATH. Please install PHP first.
    pause
    exit /b
)

:: Check Composer
composer -V >nul 2>&1
IF ERRORLEVEL 1 (
    echo Composer is not installed or not in PATH. Please install Composer first.
    pause
    exit /b
)

:: Check MySQL
mysql -V >nul 2>&1
IF ERRORLEVEL 1 (
    echo MySQL is not installed or not in PATH. Please install MySQL first.
    pause
    exit /b
)

echo.
echo Installing PHP dependencies...
composer install
IF ERRORLEVEL 1 (
    echo Composer install failed.
    pause
    exit /b
)

echo.
echo Installing Laravel Socialite...
composer require laravel/socialite
IF ERRORLEVEL 1 (
    echo Socialite installation failed.
    pause
    exit /b
)

echo.
echo Copying .env file...
IF NOT EXIST ".env" (
    copy ".env.example" ".env"
)

echo.
echo Generating application key...
php artisan key:generate

echo.
echo Running migrations...
php artisan migrate
IF ERRORLEVEL 1 (
    echo Migration failed. Please check your database connection in .env.
    pause
    exit /b
)

echo.
echo Seeding database (if any)...
php artisan db:seed

echo.
echo Starting local development server...
php artisan serve

echo.
echo =========================================
echo Setup completed! Your project is running at http://127.0.0.1:8000
echo =========================================

pause
ENDLOCAL
