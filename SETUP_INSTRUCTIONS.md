# Lucky Draw Registration System - Setup Instructions

## System Requirements
- PHP 8.1 or higher
- Composer
- MySQL 5.7+ or SQLite (default)
- Node.js & NPM (for assets)

## Installation Steps

### 1. Database Configuration

The application is configured to use **SQLite** by default (no MySQL setup needed).

If you want to use **MySQL** instead:
1. Create a MySQL database named `lucky_draw`
2. Open `.env` file
3. Update these lines:
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=lucky_draw
DB_USERNAME=root
DB_PASSWORD=your_password
```

### 2. Run Migrations

```bash
php artisan migrate
```

This will create all necessary tables:
- users (for admin authentication)
- participants (for lucky draw registrations)

### 3. Create Admin User

Run this command to create an admin user:

```bash
php artisan tinker
```

Then in the tinker console:

```php
\App\Models\User::create([
    'name' => 'Admin',
    'email' => 'admin@luckydraw.com',
    'password' => bcrypt('password123')
]);
exit
```

**Login Credentials:**
- Email: admin@luckydraw.com
- Password: password123

### 4. Start Development Server

```bash
php artisan serve
```

The application will be available at: http://localhost:8000

## Features

### Admin Portal (After Login)

1. **Dashboard**
   - Total registered participants
   - Remaining slots (out of 2000)
   - Voter ID statistics
   - Registration form

2. **Participant Registration**
   - Full Name
   - Mobile Number (unique)
   - Voter ID (Yes/No)
   - Permanent Address
   - Auto-generates unique 4-digit token

3. **Participant Management**
   - View all registered participants
   - Search by Token, Name, or Mobile
   - Filter by Voter ID status
   - View participant details
   - Soft delete participants
   - Restore deleted participants

4. **Export Functionality**
   - Export all participants to Excel (CSV format)
   - Includes all participant details

## Registration Rules

1. **Maximum Limit:** 2000 participants
2. **Unique Mobile Number:** Each mobile number can only register once
3. **Duplicate Check:** If mobile exists, shows existing token
4. **Token Generation:** Unique 4-digit random token (0000-9999)
5. **Token Display:** Token shown only on screen (no PDF/slip generation)

## Important Notes

- Only admin users can register participants
- No public registration form
- Tokens are displayed on screen only
- Admin must manually write/print tokens
- All registrations are tracked with timestamps
- Soft delete allows restoration of deleted participants

## Troubleshooting

### Database Connection Error
- Check `.env` file database credentials
- Ensure MySQL service is running (if using MySQL)
- For SQLite, ensure `database/database.sqlite` file exists

### Permission Issues
Run these commands:
```bash
php artisan cache:clear
php artisan config:clear
chmod -R 775 storage bootstrap/cache
```

### Assets Not Loading
```bash
npm install
npm run build
```

## Security

- Change default admin password after first login
- Update `APP_KEY` in `.env` (run `php artisan key:generate`)
- Set `APP_DEBUG=false` in production
- Use strong passwords for admin accounts

## Support

For any issues or questions, please contact the development team.
