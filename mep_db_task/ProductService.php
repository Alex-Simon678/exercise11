<?php

require_once __DIR__ . '/Product.php';

class ProductService {
    private PDO $db;

    public function __construct(DatabaseConnectionInterface $connection) {
        $this->db = $connection->getConnection();
    }

    public function getAllProducts(int $page = 1): array {
        $limit = 10;
        $offset = ($page - 1) * $limit;
        
        $stmt = $this->db->prepare("SELECT * FROM products LIMIT :limit OFFSET :offset");
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }

    public function get(int $id): ?Product {
        $stmt = $this->db->prepare("SELECT id, name, category, description FROM products WHERE id = :id");
        $stmt->execute([':id' => $id]);

        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$data) {
            return null;
        }

        $product = new Product();
        $product->id = (int)$data['id'];
        $product->name = $data['name'];
        $product->category = $data['category'];
        $product->description = $data['description'];

        return $product;
    }

    public function save(Product $product): void {
        $stmt = $this->db->prepare(
            "INSERT INTO products (name, category, description, created_at) VALUES (:name, :category, :description, NOW())"
        );
        $stmt->execute([
            ':name' => $product->name,
            ':category' => $product->category,
            ':description' => $product->description,
        ]);
    }

    public function update(Product $product): void {
        $stmt = $this->db->prepare(
            "UPDATE products SET name = :name, category = :category, description = :description, updated_at = NOW() WHERE id = :id"
        );
        $stmt->execute([
            ':id' => $product->id,
            ':name' => $product->name,
            ':category' => $product->category,
            ':description' => $product->description,
        ]);
    }
}
