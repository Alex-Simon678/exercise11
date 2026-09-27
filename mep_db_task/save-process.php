<?php

require __DIR__ . '/vendor/autoload.php';
require_once 'ProductForm.php';
require_once 'Product.php';
require_once 'ProductRepository.php';

if (!empty($_POST)) {
    $form = new ProductForm();
    $form->name = $_POST['name'] ?? '';
    $form->category = $_POST['category'] ?? '';
    $form->description = $_POST['description'] ?? '';

    if ($form->validate()) {
        $product = new Product();
        $product->name = $form->name;
        $product->category = $form->category;
        $product->description = $form->description;

        $dsn = "mysql:host=db;dbname=mep_db_task;charset=utf8mb4";
        try {
            $pdo = new PDO($dsn, 'root', 'root', [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
            ]);
            $repository = new ProductRepository($pdo);
            $repository->save($product);
        } catch (\PDOException $e) {
            die("Database connection failed: " . $e->getMessage());
        }
    } else {
        header('Location: index.php?error=validation_failed', true, 301);
        exit();
    }
}

header('Location: index.php', true, 301);
exit();