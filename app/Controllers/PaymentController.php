<?php
declare(strict_types=1);

class PaymentController extends Controller
{
    private PaymentModel $paymentModel;
    private WorkOrderModel $workOrderModel;

    public function __construct()
    {
        $this->paymentModel = new PaymentModel();
        $this->workOrderModel = new WorkOrderModel();
    }

    public function index(): void
    {
        $data = [
            'title' => 'Pembayaran',
            'payments' => $this->paymentModel->getAll(),
        ];

        $this->view('payments/index', $data);
    }

    public function workorder(string $id): void
    {
        $workOrder = $this->workOrderModel->getById((int) $id);

        if (!$workOrder) {
            $_SESSION['error'] = 'Work order tidak ditemukan.';
            header('Location: ' . BASE_URL . 'workorder');
            exit;
        }

        $payments = $this->paymentModel->getByWorkOrderId((int) $id);
        $paidTotal = $this->paymentModel->getTotalPaidByWorkOrderId((int) $id);
        $remaining = max(0, (float) $workOrder['grand_total'] - $paidTotal);

        $data = [
            'title' => 'Pembayaran Work Order',
            'workOrder' => $workOrder,
            'payments' => $payments,
            'paidTotal' => $paidTotal,
            'remaining' => $remaining,
            'errors' => $_SESSION['errors'] ?? [],
            'old' => $_SESSION['old'] ?? [],
        ];

        unset($_SESSION['errors'], $_SESSION['old']);

        $this->view('payments/work_order', $data);
    }

    public function store(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . 'payment');
            exit;
        }

        $workOrderId = (int) ($_POST['work_order_id'] ?? 0);
        $workOrder = $this->workOrderModel->getById($workOrderId);

        if (!$workOrder) {
            $_SESSION['error'] = 'Work order tidak ditemukan.';
            header('Location: ' . BASE_URL . 'workorder');
            exit;
        }

        $formData = [
            'work_order_id' => $workOrderId,
            'payment_date' => trim($_POST['payment_date'] ?? ''),
            'amount' => (float) ($_POST['amount'] ?? 0),
            'payment_method' => trim($_POST['payment_method'] ?? 'cash'),
            'notes' => trim($_POST['notes'] ?? ''),
        ];

        $errors = [];

        if ($formData['payment_date'] === '') {
            $errors['payment_date'] = 'Tanggal pembayaran wajib diisi.';
        }

        if ($formData['amount'] <= 0) {
            $errors['amount'] = 'Nominal pembayaran harus lebih dari 0.';
        }

        if (!in_array($formData['payment_method'], ['cash', 'transfer', 'qris', 'debit', 'credit', 'other'], true)) {
            $errors['payment_method'] = 'Metode pembayaran tidak valid.';
        }

        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['old'] = $formData;
            $_SESSION['error'] = 'Gagal menyimpan pembayaran. Periksa kembali input.';
            header('Location: ' . BASE_URL . 'payment/workorder/' . $workOrderId);
            exit;
        }

        $this->paymentModel->create($formData);
        $this->paymentModel->updateWorkOrderPaymentStatus($workOrderId);

        $_SESSION['success'] = 'Pembayaran berhasil ditambahkan.';
        header('Location: ' . BASE_URL . 'payment/workorder/' . $workOrderId);
        exit;
    }

    public function delete(string $id): void
    {
        $payments = $this->paymentModel->getAll();
        $payment = null;

        foreach ($payments as $row) {
            if ((int) $row['id'] === (int) $id) {
                $payment = $row;
                break;
            }
        }

        if (!$payment) {
            $_SESSION['error'] = 'Pembayaran tidak ditemukan.';
            header('Location: ' . BASE_URL . 'payment');
            exit;
        }

        $workOrderId = (int) $payment['work_order_id'];

        $this->paymentModel->delete((int) $id);
        $this->paymentModel->updateWorkOrderPaymentStatus($workOrderId);

        $_SESSION['success'] = 'Pembayaran berhasil dihapus.';
        header('Location: ' . BASE_URL . 'payment/workorder/' . $workOrderId);
        exit;
    }
}