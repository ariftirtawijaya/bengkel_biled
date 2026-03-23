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
            s.name AS service_name,
            COALESCE(SUM(p.amount), 0) AS paid_total
        FROM work_orders wo
        INNER JOIN customers c ON c.id = wo.customer_id
        INNER JOIN vehicles v ON v.id = wo.vehicle_id
        INNER JOIN services s ON s.id = wo.service_id
        LEFT JOIN payments p ON p.work_order_id = wo.id
        GROUP BY wo.id, c.name, v.brand, v.model, v.plate_number, s.name
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
            s.name AS service_name,
            COALESCE(SUM(p.amount), 0) AS paid_total
        FROM work_orders wo
        INNER JOIN customers c ON c.id = wo.customer_id
        INNER JOIN vehicles v ON v.id = wo.vehicle_id
        INNER JOIN services s ON s.id = wo.service_id
        LEFT JOIN payments p ON p.work_order_id = wo.id
        WHERE wo.id = :id
        GROUP BY wo.id, c.name, c.phone, c.address, v.category, v.brand, v.model, v.year, v.plate_number, v.color, s.name
        LIMIT 1";

        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);
        $result = $stmt->fetch();

        return $result ?: null;
    }

    public function getAddonsByWorkOrderId(int $workOrderId): array
    {
        $stmt = $this->db->prepare("
            SELECT *
            FROM work_order_addons
            WHERE work_order_id = :work_order_id
            ORDER BY id ASC
        ");
        $stmt->execute(['work_order_id' => $workOrderId]);

        return $stmt->fetchAll();
    }

    public function getProductsByWorkOrderId(int $workOrderId): array
    {
        $stmt = $this->db->prepare("
            SELECT *
            FROM work_order_products
            WHERE work_order_id = :work_order_id
            ORDER BY id ASC
        ");
        $stmt->execute(['work_order_id' => $workOrderId]);

        return $stmt->fetchAll();
    }

    public function create(array $data, array $addons = [], array $products = []): bool
    {
        $maxAttempts = 3;
        $attempt = 0;

        while ($attempt < $maxAttempts) {
            try {
                $this->db->beginTransaction();

                $stmt = $this->db->prepare("
                INSERT INTO work_orders (
                    wo_number, work_date, customer_id, vehicle_id, service_id,
                    complaint, estimated_service_price, addons_total, products_total, grand_total, status, internal_notes
                ) VALUES (
                    :wo_number, :work_date, :customer_id, :vehicle_id, :service_id,
                    :complaint, :estimated_service_price, :addons_total, :products_total, :grand_total, :status, :internal_notes
                )
            ");

                $stmt->execute([
                    'wo_number' => $data['wo_number'],
                    'work_date' => $data['work_date'],
                    'customer_id' => $data['customer_id'],
                    'vehicle_id' => $data['vehicle_id'],
                    'service_id' => $data['service_id'],
                    'complaint' => $data['complaint'],
                    'estimated_service_price' => $data['estimated_service_price'],
                    'addons_total' => $data['addons_total'],
                    'products_total' => $data['products_total'],
                    'grand_total' => $data['grand_total'],
                    'status' => $data['status'],
                    'internal_notes' => $data['internal_notes'],
                ]);

                $workOrderId = (int) $this->db->lastInsertId();

                $this->insertAddons($workOrderId, $addons);
                $this->insertProducts($workOrderId, $products);

                $this->db->commit();
                return true;
            } catch (PDOException $e) {
                $this->db->rollBack();

                $isDuplicateWo =
                    $e->getCode() === '23000' &&
                    str_contains($e->getMessage(), 'wo_number');

                if ($isDuplicateWo) {
                    $attempt++;
                    $data['wo_number'] = $this->generateWoNumber();
                    continue;
                }

                throw $e;
            } catch (Throwable $e) {
                $this->db->rollBack();
                throw $e;
            }
        }

        throw new RuntimeException('Gagal membuat nomor work order yang unik.');
    }

    public function update(int $id, array $data, array $addons = [], array $products = []): bool
    {
        try {
            $this->db->beginTransaction();

            $stmt = $this->db->prepare("
                UPDATE work_orders
                SET work_date = :work_date,
                    customer_id = :customer_id,
                    vehicle_id = :vehicle_id,
                    service_id = :service_id,
                    complaint = :complaint,
                    estimated_service_price = :estimated_service_price,
                    addons_total = :addons_total,
                    products_total = :products_total,
                    grand_total = :grand_total,
                    status = :status,
                    internal_notes = :internal_notes
                WHERE id = :id
            ");

            $stmt->execute([
                'id' => $id,
                'work_date' => $data['work_date'],
                'customer_id' => $data['customer_id'],
                'vehicle_id' => $data['vehicle_id'],
                'service_id' => $data['service_id'],
                'complaint' => $data['complaint'],
                'estimated_service_price' => $data['estimated_service_price'],
                'addons_total' => $data['addons_total'],
                'products_total' => $data['products_total'],
                'grand_total' => $data['grand_total'],
                'status' => $data['status'],
                'internal_notes' => $data['internal_notes'],
            ]);

            $deleteAddonStmt = $this->db->prepare("DELETE FROM work_order_addons WHERE work_order_id = :work_order_id");
            $deleteAddonStmt->execute(['work_order_id' => $id]);

            $deleteProductStmt = $this->db->prepare("DELETE FROM work_order_products WHERE work_order_id = :work_order_id");
            $deleteProductStmt->execute(['work_order_id' => $id]);

            $this->insertAddons($id, $addons);
            $this->insertProducts($id, $products);

            $this->db->commit();
            return true;
        } catch (Throwable $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    private function insertAddons(int $workOrderId, array $addons): void
    {
        if (empty($addons)) {
            return;
        }

        $stmt = $this->db->prepare("
            INSERT INTO work_order_addons (
                work_order_id, addon_id, addon_name, price, qty, subtotal, notes
            ) VALUES (
                :work_order_id, :addon_id, :addon_name, :price, :qty, :subtotal, :notes
            )
        ");

        foreach ($addons as $addon) {
            $stmt->execute([
                'work_order_id' => $workOrderId,
                'addon_id' => $addon['addon_id'],
                'addon_name' => $addon['addon_name'],
                'price' => $addon['price'],
                'qty' => $addon['qty'],
                'subtotal' => $addon['subtotal'],
                'notes' => $addon['notes'],
            ]);
        }
    }

    private function insertProducts(int $workOrderId, array $products): void
    {
        if (empty($products)) {
            return;
        }

        $stmt = $this->db->prepare("
            INSERT INTO work_order_products (
                work_order_id, product_id, product_code, product_name, price, qty, subtotal, notes
            ) VALUES (
                :work_order_id, :product_id, :product_code, :product_name, :price, :qty, :subtotal, :notes
            )
        ");

        foreach ($products as $product) {
            $stmt->execute([
                'work_order_id' => $workOrderId,
                'product_id' => $product['product_id'],
                'product_code' => $product['product_code'],
                'product_name' => $product['product_name'],
                'price' => $product['price'],
                'qty' => $product['qty'],
                'subtotal' => $product['subtotal'],
                'notes' => $product['notes'],
            ]);
        }
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM work_orders WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }

    public function generateWoNumber(): string
    {
        $datePart = date('Ymd');
        $prefix = 'WO-' . $datePart . '-';

        $stmt = $this->db->prepare("
        SELECT wo_number
        FROM work_orders
        WHERE wo_number LIKE :prefix
        ORDER BY wo_number DESC
        LIMIT 1
    ");
        $stmt->execute([
            'prefix' => $prefix . '%'
        ]);

        $lastWoNumber = $stmt->fetchColumn();

        if ($lastWoNumber) {
            $lastSequence = (int) substr($lastWoNumber, -3);
            $nextSequence = $lastSequence + 1;
        } else {
            $nextSequence = 1;
        }

        return $prefix . str_pad((string) $nextSequence, 3, '0', STR_PAD_LEFT);
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