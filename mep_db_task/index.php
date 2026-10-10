<?php

require __DIR__ . '/vendor/autoload.php';

require_once 'DatabaseConnectionInterface.php';
require_once 'PdoConnection.php';
require_once 'ProductService.php';
require_once 'Box.php';

use DI\ContainerBuilder;
use Dotenv\Dotenv;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Psr\Log\LoggerInterface;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

$containerBuilder = new ContainerBuilder();
$containerBuilder->addDefinitions([
    
    LoggerInterface::class => function() {
        $logger = new Logger('app');
        $logger->pushHandler(new StreamHandler(__DIR__ . '/app.log', Logger::DEBUG));
        return $logger;
    },
    
    DatabaseConnectionInterface::class => function() {
        return new PdoConnection(
            $_ENV['DB_HOST'],
            $_ENV['DB_NAME'],
            $_ENV['DB_USER'],
            $_ENV['DB_PASS']
        );
    }
]);

$container = $containerBuilder->build();

$box = $container->get(Box::class);
$box->getCount();

$productService = $container->get(ProductService::class);

$page = isset($_GET['page']) && (int)$_GET['page'] > 0 ? (int)$_GET['page'] : 1;
$products = $productService->getAllProducts($page);

?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <title>PHP Mentored Education Program, Module 10: SQL, PDO</title>
</head>
<body class="pt-5">
    <div class="container">
        <div class="shadow-lg p-3 mb-5 bg-body rounded">
            <h1>PHP Mentored Education Program, Module 10: SQL, PDO</h1>
        </div>

        <div class="shadow-lg p-3 mb-5 bg-body rounded">
            <ul class="nav justify-content-center">
                <li class="nav-item">
                    <a class="nav-link active disabled" aria-current="page" href="index.php">List Page</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="add-product-page.php">Add new Product</a>
                </li>
            </ul>
        </div>

    <?php if (isset($_GET['error'])) echo '<div class="alert alert-danger">Validation failed!</div>'; ?>
    
        <div class="shadow-lg p-3 mb-5 bg-body rounded">
            <h2>Products</h2>
            <hr>
            <div id="products">
                <?php if (empty($products)): ?>
                    <div class="alert alert-info">No products found.</div>
                <?php else: ?>
                    <table class="table table-striped align-middle">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Category</th>
                                <th>Description</th>
                                <th>Created At</th>
                                <th>Updated At</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($products as $p): ?>
                                <tr>
                                    <td><?= htmlspecialchars($p['name']) ?></td>
                                    <td><?= htmlspecialchars($p['category']) ?></td>
                                    <td><?= htmlspecialchars($p['description']) ?></td>
                                    <td><?= htmlspecialchars($p['created_at']) ?></td>
                                    <td><?= $p['updated_at'] ? htmlspecialchars($p['updated_at']) : '<i>Never</i>' ?></td>
                                    <td>
                                        <a href="update-product-page.php?id=<?= $p['id'] ?>" class="btn btn-sm btn-primary">Update</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    
                    <!-- Pagination Controls -->
                    <div class="d-flex justify-content-between mt-4">
                        <?php if ($page > 1): ?>
                            <a href="?page=<?= $page - 1 ?>" class="btn btn-outline-primary">&laquo; Previous</a>
                        <?php else: ?>
                            <button class="btn btn-outline-secondary" disabled>&laquo; Previous</button>
                        <?php endif; ?>

                        <span class="align-self-center">Page <?= $page ?></span>
                        
                        <?php if (count($products) == 10): ?>
                            <a href="?page=<?= $page + 1 ?>" class="btn btn-outline-primary">Next &raquo;</a>
                        <?php else: ?>
                            <button class="btn btn-outline-secondary" disabled>Next &raquo;</button>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p"
            crossorigin="anonymous"></script>
</body>
</html>