<?php

$dsn = "mysql:host=db;dbname=mep_db_task;charset=utf8mb4";
$user = "root";
$pass = "root";

try {
    $pdo = new PDO($dsn, $user, $pass, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
    
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS products (
            id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(64) NOT NULL,
            category VARCHAR(64) NOT NULL,
            description TEXT,
            created_at DATETIME NOT NULL DEFAULT NOW(),
            updated_at DATETIME NULL
        )
    ");

    $pdo->exec("
        INSERT INTO products (name, category, description) VALUES 
        ('Laptop', 'Electronics', 'A high performance laptop'),
        ('Desk Chair', 'Furniture', 'Ergonomic office chair'),
        ('Coffee Mug', 'Kitchen', 'Ceramic mug for your morning brew')
    ");

    echo "Database initialized and sample data inserted successfully!";
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}