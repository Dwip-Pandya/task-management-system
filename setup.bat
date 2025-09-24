@echo off
SETLOCAL

echo =========================================
echo  Task Management System - Local Setup
echo =========================================
echo.

:: ------------------------------
:: Check PHP
:: ------------------------------
php -v >nul 2>&1
IF ERRORLEVEL 1 (
    echo [ERROR] PHP is not installed or not in PATH. Please install PHP first.
    pause
    exit /b
)

:: ------------------------------
:: Check required PHP extensions: gd and zip
:: ------------------------------
echo Checking PHP extensions...
php -m | findstr /i "gd" >nul 2>&1
IF ERRORLEVEL 1 (
    echo [ERROR] PHP GD extension is not enabled. Please enable 'extension=gd' in php.ini
    pause
    exit /b
)

php -m | findstr /i "zip" >nul 2>&1
IF ERRORLEVEL 1 (
    echo [ERROR] PHP ZIP extension is not enabled. Please enable 'extension=zip' in php.ini
    pause
    exit /b
)

:: ------------------------------
:: Check Composer
:: ------------------------------
composer -V >nul 2>&1
IF ERRORLEVEL 1 (
    echo [ERROR] Composer is not installed or not in PATH. Please install Composer first.
    pause
    exit /b
)

:: ------------------------------
:: Check MySQL
:: ------------------------------
mysql -V >nul 2>&1
IF ERRORLEVEL 1 (
    echo [ERROR] MySQL is not installed or not in PATH. Please install MySQL first.
    pause
    exit /b
)

echo.
echo Installing PHP dependencies...
composer install
IF ERRORLEVEL 1 (
    echo [ERROR] Composer install failed.
    pause
    exit /b
)

echo.
set /p install_socialite="Do you want to install Laravel Socialite? (Y/N): "
if /I "%install_socialite%"=="Y" (
    echo Installing Laravel Socialite...
    composer require laravel/socialite
    IF ERRORLEVEL 1 (
        echo [ERROR] Socialite installation failed.
        pause
        exit /b
    )
) else (
    echo Skipping Socialite installation...
)

echo.
echo Copying .env file...
IF NOT EXIST ".env" (
    copy ".env.example" ".env"
    IF ERRORLEVEL 1 (
        echo [ERROR] Failed to copy .env file.
        pause
        exit /b
    )
) ELSE (
    echo .env file already exists.
)

echo.
echo Generating application key...
php artisan key:generate
IF ERRORLEVEL 1 (
    echo [ERROR] Failed to generate application key.
    pause
    exit /b
)

echo.
echo Running database migrations...
php artisan migrate
IF ERRORLEVEL 1 (
    echo [ERROR] Migrations failed. Please check your database connection in .env.
    pause
    exit /b
)

echo.
echo Seeding database (if applicable)...
php artisan db:seed
IF ERRORLEVEL 1 (
    echo [WARNING] Seeding failed or no seeders found. Continuing...
)

echo.
echo Starting Laravel development server...
php artisan serve
IF ERRORLEVEL 1 (
    echo [ERROR] Failed to start the server.
    pause
    exit /b
)

echo.
echo =========================================
echo Setup completed! Your project is running at http://127.0.0.1:8000
echo =========================================
pause
ENDLOCAL
