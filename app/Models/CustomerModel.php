<?php
declare(strict_types=1);

class CustomerModel
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }

    public function getAll(): array
    {
        $stmt = $this->db->query("SELECT * FROM customers ORDER BY id DESC");
        return $stmt->fetchAll();
    }

    public function getById(int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM customers WHERE id = :id LIMIT 1");
        $stmt->execute(['id' => $id]);
        $result = $stmt->fetch();

        return $result ?: null;
    }

    public function create(array $data): bool
    {
        $stmt = $this->db->prepare("
            INSERT INTO customers (name, phone, address)
            VALUES (:name, :phone, :address)
        ");

        return $stmt->execute([
            'name' => $data['name'],
            'phone' => $data['phone'],
            'address' => $data['address'],
        ]);
    }

    public function update(int $id, array $data): bool
    {
        $stmt = $this->db->prepare("
            UPDATE customers
            SET name = :name,
                phone = :phone,
                address = :address
            WHERE id = :id
        ");

        return $stmt->execute([
            'id' => $id,
            'name' => $data['name'],
            'phone' => $data['phone'],
            'address' => $data['address'],
        ]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM customers WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }
}