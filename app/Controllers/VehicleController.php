<?php
declare(strict_types=1);

class VehicleController extends Controller
{
    private VehicleModel $vehicleModel;
    private CustomerModel $customerModel;

    public function __construct()
    {
        $this->vehicleModel = new VehicleModel();
        $this->customerModel = new CustomerModel();
    }

    public function index(): void
    {
        $data = [
            'title' => 'Master Kendaraan',
            'vehicles' => $this->vehicleModel->getAll(),
        ];

        $this->view('vehicles/index', $data);
    }

    public function create(): void
    {
        $data = [
            'title' => 'Tambah Kendaraan',
            'customers' => $this->customerModel->getAll(),
            'old' => $_SESSION['old'] ?? [],
            'errors' => $_SESSION['errors'] ?? [],
        ];

        unset($_SESSION['old'], $_SESSION['errors']);

        $this->view('vehicles/create', $data);
    }

    public function store(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . 'vehicle');
            exit;
        }

        $formData = [
            'customer_id' => (int) ($_POST['customer_id'] ?? 0),
            'category' => trim($_POST['category'] ?? ''),
            'brand' => trim($_POST['brand'] ?? ''),
            'model' => trim($_POST['model'] ?? ''),
            'year' => trim($_POST['year'] ?? ''),
            'plate_number' => trim($_POST['plate_number'] ?? ''),
            'color' => trim($_POST['color'] ?? ''),
            'notes' => trim($_POST['notes'] ?? ''),
        ];

        $errors = $this->validateVehicle($formData);

        if ($formData['customer_id'] <= 0 || !$this->customerModel->getById($formData['customer_id'])) {
            $errors['customer_id'] = 'Customer wajib dipilih.';
        }

        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['old'] = $formData;
            $_SESSION['error'] = 'Gagal menyimpan kendaraan. Periksa kembali input.';
            header('Location: ' . BASE_URL . 'vehicle/create');
            exit;
        }

        $this->vehicleModel->create($formData);

        $_SESSION['success'] = 'Kendaraan berhasil ditambahkan.';
        header('Location: ' . BASE_URL . 'vehicle');
        exit;
    }

    public function edit(string $id): void
    {
        $vehicle = $this->vehicleModel->getById((int) $id);

        if (!$vehicle) {
            $_SESSION['error'] = 'Kendaraan tidak ditemukan.';
            header('Location: ' . BASE_URL . 'vehicle');
            exit;
        }

        $data = [
            'title' => 'Edit Kendaraan',
            'vehicle' => $vehicle,
            'customers' => $this->customerModel->getAll(),
            'errors' => $_SESSION['errors'] ?? [],
        ];

        unset($_SESSION['errors']);

        $this->view('vehicles/edit', $data);
    }

    public function update(string $id): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . 'vehicle');
            exit;
        }

        $vehicle = $this->vehicleModel->getById((int) $id);

        if (!$vehicle) {
            $_SESSION['error'] = 'Kendaraan tidak ditemukan.';
            header('Location: ' . BASE_URL . 'vehicle');
            exit;
        }

        $formData = [
            'customer_id' => (int) ($_POST['customer_id'] ?? 0),
            'category' => trim($_POST['category'] ?? ''),
            'brand' => trim($_POST['brand'] ?? ''),
            'model' => trim($_POST['model'] ?? ''),
            'year' => trim($_POST['year'] ?? ''),
            'plate_number' => trim($_POST['plate_number'] ?? ''),
            'color' => trim($_POST['color'] ?? ''),
            'notes' => trim($_POST['notes'] ?? ''),
        ];

        $errors = $this->validateVehicle($formData);

        if ($formData['customer_id'] <= 0 || !$this->customerModel->getById($formData['customer_id'])) {
            $errors['customer_id'] = 'Customer wajib dipilih.';
        }

        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['error'] = 'Gagal mengupdate kendaraan. Periksa kembali input.';
            header('Location: ' . BASE_URL . 'vehicle/edit/' . $id);
            exit;
        }

        $this->vehicleModel->update((int) $id, $formData);

        $_SESSION['success'] = 'Kendaraan berhasil diupdate.';
        header('Location: ' . BASE_URL . 'vehicle');
        exit;
    }

    public function delete(string $id): void
    {
        $vehicle = $this->vehicleModel->getById((int) $id);

        if (!$vehicle) {
            $_SESSION['error'] = 'Kendaraan tidak ditemukan.';
            header('Location: ' . BASE_URL . 'vehicle');
            exit;
        }

        $this->vehicleModel->delete((int) $id);

        $_SESSION['success'] = 'Kendaraan berhasil dihapus.';
        header('Location: ' . BASE_URL . 'vehicle');
        exit;
    }

    private function validateVehicle(array $data): array
    {
        $errors = [];

        if (!in_array($data['category'], ['motor', 'mobil'], true)) {
            $errors['category'] = 'Kategori kendaraan wajib dipilih.';
        }

        if ($data['brand'] === '') {
            $errors['brand'] = 'Merk kendaraan wajib diisi.';
        }

        if ($data['model'] === '') {
            $errors['model'] = 'Model kendaraan wajib diisi.';
        }

        return $errors;
    }
}