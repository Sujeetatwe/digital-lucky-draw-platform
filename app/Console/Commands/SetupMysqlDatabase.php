<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;

class SetupMysqlDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'setup:mysql';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatically setup MySQL database for Lucky Draw system';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🚀 Lucky Draw - MySQL Database Setup');
        $this->newLine();

        // Get database credentials
        $dbName = $this->ask('Database name', 'lucky_draw');
        $dbHost = $this->ask('Database host', '127.0.0.1');
        $dbPort = $this->ask('Database port', '3306');
        $dbUsername = $this->ask('Database username', 'root');
        $dbPassword = $this->secret('Database password (press Enter if none)');

        $this->info('📝 Creating database...');

        try {
            // Connect to MySQL without database
            $pdo = new \PDO(
                "mysql:host={$dbHost};port={$dbPort}",
                $dbUsername,
                $dbPassword ?: null
            );
            $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

            // Create database
            $pdo->exec("CREATE DATABASE IF NOT EXISTS `{$dbName}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
            $this->info("✅ Database '{$dbName}' created successfully!");

        } catch (\PDOException $e) {
            $this->error('❌ Failed to create database: ' . $e->getMessage());
            return 1;
        }

        // Update .env file
        $this->info('📝 Updating .env file...');
        $this->updateEnvFile($dbName, $dbHost, $dbPort, $dbUsername, $dbPassword);
        $this->info('✅ .env file updated!');

        // Clear config cache
        $this->info('🧹 Clearing configuration cache...');
        Artisan::call('config:clear');
        Artisan::call('cache:clear');
        $this->info('✅ Cache cleared!');

        // Run migrations
        $this->info('📊 Running database migrations...');
        Artisan::call('migrate:fresh', [], $this->output);
        $this->info('✅ Migrations completed!');

        // Seed admin user
        $this->info('👤 Creating admin user...');
        Artisan::call('db:seed', ['--class' => 'AdminUserSeeder'], $this->output);
        $this->info('✅ Admin user created!');

        $this->newLine();
        $this->info('🎉 Setup completed successfully!');
        $this->newLine();
        $this->info('Login credentials:');
        $this->line('  Email: admin@luckydraw.com');
        $this->line('  Password: password123');
        $this->newLine();
        $this->warn('⚠️  Please restart your development server (php artisan serve)');

        return 0;
    }

    /**
     * Update the .env file with database credentials
     */
    private function updateEnvFile($dbName, $dbHost, $dbPort, $dbUsername, $dbPassword)
    {
        $envPath = base_path('.env');

        if (!file_exists($envPath)) {
            copy(base_path('.env.example'), $envPath);
        }

        $envContent = file_get_contents($envPath);

        // Update database connection
        $envContent = preg_replace('/DB_CONNECTION=.*/', 'DB_CONNECTION=mysql', $envContent);
        $envContent = preg_replace('/DB_HOST=.*/', "DB_HOST={$dbHost}", $envContent);
        $envContent = preg_replace('/DB_PORT=.*/', "DB_PORT={$dbPort}", $envContent);
        $envContent = preg_replace('/DB_DATABASE=.*/', "DB_DATABASE={$dbName}", $envContent);
        $envContent = preg_replace('/DB_USERNAME=.*/', "DB_USERNAME={$dbUsername}", $envContent);
        $envContent = preg_replace('/DB_PASSWORD=.*/', "DB_PASSWORD={$dbPassword}", $envContent);

        // If DB_ entries don't exist, add them
        if (!str_contains($envContent, 'DB_CONNECTION=')) {
            $envContent .= "\nDB_CONNECTION=mysql";
            $envContent .= "\nDB_HOST={$dbHost}";
            $envContent .= "\nDB_PORT={$dbPort}";
            $envContent .= "\nDB_DATABASE={$dbName}";
            $envContent .= "\nDB_USERNAME={$dbUsername}";
            $envContent .= "\nDB_PASSWORD={$dbPassword}";
        }

        file_put_contents($envPath, $envContent);
    }
}
