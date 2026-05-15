@echo off
echo ========================================
echo Lucky Draw - MySQL Setup Script
echo ========================================
echo.

echo Step 1: Clearing configuration cache...
php artisan config:clear
php artisan cache:clear
echo Done!
echo.

echo Step 2: Running migrations...
echo This will create all tables in your MySQL database.
php artisan migrate:fresh
echo Done!
echo.

echo Step 3: Creating admin user...
php artisan db:seed --class=AdminUserSeeder
echo Done!
echo.

echo ========================================
echo Setup Complete!
echo ========================================
echo.
echo You can now login with:
echo Email: admin@luckydraw.com
echo Password: password123
echo.
echo Press any key to exit...
pause > nul
