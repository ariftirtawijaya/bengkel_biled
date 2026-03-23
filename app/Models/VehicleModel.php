<?php
declare(strict_types=1);

class VehicleModel
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }

    public function getAll(): array
    {
        $sql = "SELECT 
                    v.*,
                    c.name AS customer_name
                FROM vehicles v
                INNER JOIN customers c ON c.id = v.customer_id
                ORDER BY v.id DESC";

        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }

    public function getById(int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM vehicles WHERE id = :id LIMIT 1");
        $stmt->execute(['id' => $id]);
        $result = $stmt->fetch();

        return $result ?: null;
    }

    public function create(array $data): bool
    {
        $stmt = $this->db->prepare("
            INSERT INTO vehicles (
                customer_id, category, brand, model,
                year, plate_number, color, notes
            ) VALUES (
                :customer_id, :category, :brand, :model,
                :year, :plate_number, :color, :notes
            )
        ");

        return $stmt->execute([
            'customer_id' => $data['customer_id'],
            'category' => $data['category'],
            'brand' => $data['brand'],
            'model' => $data['model'],
            'year' => $data['year'],
            'plate_number' => $data['plate_number'],
            'color' => $data['color'],
            'notes' => $data['notes'],
        ]);
    }

    public function update(int $id, array $data): bool
    {
        $stmt = $this->db->prepare("
            UPDATE vehicles
            SET customer_id = :customer_id,
                category = :category,
                brand = :brand,
                model = :model,
                year = :year,
                plate_number = :plate_number,
                color = :color,
                notes = :notes
            WHERE id = :id
        ");

        return $stmt->execute([
            'id' => $id,
            'customer_id' => $data['customer_id'],
            'category' => $data['category'],
            'brand' => $data['brand'],
            'model' => $data['model'],
            'year' => $data['year'],
            'plate_number' => $data['plate_number'],
            'color' => $data['color'],
            'notes' => $data['notes'],
        ]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM vehicles WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }
}