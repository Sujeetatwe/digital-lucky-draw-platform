# Switch from SQLite to MySQL Database

## Step 1: Create MySQL Database

1. Open **phpMyAdmin** in your browser (usually http://localhost/phpmyadmin)
2. Click on **"New"** in the left sidebar
3. Create a new database named: `lucky_draw`
4. Set Collation to: `utf8mb4_unicode_ci`
5. Click **"Create"**

## Step 2: Update .env File

Open the `.env` file in your project root and change these lines:

**FROM (SQLite):**
```
DB_CONNECTION=sqlite
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=laravel
# DB_USERNAME=root
# DB_PASSWORD=
```

**TO (MySQL):**
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=lucky_draw
DB_USERNAME=root
DB_PASSWORD=
```

**Note:** If your MySQL has a password, add it to `DB_PASSWORD=your_password`

## Step 3: Clear Configuration Cache

Run these commands in your terminal:

```bash
php artisan config:clear
php artisan cache:clear
```

## Step 4: Run Migrations

This will create all tables in your MySQL database:

```bash
php artisan migrate:fresh
```

## Step 5: Create Admin User

Run the seeder to create the admin account:

```bash
php artisan db:seed --class=AdminUserSeeder
```

## Step 6: Restart Server

Stop the current server (Ctrl+C) and restart:

```bash
php artisan serve
```

## Login Credentials

After setup, login with:
- **Email:** admin@luckydraw.com
- **Password:** password123

## Troubleshooting

### Error: "SQLSTATE[HY000] [2002] No connection could be made"
- Make sure XAMPP/WAMP MySQL service is running
- Check if MySQL port is 3306 (default)

### Error: "Access denied for user 'root'@'localhost'"
- Check your MySQL username and password in .env
- Default XAMPP/WAMP password is usually empty

### Error: "Unknown database 'lucky_draw'"
- Make sure you created the database in phpMyAdmin
- Database name must match exactly (case-sensitive on Linux)

## Verify Connection

To test if MySQL connection is working:

```bash
php artisan tinker
```

Then type:
```php
DB::connection()->getPdo();
exit
```

If no error appears, connection is successful!
