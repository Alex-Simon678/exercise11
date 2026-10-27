<?php

require __DIR__ . '/vendor/autoload.php';
require_once 'ProductForm.php';
require_once 'Product.php';
require_once 'ProductService.php';
require_once 'DatabaseConnectionInterface.php';
require_once 'PdoConnection.php';

use DI\ContainerBuilder;
use Dotenv\Dotenv;

if (!empty($_POST)) {
    $form = new ProductForm();
    
    $form->id = isset($_POST['id']) ? (int)$_POST['id'] : null; 
    $form->name = $_POST['name'] ?? '';
    $form->category = $_POST['category'] ?? '';
    $form->description = $_POST['description'] ?? '';

    if ($form->validate() && $form->id !== null) {
        $product = new Product();
        $product->id = $form->id;
        $product->name = $form->name;
        $product->category = $form->category;
        $product->description = $form->description;

        $dotenv = Dotenv::createImmutable(__DIR__);
        $dotenv->load();

        $containerBuilder = new ContainerBuilder();
        $containerBuilder->addDefinitions([
            DatabaseConnectionInterface::class => function() {
                return new PdoConnection(
                    $_ENV['DB_HOST'],
                    $_ENV['DB_NAME'],
                    $_ENV['DB_USER'],
                    $_ENV['DB_PASS']
                );
            },
            PDO::class => function(\Psr\Container\ContainerInterface $c) {
                return $c->get(DatabaseConnectionInterface::class)->getConnection();
            }
        ]);
        $container = $containerBuilder->build();

        try {
            $productService = $container->get(ProductService::class);
            $productService->update($product);
        } catch (\Exception $e) {
            die("Database connection/update failed: " . $e->getMessage());
        }
    } else {
        header('Location: index.php?error=validation_failed', true, 301);
        exit();
    }
}

header('Location: index.php', true, 301);
exit();