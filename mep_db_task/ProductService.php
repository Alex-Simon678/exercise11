<?php

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
}