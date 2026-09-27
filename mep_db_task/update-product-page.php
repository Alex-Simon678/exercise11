<?php
require_once 'Product.php';
require_once 'ProductRepository.php';

// 1. Get the ID from the URL (e.g., ?id=1)
$id = isset($_GET['id']) ? (int)$_GET['id'] : null;

// If there's no ID in the URL, redirect back to the list
if (!$id) {
    header('Location: index.php', true, 301);
    exit();
}

// 2. Connect to the database
$dsn = "mysql:host=db;dbname=mep_db_task;charset=utf8mb4";
try {
    $pdo = new PDO($dsn, 'root', 'root', [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
    $repository = new ProductRepository($pdo);
    
    // 3. Fetch the existing product data
    $product = $repository->get($id);
    
    // If the product doesn't exist in the database, redirect back
    if (!$product) {
        header('Location: index.php', true, 301);
        exit();
    }
} catch (\PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
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
                    <a class="nav-link active" aria-current="page" href="index.php">List Page</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link disabled" href="add-product-page.php">Add new Product</a>
                </li>
            </ul>
        </div>

        <div class="shadow-lg p-3 mb-5 bg-body rounded">
            <h2>Update Product</h2>
            <hr>
            <div id="products">
                <!-- Action changed to update-process.php -->
                <form id="product-form" method="POST" action="update-process.php">
                    <div class="mb-3">
                        <label for="productId" class="form-label">Product ID</label>
                        <!-- Readonly ID field -->
                        <input type="text" class="form-control" name="id" id="productId" value="<?= htmlspecialchars($product->id) ?>" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="productName" class="form-label">Product Name</label>
                        <!-- Pre-filling the name -->
                        <input type="text" class="form-control" name="name" id="productName" value="<?= htmlspecialchars($product->name) ?>">
                    </div>
                    <div class="mb-3">
                        <label for="productCategory" class="form-label">Product Category</label>
                        <!-- Pre-filling the category -->
                        <input type="text" class="form-control" name="category" id="productCategory" value="<?= htmlspecialchars($product->category) ?>">
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <!-- Pre-filling the description inside the textarea -->
                        <textarea class="form-control" id="description" rows="3" name="description"><?= htmlspecialchars($product->description) ?></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </form>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p"
            crossorigin="anonymous"></script>
</body>
</html>