<?php
declare(strict_types=1);

class ServiceController extends Controller
{
    private ServiceModel $serviceModel;

    public function __construct()
    {
        $this->serviceModel = new ServiceModel();
    }

    public function index(): void
    {
        $data = [
            'title' => 'Master Jasa Utama',
            'services' => $this->serviceModel->getAll(),
        ];

        $this->view('services/index', $data);
    }

    public function create(): void
    {
        $data = [
            'title' => 'Tambah Jasa Utama',
            'old' => $_SESSION['old'] ?? [],
            'errors' => $_SESSION['errors'] ?? [],
        ];

        unset($_SESSION['old'], $_SESSION['errors']);

        $this->view('services/create', $data);
    }

    public function store(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . 'service');
            exit;
        }

        $formData = [
            'name' => trim($_POST['name'] ?? ''),
            'service_type' => 'utama',
            'vehicle_category' => trim($_POST['vehicle_category'] ?? ''),
            'base_price' => (float) ($_POST['base_price'] ?? 0),
            'estimated_minutes' => (int) ($_POST['estimated_minutes'] ?? 0),
            'description' => trim($_POST['description'] ?? ''),
            'is_active' => isset($_POST['is_active']) ? 1 : 0,
        ];

        $errors = $this->validateService($formData);

        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['old'] = $formData;
            $_SESSION['error'] = 'Gagal menyimpan jasa. Periksa kembali input.';
            header('Location: ' . BASE_URL . 'service/create');
            exit;
        }

        $this->serviceModel->create($formData);

        $_SESSION['success'] = 'Jasa berhasil ditambahkan.';
        header('Location: ' . BASE_URL . 'service');
        exit;
    }

    public function edit(string $id): void
    {
        $service = $this->serviceModel->getById((int) $id);

        if (!$service) {
            $_SESSION['error'] = 'Jasa tidak ditemukan.';
            header('Location: ' . BASE_URL . 'service');
            exit;
        }

        $data = [
            'title' => 'Edit Jasa Utama',
            'service' => $service,
            'errors' => $_SESSION['errors'] ?? [],
        ];

        unset($_SESSION['errors']);

        $this->view('services/edit', $data);
    }

    public function update(string $id): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . 'service');
            exit;
        }

        $service = $this->serviceModel->getById((int) $id);

        if (!$service) {
            $_SESSION['error'] = 'Jasa tidak ditemukan.';
            header('Location: ' . BASE_URL . 'service');
            exit;
        }

        $formData = [
            'name' => trim($_POST['name'] ?? ''),
            'service_type' => 'utama',
            'vehicle_category' => trim($_POST['vehicle_category'] ?? ''),
            'base_price' => (float) ($_POST['base_price'] ?? 0),
            'estimated_minutes' => (int) ($_POST['estimated_minutes'] ?? 0),
            'description' => trim($_POST['description'] ?? ''),
            'is_active' => isset($_POST['is_active']) ? 1 : 0,
        ];

        $errors = $this->validateService($formData);

        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['error'] = 'Gagal mengupdate jasa. Periksa kembali input.';
            header('Location: ' . BASE_URL . 'service/edit/' . $id);
            exit;
        }

        $this->serviceModel->update((int) $id, $formData);

        $_SESSION['success'] = 'Jasa berhasil diupdate.';
        header('Location: ' . BASE_URL . 'service');
        exit;
    }

    public function delete(string $id): void
    {
        $service = $this->serviceModel->getById((int) $id);

        if (!$service) {
            $_SESSION['error'] = 'Jasa tidak ditemukan.';
            header('Location: ' . BASE_URL . 'service');
            exit;
        }

        $this->serviceModel->delete((int) $id);

        $_SESSION['success'] = 'Jasa berhasil dihapus.';
        header('Location: ' . BASE_URL . 'service');
        exit;
    }

    private function validateService(array $data): array
    {
        $errors = [];

        if ($data['name'] === '') {
            $errors['name'] = 'Nama jasa wajib diisi.';
        }

        if (!in_array($data['vehicle_category'], ['motor', 'mobil', 'umum'], true)) {
            $errors['vehicle_category'] = 'Kategori kendaraan wajib dipilih.';
        }

        if ($data['base_price'] < 0) {
            $errors['base_price'] = 'Harga dasar tidak boleh minus.';
        }

        if ($data['estimated_minutes'] < 0) {
            $errors['estimated_minutes'] = 'Estimasi durasi tidak boleh minus.';
        }

        return $errors;
    }
}