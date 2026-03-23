<?php
declare(strict_types=1);

class ProductController extends Controller
{
    private ProductModel $productModel;

    public function __construct()
    {
        $this->productModel = new ProductModel();
    }

    public function index(): void
    {
        $data = [
            'title' => 'Master Produk',
            'products' => $this->productModel->getAll(),
        ];

        $this->view('products/index', $data);
    }

    public function create(): void
    {
        $data = [
            'title' => 'Tambah Produk',
            'product_code' => $this->productModel->generateNextCode(),
            'old' => $_SESSION['old'] ?? [],
            'errors' => $_SESSION['errors'] ?? [],
        ];

        unset($_SESSION['old'], $_SESSION['errors']);

        $this->view('products/create', $data);
    }

    public function store(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . 'product');
            exit;
        }

        $formData = [
            'code' => trim($_POST['code'] ?? ''),
            'name' => trim($_POST['name'] ?? ''),
            'category' => trim($_POST['category'] ?? ''),
            'unit' => trim($_POST['unit'] ?? 'pcs'),
            'purchase_price' => (float) ($_POST['purchase_price'] ?? 0),
            'margin_percent' => (float) ($_POST['margin_percent'] ?? 0),
            'stock' => (float) ($_POST['stock'] ?? 0),
            'min_stock' => (float) ($_POST['min_stock'] ?? 0),
            'is_active' => isset($_POST['is_active']) ? 1 : 0,
        ];

        $errors = $this->validateProduct($formData);

        $existingCode = $this->productModel->getByCode($formData['code']);
        if ($existingCode) {
            $errors['code'] = 'Kode produk sudah digunakan.';
        }

        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['old'] = $formData;
            $_SESSION['error'] = 'Gagal menyimpan produk. Periksa kembali input.';
            header('Location: ' . BASE_URL . 'product/create');
            exit;
        }

        $formData['selling_price'] = $formData['purchase_price'] + ($formData['purchase_price'] * $formData['margin_percent'] / 100);

        $this->productModel->create($formData);

        $_SESSION['success'] = 'Produk berhasil ditambahkan.';
        header('Location: ' . BASE_URL . 'product');
        exit;
    }

    public function edit(string $id): void
    {
        $product = $this->productModel->getById((int) $id);

        if (!$product) {
            $_SESSION['error'] = 'Produk tidak ditemukan.';
            header('Location: ' . BASE_URL . 'product');
            exit;
        }

        $data = [
            'title' => 'Edit Produk',
            'product' => $product,
            'errors' => $_SESSION['errors'] ?? [],
        ];

        unset($_SESSION['errors']);

        $this->view('products/edit', $data);
    }

    public function update(string $id): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . 'product');
            exit;
        }

        $product = $this->productModel->getById((int) $id);

        if (!$product) {
            $_SESSION['error'] = 'Produk tidak ditemukan.';
            header('Location: ' . BASE_URL . 'product');
            exit;
        }

        $formData = [
            'code' => trim($_POST['code'] ?? ''),
            'name' => trim($_POST['name'] ?? ''),
            'category' => trim($_POST['category'] ?? ''),
            'unit' => trim($_POST['unit'] ?? 'pcs'),
            'purchase_price' => (float) ($_POST['purchase_price'] ?? 0),
            'margin_percent' => (float) ($_POST['margin_percent'] ?? 0),
            'stock' => (float) ($_POST['stock'] ?? 0),
            'min_stock' => (float) ($_POST['min_stock'] ?? 0),
            'is_active' => isset($_POST['is_active']) ? 1 : 0,
        ];

        $errors = $this->validateProduct($formData);

        $existingCode = $this->productModel->getByCode($formData['code']);
        if ($existingCode && (int) $existingCode['id'] !== (int) $id) {
            $errors['code'] = 'Kode produk sudah digunakan oleh produk lain.';
        }

        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['error'] = 'Gagal mengupdate produk. Periksa kembali input.';
            header('Location: ' . BASE_URL . 'product/edit/' . $id);
            exit;
        }

        $formData['selling_price'] = $formData['purchase_price'] + ($formData['purchase_price'] * $formData['margin_percent'] / 100);

        $this->productModel->update((int) $id, $formData);

        $_SESSION['success'] = 'Produk berhasil diupdate.';
        header('Location: ' . BASE_URL . 'product');
        exit;
    }

    public function delete(string $id): void
    {
        $product = $this->productModel->getById((int) $id);

        if (!$product) {
            $_SESSION['error'] = 'Produk tidak ditemukan.';
            header('Location: ' . BASE_URL . 'product');
            exit;
        }

        $this->productModel->delete((int) $id);

        $_SESSION['success'] = 'Produk berhasil dihapus.';
        header('Location: ' . BASE_URL . 'product');
        exit;
    }

    private function validateProduct(array $data): array
    {
        $errors = [];

        if ($data['code'] === '') {
            $errors['code'] = 'Kode produk wajib diisi.';
        }

        if ($data['name'] === '') {
            $errors['name'] = 'Nama produk wajib diisi.';
        }

        if ($data['unit'] === '') {
            $errors['unit'] = 'Satuan wajib diisi.';
        }

        if ($data['purchase_price'] < 0) {
            $errors['purchase_price'] = 'Harga beli tidak boleh minus.';
        }

        if ($data['margin_percent'] < 0) {
            $errors['margin_percent'] = 'Margin tidak boleh minus.';
        }

        if ($data['stock'] < 0) {
            $errors['stock'] = 'Stok tidak boleh minus.';
        }

        if ($data['min_stock'] < 0) {
            $errors['min_stock'] = 'Minimum stok tidak boleh minus.';
        }

        return $errors;
    }
}