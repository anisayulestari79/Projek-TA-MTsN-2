@echo off
echo ========================================
echo Setup Backend Laravel - Sistem Penilaian Poin
echo ========================================
echo.

echo [1/7] Installing Composer dependencies...
call composer install
if errorlevel 1 (
    echo Error installing dependencies!
    pause
    exit /b 1
)
echo.

echo [2/7] Installing Laravel Sanctum...
call composer require laravel/sanctum
if errorlevel 1 (
    echo Error installing Sanctum!
    pause
    exit /b 1
)
echo.

echo [3/7] Publishing Sanctum configuration...
call php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
echo.

echo [4/7] Copying .env file...
if not exist .env (
    copy .env.example .env
    echo .env file created!
) else (
    echo .env file already exists, skipping...
)
echo.

echo [5/7] Generating application key...
call php artisan key:generate
echo.

echo [6/7] Running migrations...
call php artisan migrate
if errorlevel 1 (
    echo Error running migrations! Please check your database configuration in .env
    pause
    exit /b 1
)
echo.

echo [7/7] Seeding database...
call php artisan db:seed
if errorlevel 1 (
    echo Error seeding database!
    pause
    exit /b 1
)
echo.

echo ========================================
echo Setup completed successfully!
echo ========================================
echo.
echo You can now start the server with:
echo   php artisan serve
echo.
pause

