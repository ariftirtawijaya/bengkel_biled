<?php
declare(strict_types=1);

class WorkOrderController extends Controller
{
    private WorkOrderModel $workOrderModel;
    private CustomerModel $customerModel;
    private VehicleModel $vehicleModel;
    private ServiceModel $serviceModel;

    public function __construct()
    {
        $this->workOrderModel = new WorkOrderModel();
        $this->customerModel = new CustomerModel();
        $this->vehicleModel = new VehicleModel();
        $this->serviceModel = new ServiceModel();
    }

    public function index(): void
    {
        $data = [
            'title' => 'Work Order',
            'workOrders' => $this->workOrderModel->getAll(),
        ];

        $this->view('work_orders/index', $data);
    }

    public function create(): void
    {
        $data = [
            'title' => 'Tambah Work Order',
            'wo_number' => $this->workOrderModel->generateWoNumber(),
            'customers' => $this->customerModel->getAll(),
            'vehicles' => $this->vehicleModel->getAll(),
            'services' => $this->serviceModel->getAll(),
            'old' => $_SESSION['old'] ?? [],
            'errors' => $_SESSION['errors'] ?? [],
        ];

        unset($_SESSION['old'], $_SESSION['errors']);

        $this->view('work_orders/create', $data);
    }

    public function store(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . 'workorder');
            exit;
        }

        $formData = [
            'wo_number' => trim($_POST['wo_number'] ?? ''),
            'work_date' => trim($_POST['work_date'] ?? ''),
            'customer_id' => (int) ($_POST['customer_id'] ?? 0),
            'vehicle_id' => (int) ($_POST['vehicle_id'] ?? 0),
            'service_id' => (int) ($_POST['service_id'] ?? 0),
            'complaint' => trim($_POST['complaint'] ?? ''),
            'estimated_service_price' => (float) ($_POST['estimated_service_price'] ?? 0),
            'status' => trim($_POST['status'] ?? 'pending'),
            'internal_notes' => trim($_POST['internal_notes'] ?? ''),
        ];

        if ($formData['wo_number'] === '') {
            $formData['wo_number'] = $this->workOrderModel->generateWoNumber();
        }

        $errors = $this->validateWorkOrder($formData);

        if ($formData['customer_id'] <= 0 || !$this->customerModel->getById($formData['customer_id'])) {
            $errors['customer_id'] = 'Customer wajib dipilih.';
        }

        if ($formData['vehicle_id'] <= 0 || !$this->vehicleModel->getById($formData['vehicle_id'])) {
            $errors['vehicle_id'] = 'Kendaraan wajib dipilih.';
        }

        if ($formData['service_id'] <= 0 || !$this->serviceModel->getById($formData['service_id'])) {
            $errors['service_id'] = 'Jasa wajib dipilih.';
        }

        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['old'] = $formData;
            $_SESSION['error'] = 'Gagal menyimpan work order. Periksa kembali input.';
            header('Location: ' . BASE_URL . 'workorder/create');
            exit;
        }

        $this->workOrderModel->create($formData);

        $_SESSION['success'] = 'Work order berhasil ditambahkan.';
        header('Location: ' . BASE_URL . 'workorder');
        exit;
    }

    public function edit(string $id): void
    {
        $workOrder = $this->workOrderModel->getById((int) $id);

        if (!$workOrder) {
            $_SESSION['error'] = 'Work order tidak ditemukan.';
            header('Location: ' . BASE_URL . 'workorder');
            exit;
        }

        $data = [
            'title' => 'Edit Work Order',
            'workOrder' => $workOrder,
            'customers' => $this->customerModel->getAll(),
            'vehicles' => $this->vehicleModel->getAll(),
            'services' => $this->serviceModel->getAll(),
            'errors' => $_SESSION['errors'] ?? [],
        ];

        unset($_SESSION['errors']);

        $this->view('work_orders/edit', $data);
    }

    public function update(string $id): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . 'workorder');
            exit;
        }

        $workOrder = $this->workOrderModel->getById((int) $id);

        if (!$workOrder) {
            $_SESSION['error'] = 'Work order tidak ditemukan.';
            header('Location: ' . BASE_URL . 'workorder');
            exit;
        }

        $formData = [
            'work_date' => trim($_POST['work_date'] ?? ''),
            'customer_id' => (int) ($_POST['customer_id'] ?? 0),
            'vehicle_id' => (int) ($_POST['vehicle_id'] ?? 0),
            'service_id' => (int) ($_POST['service_id'] ?? 0),
            'complaint' => trim($_POST['complaint'] ?? ''),
            'estimated_service_price' => (float) ($_POST['estimated_service_price'] ?? 0),
            'status' => trim($_POST['status'] ?? 'pending'),
            'internal_notes' => trim($_POST['internal_notes'] ?? ''),
        ];

        $errors = $this->validateWorkOrder($formData);

        if ($formData['customer_id'] <= 0 || !$this->customerModel->getById($formData['customer_id'])) {
            $errors['customer_id'] = 'Customer wajib dipilih.';
        }

        if ($formData['vehicle_id'] <= 0 || !$this->vehicleModel->getById($formData['vehicle_id'])) {
            $errors['vehicle_id'] = 'Kendaraan wajib dipilih.';
        }

        if ($formData['service_id'] <= 0 || !$this->serviceModel->getById($formData['service_id'])) {
            $errors['service_id'] = 'Jasa wajib dipilih.';
        }

        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['error'] = 'Gagal mengupdate work order. Periksa kembali input.';
            header('Location: ' . BASE_URL . 'workorder/edit/' . $id);
            exit;
        }

        $this->workOrderModel->update((int) $id, $formData);

        $_SESSION['success'] = 'Work order berhasil diupdate.';
        header('Location: ' . BASE_URL . 'workorder');
        exit;
    }

    public function show(string $id): void
    {
        $workOrder = $this->workOrderModel->getById((int) $id);

        if (!$workOrder) {
            $_SESSION['error'] = 'Work order tidak ditemukan.';
            header('Location: ' . BASE_URL . 'workorder');
            exit;
        }

        $data = [
            'title' => 'Detail Work Order',
            'workOrder' => $workOrder,
        ];

        $this->view('work_orders/show', $data);
    }

    public function delete(string $id): void
    {
        $workOrder = $this->workOrderModel->getById((int) $id);

        if (!$workOrder) {
            $_SESSION['error'] = 'Work order tidak ditemukan.';
            header('Location: ' . BASE_URL . 'workorder');
            exit;
        }

        $this->workOrderModel->delete((int) $id);

        $_SESSION['success'] = 'Work order berhasil dihapus.';
        header('Location: ' . BASE_URL . 'workorder');
        exit;
    }

    private function validateWorkOrder(array $data): array
    {
        $errors = [];

        if ($data['work_date'] === '') {
            $errors['work_date'] = 'Tanggal work order wajib diisi.';
        }

        if ($data['estimated_service_price'] < 0) {
            $errors['estimated_service_price'] = 'Estimasi biaya tidak boleh minus.';
        }

        if (!in_array($data['status'], ['pending', 'antri', 'progress', 'done', 'cancelled'], true)) {
            $errors['status'] = 'Status tidak valid.';
        }

        return $errors;
    }
}