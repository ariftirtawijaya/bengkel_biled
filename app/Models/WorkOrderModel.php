<?php
declare(strict_types=1);

class WorkOrderModel
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }

    public function getAll(): array
    {
        $sql = "SELECT 
                    wo.*,
                    c.name AS customer_name,
                    v.brand,
                    v.model,
                    v.plate_number,
                    s.name AS service_name
                FROM work_orders wo
                INNER JOIN customers c ON c.id = wo.customer_id
                INNER JOIN vehicles v ON v.id = wo.vehicle_id
                INNER JOIN services s ON s.id = wo.service_id
                ORDER BY wo.id DESC";

        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }

    public function getById(int $id): ?array
    {
        $sql = "SELECT 
                    wo.*,
                    c.name AS customer_name,
                    c.phone AS customer_phone,
                    c.address AS customer_address,
                    v.category AS vehicle_category,
                    v.brand,
                    v.model,
                    v.year,
                    v.plate_number,
                    v.color,
                    s.name AS service_name
                FROM work_orders wo
                INNER JOIN customers c ON c.id = wo.customer_id
                INNER JOIN vehicles v ON v.id = wo.vehicle_id
                INNER JOIN services s ON s.id = wo.service_id
                WHERE wo.id = :id
                LIMIT 1";

        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);
        $result = $stmt->fetch();

        return $result ?: null;
    }

    public function create(array $data): bool
    {
        $stmt = $this->db->prepare("
            INSERT INTO work_orders (
                wo_number, work_date, customer_id, vehicle_id, service_id,
                complaint, estimated_service_price, status, internal_notes
            ) VALUES (
                :wo_number, :work_date, :customer_id, :vehicle_id, :service_id,
                :complaint, :estimated_service_price, :status, :internal_notes
            )
        ");

        return $stmt->execute([
            'wo_number' => $data['wo_number'],
            'work_date' => $data['work_date'],
            'customer_id' => $data['customer_id'],
            'vehicle_id' => $data['vehicle_id'],
            'service_id' => $data['service_id'],
            'complaint' => $data['complaint'],
            'estimated_service_price' => $data['estimated_service_price'],
            'status' => $data['status'],
            'internal_notes' => $data['internal_notes'],
        ]);
    }

    public function update(int $id, array $data): bool
    {
        $stmt = $this->db->prepare("
            UPDATE work_orders
            SET work_date = :work_date,
                customer_id = :customer_id,
                vehicle_id = :vehicle_id,
                service_id = :service_id,
                complaint = :complaint,
                estimated_service_price = :estimated_service_price,
                status = :status,
                internal_notes = :internal_notes
            WHERE id = :id
        ");

        return $stmt->execute([
            'id' => $id,
            'work_date' => $data['work_date'],
            'customer_id' => $data['customer_id'],
            'vehicle_id' => $data['vehicle_id'],
            'service_id' => $data['service_id'],
            'complaint' => $data['complaint'],
            'estimated_service_price' => $data['estimated_service_price'],
            'status' => $data['status'],
            'internal_notes' => $data['internal_notes'],
        ]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM work_orders WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }

    public function generateWoNumber(): string
    {
        $datePart = date('Ymd');

        $stmt = $this->db->prepare("
            SELECT COUNT(*) AS total
            FROM work_orders
            WHERE DATE(created_at) = CURDATE()
        ");
        $stmt->execute();
        $countToday = (int) $stmt->fetch()['total'] + 1;

        return 'WO-' . $datePart . '-' . str_pad((string) $countToday, 3, '0', STR_PAD_LEFT);
    }

    public function getVehiclesByCustomerId(int $customerId): array
    {
        $stmt = $this->db->prepare("
            SELECT id, brand, model, plate_number
            FROM vehicles
            WHERE customer_id = :customer_id
            ORDER BY id DESC
        ");
        $stmt->execute(['customer_id' => $customerId]);

        return $stmt->fetchAll();
    }
}