Write-Host "========================================" -ForegroundColor Cyan
Write-Host "Setup Backend Laravel - Sistem Penilaian Poin" -ForegroundColor Cyan
Write-Host "========================================" -ForegroundColor Cyan
Write-Host ""

Write-Host "[1/7] Installing Composer dependencies..." -ForegroundColor Yellow
composer install
if ($LASTEXITCODE -ne 0) {
    Write-Host "Error installing dependencies!" -ForegroundColor Red
    exit 1
}
Write-Host ""

Write-Host "[2/7] Installing Laravel Sanctum..." -ForegroundColor Yellow
composer require laravel/sanctum
if ($LASTEXITCODE -ne 0) {
    Write-Host "Error installing Sanctum!" -ForegroundColor Red
    exit 1
}
Write-Host ""

Write-Host "[3/7] Publishing Sanctum configuration..." -ForegroundColor Yellow
php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
Write-Host ""

Write-Host "[4/7] Copying .env file..." -ForegroundColor Yellow
if (-not (Test-Path .env)) {
    Copy-Item .env.example .env
    Write-Host ".env file created!" -ForegroundColor Green
} else {
    Write-Host ".env file already exists, skipping..." -ForegroundColor Yellow
}
Write-Host ""

Write-Host "[5/7] Generating application key..." -ForegroundColor Yellow
php artisan key:generate
Write-Host ""

Write-Host "[6/7] Running migrations..." -ForegroundColor Yellow
php artisan migrate
if ($LASTEXITCODE -ne 0) {
    Write-Host "Error running migrations! Please check your database configuration in .env" -ForegroundColor Red
    Write-Host "Make sure to set:" -ForegroundColor Yellow
    Write-Host "  DB_DATABASE=penilaian_poin" -ForegroundColor Yellow
    Write-Host "  DB_USERNAME=root" -ForegroundColor Yellow
    Write-Host "  DB_PASSWORD=your_password" -ForegroundColor Yellow
    exit 1
}
Write-Host ""

Write-Host "[7/7] Seeding database..." -ForegroundColor Yellow
php artisan db:seed
if ($LASTEXITCODE -ne 0) {
    Write-Host "Error seeding database!" -ForegroundColor Red
    exit 1
}
Write-Host ""

Write-Host "========================================" -ForegroundColor Green
Write-Host "Setup completed successfully!" -ForegroundColor Green
Write-Host "========================================" -ForegroundColor Green
Write-Host ""
Write-Host "You can now start the server with:" -ForegroundColor Cyan
Write-Host "  php artisan serve" -ForegroundColor White
Write-Host ""

