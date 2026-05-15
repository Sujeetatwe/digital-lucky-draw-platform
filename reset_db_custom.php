<?php
try {
    // Connect to MySQL server without selecting the corrupted DB
    // Assuming default root/empty credentials which are standard for XAMPP/Laragon
    $pdo = new PDO('mysql:host=127.0.0.1;port=3306', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "Dropping database lucky_draw...\n";
    $pdo->exec("DROP DATABASE IF EXISTS lucky_draw");
    echo "Creating database lucky_draw...\n";
    $pdo->exec("CREATE DATABASE lucky_draw");
    echo "Database reset successfully.\n";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
    exit(1);
}
