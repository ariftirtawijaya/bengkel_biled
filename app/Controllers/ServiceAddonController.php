<?php
declare(strict_types=1);

class ServiceAddonController extends Controller
{
    private ServiceAddonModel $serviceAddonModel;

    public function __construct()
    {
        $this->serviceAddonModel = new ServiceAddonModel();
    }

    public function index(): void
    {
        $data = [
            'title' => 'Master Add-on Pekerjaan',
            'addons' => $this->serviceAddonModel->getAll(),
        ];

        $this->view('service_addons/index', $data);
    }

    public function create(): void
    {
        $data = [
            'title' => 'Tambah Add-on Pekerjaan',
            'old' => $_SESSION['old'] ?? [],
            'errors' => $_SESSION['errors'] ?? [],
        ];

        unset($_SESSION['old'], $_SESSION['errors']);

        $this->view('service_addons/create', $data);
    }

    public function store(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . 'serviceaddon');
            exit;
        }

        $formData = [
            'name' => trim($_POST['name'] ?? ''),
            'addon_category' => trim($_POST['addon_category'] ?? ''),
            'vehicle_category' => trim($_POST['vehicle_category'] ?? ''),
            'price' => (float) ($_POST['price'] ?? 0),
            'estimated_minutes' => (int) ($_POST['estimated_minutes'] ?? 0),
            'description' => trim($_POST['description'] ?? ''),
            'is_active' => isset($_POST['is_active']) ? 1 : 0,
        ];

        $errors = $this->validateAddon($formData);

        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['old'] = $formData;
            $_SESSION['error'] = 'Gagal menyimpan add-on. Periksa kembali input.';
            header('Location: ' . BASE_URL . 'serviceaddon/create');
            exit;
        }

        $this->serviceAddonModel->create($formData);

        $_SESSION['success'] = 'Add-on berhasil ditambahkan.';
        header('Location: ' . BASE_URL . 'serviceaddon');
        exit;
    }

    public function edit(string $id): void
    {
        $addon = $this->serviceAddonModel->getById((int) $id);

        if (!$addon) {
            $_SESSION['error'] = 'Add-on tidak ditemukan.';
            header('Location: ' . BASE_URL . 'serviceaddon');
            exit;
        }

        $data = [
            'title' => 'Edit Add-on Pekerjaan',
            'addon' => $addon,
            'errors' => $_SESSION['errors'] ?? [],
        ];

        unset($_SESSION['errors']);

        $this->view('service_addons/edit', $data);
    }

    public function update(string $id): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . 'serviceaddon');
            exit;
        }

        $addon = $this->serviceAddonModel->getById((int) $id);

        if (!$addon) {
            $_SESSION['error'] = 'Add-on tidak ditemukan.';
            header('Location: ' . BASE_URL . 'serviceaddon');
            exit;
        }

        $formData = [
            'name' => trim($_POST['name'] ?? ''),
            'addon_category' => trim($_POST['addon_category'] ?? ''),
            'vehicle_category' => trim($_POST['vehicle_category'] ?? ''),
            'price' => (float) ($_POST['price'] ?? 0),
            'estimated_minutes' => (int) ($_POST['estimated_minutes'] ?? 0),
            'description' => trim($_POST['description'] ?? ''),
            'is_active' => isset($_POST['is_active']) ? 1 : 0,
        ];

        $errors = $this->validateAddon($formData);

        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['error'] = 'Gagal mengupdate add-on. Periksa kembali input.';
            header('Location: ' . BASE_URL . 'serviceaddon/edit/' . $id);
            exit;
        }

        $this->serviceAddonModel->update((int) $id, $formData);

        $_SESSION['success'] = 'Add-on berhasil diupdate.';
        header('Location: ' . BASE_URL . 'serviceaddon');
        exit;
    }

    public function delete(string $id): void
    {
        $addon = $this->serviceAddonModel->getById((int) $id);

        if (!$addon) {
            $_SESSION['error'] = 'Add-on tidak ditemukan.';
            header('Location: ' . BASE_URL . 'serviceaddon');
            exit;
        }

        $this->serviceAddonModel->delete((int) $id);

        $_SESSION['success'] = 'Add-on berhasil dihapus.';
        header('Location: ' . BASE_URL . 'serviceaddon');
        exit;
    }

    private function validateAddon(array $data): array
    {
        $errors = [];

        if ($data['name'] === '') {
            $errors['name'] = 'Nama add-on wajib diisi.';
        }

        if (!in_array($data['vehicle_category'], ['motor', 'mobil', 'umum'], true)) {
            $errors['vehicle_category'] = 'Kategori kendaraan wajib dipilih.';
        }

        if ($data['price'] < 0) {
            $errors['price'] = 'Harga add-on tidak boleh minus.';
        }

        if ($data['estimated_minutes'] < 0) {
            $errors['estimated_minutes'] = 'Estimasi durasi tidak boleh minus.';
        }

        return $errors;
    }
}