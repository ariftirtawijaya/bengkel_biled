<?php
declare(strict_types=1);

class CustomerController extends Controller
{
    private CustomerModel $customerModel;

    public function __construct()
    {
        $this->customerModel = new CustomerModel();
    }

    public function index(): void
    {
        $data = [
            'title' => 'Master Customer',
            'customers' => $this->customerModel->getAll(),
        ];

        $this->view('customers/index', $data);
    }

    public function create(): void
    {
        $data = [
            'title' => 'Tambah Customer',
            'old' => $_SESSION['old'] ?? [],
            'errors' => $_SESSION['errors'] ?? [],
        ];

        unset($_SESSION['old'], $_SESSION['errors']);

        $this->view('customers/create', $data);
    }

    public function store(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . 'customer');
            exit;
        }

        $formData = [
            'name' => trim($_POST['name'] ?? ''),
            'phone' => trim($_POST['phone'] ?? ''),
            'address' => trim($_POST['address'] ?? ''),
        ];

        $errors = $this->validateCustomer($formData);

        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['old'] = $formData;
            $_SESSION['error'] = 'Gagal menyimpan customer. Periksa kembali input.';
            header('Location: ' . BASE_URL . 'customer/create');
            exit;
        }

        $this->customerModel->create($formData);

        $_SESSION['success'] = 'Customer berhasil ditambahkan.';
        header('Location: ' . BASE_URL . 'customer');
        exit;
    }

    public function edit(string $id): void
    {
        $customer = $this->customerModel->getById((int) $id);

        if (!$customer) {
            $_SESSION['error'] = 'Customer tidak ditemukan.';
            header('Location: ' . BASE_URL . 'customer');
            exit;
        }

        $data = [
            'title' => 'Edit Customer',
            'customer' => $customer,
            'errors' => $_SESSION['errors'] ?? [],
        ];

        unset($_SESSION['errors']);

        $this->view('customers/edit', $data);
    }

    public function update(string $id): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . 'customer');
            exit;
        }

        $customer = $this->customerModel->getById((int) $id);

        if (!$customer) {
            $_SESSION['error'] = 'Customer tidak ditemukan.';
            header('Location: ' . BASE_URL . 'customer');
            exit;
        }

        $formData = [
            'name' => trim($_POST['name'] ?? ''),
            'phone' => trim($_POST['phone'] ?? ''),
            'address' => trim($_POST['address'] ?? ''),
        ];

        $errors = $this->validateCustomer($formData);

        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['error'] = 'Gagal mengupdate customer. Periksa kembali input.';
            header('Location: ' . BASE_URL . 'customer/edit/' . $id);
            exit;
        }

        $this->customerModel->update((int) $id, $formData);

        $_SESSION['success'] = 'Customer berhasil diupdate.';
        header('Location: ' . BASE_URL . 'customer');
        exit;
    }

    public function delete(string $id): void
    {
        $customer = $this->customerModel->getById((int) $id);

        if (!$customer) {
            $_SESSION['error'] = 'Customer tidak ditemukan.';
            header('Location: ' . BASE_URL . 'customer');
            exit;
        }

        $this->customerModel->delete((int) $id);

        $_SESSION['success'] = 'Customer berhasil dihapus.';
        header('Location: ' . BASE_URL . 'customer');
        exit;
    }

    private function validateCustomer(array $data): array
    {
        $errors = [];

        if ($data['name'] === '') {
            $errors['name'] = 'Nama customer wajib diisi.';
        }

        if ($data['phone'] !== '' && strlen($data['phone']) > 30) {
            $errors['phone'] = 'Nomor HP terlalu panjang.';
        }

        return $errors;
    }
}