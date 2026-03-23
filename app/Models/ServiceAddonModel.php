<?php
declare(strict_types=1);

class ServiceAddonModel
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }

    public function getAll(): array
    {
        $stmt = $this->db->query("SELECT * FROM service_addons ORDER BY id DESC");
        return $stmt->fetchAll();
    }

    public function getById(int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM service_addons WHERE id = :id LIMIT 1");
        $stmt->execute(['id' => $id]);
        $result = $stmt->fetch();

        return $result ?: null;
    }

    public function create(array $data): bool
    {
        $stmt = $this->db->prepare("
            INSERT INTO service_addons (
                name, addon_category, vehicle_category,
                price, estimated_minutes, description, is_active
            ) VALUES (
                :name, :addon_category, :vehicle_category,
                :price, :estimated_minutes, :description, :is_active
            )
        ");

        return $stmt->execute([
            'name' => $data['name'],
            'addon_category' => $data['addon_category'],
            'vehicle_category' => $data['vehicle_category'],
            'price' => $data['price'],
            'estimated_minutes' => $data['estimated_minutes'],
            'description' => $data['description'],
            'is_active' => $data['is_active'],
        ]);
    }

    public function update(int $id, array $data): bool
    {
        $stmt = $this->db->prepare("
            UPDATE service_addons
            SET name = :name,
                addon_category = :addon_category,
                vehicle_category = :vehicle_category,
                price = :price,
                estimated_minutes = :estimated_minutes,
                description = :description,
                is_active = :is_active
            WHERE id = :id
        ");

        return $stmt->execute([
            'id' => $id,
            'name' => $data['name'],
            'addon_category' => $data['addon_category'],
            'vehicle_category' => $data['vehicle_category'],
            'price' => $data['price'],
            'estimated_minutes' => $data['estimated_minutes'],
            'description' => $data['description'],
            'is_active' => $data['is_active'],
        ]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM service_addons WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }
}