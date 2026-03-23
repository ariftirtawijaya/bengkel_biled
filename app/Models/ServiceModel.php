<?php
declare(strict_types=1);

class ServiceModel
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }

    public function getAll(): array
    {
        $stmt = $this->db->query("SELECT * FROM services ORDER BY id DESC");
        return $stmt->fetchAll();
    }

    public function getById(int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM services WHERE id = :id LIMIT 1");
        $stmt->execute(['id' => $id]);
        $result = $stmt->fetch();

        return $result ?: null;
    }

    public function create(array $data): bool
    {
        $stmt = $this->db->prepare("
            INSERT INTO services (
                name, service_type, vehicle_category,
                base_price, estimated_minutes, description, is_active
            ) VALUES (
                :name, :service_type, :vehicle_category,
                :base_price, :estimated_minutes, :description, :is_active
            )
        ");

        return $stmt->execute([
            'name' => $data['name'],
            'service_type' => $data['service_type'],
            'vehicle_category' => $data['vehicle_category'],
            'base_price' => $data['base_price'],
            'estimated_minutes' => $data['estimated_minutes'],
            'description' => $data['description'],
            'is_active' => $data['is_active'],
        ]);
    }

    public function update(int $id, array $data): bool
    {
        $stmt = $this->db->prepare("
            UPDATE services
            SET name = :name,
                service_type = :service_type,
                vehicle_category = :vehicle_category,
                base_price = :base_price,
                estimated_minutes = :estimated_minutes,
                description = :description,
                is_active = :is_active
            WHERE id = :id
        ");

        return $stmt->execute([
            'id' => $id,
            'name' => $data['name'],
            'service_type' => $data['service_type'],
            'vehicle_category' => $data['vehicle_category'],
            'base_price' => $data['base_price'],
            'estimated_minutes' => $data['estimated_minutes'],
            'description' => $data['description'],
            'is_active' => $data['is_active'],
        ]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM services WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }
}