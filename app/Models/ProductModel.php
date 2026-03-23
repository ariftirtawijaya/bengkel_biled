<?php
declare(strict_types=1);

class ProductModel
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }

    public function getAll(): array
    {
        $stmt = $this->db->query("SELECT * FROM products ORDER BY id DESC");
        return $stmt->fetchAll();
    }

    public function getById(int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM products WHERE id = :id LIMIT 1");
        $stmt->execute(['id' => $id]);
        $result = $stmt->fetch();

        return $result ?: null;
    }

    public function getByCode(string $code): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM products WHERE code = :code LIMIT 1");
        $stmt->execute(['code' => $code]);
        $result = $stmt->fetch();

        return $result ?: null;
    }

    public function create(array $data): bool
    {
        $sql = "INSERT INTO products (
                    code, name, category, unit,
                    purchase_price, margin_percent, selling_price,
                    stock, min_stock, is_active
                ) VALUES (
                    :code, :name, :category, :unit,
                    :purchase_price, :margin_percent, :selling_price,
                    :stock, :min_stock, :is_active
                )";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            'code' => $data['code'],
            'name' => $data['name'],
            'category' => $data['category'],
            'unit' => $data['unit'],
            'purchase_price' => $data['purchase_price'],
            'margin_percent' => $data['margin_percent'],
            'selling_price' => $data['selling_price'],
            'stock' => $data['stock'],
            'min_stock' => $data['min_stock'],
            'is_active' => $data['is_active'],
        ]);
    }

    public function update(int $id, array $data): bool
    {
        $sql = "UPDATE products SET
                    code = :code,
                    name = :name,
                    category = :category,
                    unit = :unit,
                    purchase_price = :purchase_price,
                    margin_percent = :margin_percent,
                    selling_price = :selling_price,
                    stock = :stock,
                    min_stock = :min_stock,
                    is_active = :is_active
                WHERE id = :id";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            'id' => $id,
            'code' => $data['code'],
            'name' => $data['name'],
            'category' => $data['category'],
            'unit' => $data['unit'],
            'purchase_price' => $data['purchase_price'],
            'margin_percent' => $data['margin_percent'],
            'selling_price' => $data['selling_price'],
            'stock' => $data['stock'],
            'min_stock' => $data['min_stock'],
            'is_active' => $data['is_active'],
        ]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM products WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }

    public function generateNextCode(): string
    {
        $stmt = $this->db->query("SELECT id FROM products ORDER BY id DESC LIMIT 1");
        $last = $stmt->fetch();

        $nextNumber = $last ? ((int) $last['id'] + 1) : 1;

        return 'PRD-' . str_pad((string) $nextNumber, 4, '0', STR_PAD_LEFT);
    }
}