<?php
declare(strict_types=1);

class PaymentModel
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }

    public function getAll(): array
    {
        $sql = "SELECT 
                    p.*,
                    wo.wo_number,
                    wo.grand_total,
                    c.name AS customer_name
                FROM payments p
                INNER JOIN work_orders wo ON wo.id = p.work_order_id
                INNER JOIN customers c ON c.id = wo.customer_id
                ORDER BY p.id DESC";

        return $this->db->query($sql)->fetchAll();
    }

    public function getByWorkOrderId(int $workOrderId): array
    {
        $stmt = $this->db->prepare("
            SELECT *
            FROM payments
            WHERE work_order_id = :work_order_id
            ORDER BY id ASC
        ");
        $stmt->execute(['work_order_id' => $workOrderId]);

        return $stmt->fetchAll();
    }

    public function getTotalPaidByWorkOrderId(int $workOrderId): float
    {
        $stmt = $this->db->prepare("
            SELECT COALESCE(SUM(amount), 0) AS total
            FROM payments
            WHERE work_order_id = :work_order_id
        ");
        $stmt->execute(['work_order_id' => $workOrderId]);

        return (float) $stmt->fetch()['total'];
    }

    public function create(array $data): bool
    {
        $stmt = $this->db->prepare("
            INSERT INTO payments (
                work_order_id, payment_date, amount, payment_method, notes
            ) VALUES (
                :work_order_id, :payment_date, :amount, :payment_method, :notes
            )
        ");

        return $stmt->execute([
            'work_order_id' => $data['work_order_id'],
            'payment_date' => $data['payment_date'],
            'amount' => $data['amount'],
            'payment_method' => $data['payment_method'],
            'notes' => $data['notes'],
        ]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM payments WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }

    public function updateWorkOrderPaymentStatus(int $workOrderId): void
    {
        $stmt = $this->db->prepare("
            SELECT grand_total
            FROM work_orders
            WHERE id = :id
            LIMIT 1
        ");
        $stmt->execute(['id' => $workOrderId]);
        $workOrder = $stmt->fetch();

        if (!$workOrder) {
            return;
        }

        $grandTotal = (float) $workOrder['grand_total'];
        $totalPaid = $this->getTotalPaidByWorkOrderId($workOrderId);

        $paymentStatus = 'unpaid';
        if ($totalPaid > 0 && $totalPaid < $grandTotal) {
            $paymentStatus = 'dp';
        } elseif ($totalPaid >= $grandTotal && $grandTotal > 0) {
            $paymentStatus = 'paid';
        }

        $updateStmt = $this->db->prepare("
            UPDATE work_orders
            SET payment_status = :payment_status
            WHERE id = :id
        ");
        $updateStmt->execute([
            'payment_status' => $paymentStatus,
            'id' => $workOrderId,
        ]);
    }
}