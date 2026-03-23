<?php
declare(strict_types=1);

class DashboardModel
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }

    public function getSummary(): array
    {
        $produk = (int) $this->db->query("SELECT COUNT(*) AS total FROM products")->fetch()['total'];
        $customer = (int) $this->db->query("SELECT COUNT(*) AS total FROM customers")->fetch()['total'];
        $workOrder = (int) $this->db->query("SELECT COUNT(*) AS total FROM work_orders")->fetch()['total'];
        $omzet = (float) $this->db->query("SELECT COALESCE(SUM(total_amount), 0) AS total FROM work_orders WHERE status != 'cancelled'")->fetch()['total'];

        return [
            'produk' => $produk,
            'customer' => $customer,
            'work_order' => $workOrder,
            'omzet' => $omzet,
        ];
    }
}