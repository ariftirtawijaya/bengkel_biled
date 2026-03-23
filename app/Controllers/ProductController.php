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
        ];

        $this->view('products/create', $data);
    }

    public function store(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . 'product');
            exit;
        }

        $purchasePrice = (float) ($_POST['purchase_price'] ?? 0);
        $marginPercent = (float) ($_POST['margin_percent'] ?? 0);
        $sellingPrice = $purchasePrice + ($purchasePrice * $marginPercent / 100);

        $data = [
            'code' => trim($_POST['code'] ?? ''),
            'name' => trim($_POST['name'] ?? ''),
            'category' => trim($_POST['category'] ?? ''),
            'unit' => trim($_POST['unit'] ?? 'pcs'),
            'purchase_price' => $purchasePrice,
            'margin_percent' => $marginPercent,
            'selling_price' => $sellingPrice,
            'stock' => (float) ($_POST['stock'] ?? 0),
            'min_stock' => (float) ($_POST['min_stock'] ?? 0),
            'is_active' => isset($_POST['is_active']) ? 1 : 0,
        ];

        $this->productModel->create($data);

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
        ];

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

        $purchasePrice = (float) ($_POST['purchase_price'] ?? 0);
        $marginPercent = (float) ($_POST['margin_percent'] ?? 0);
        $sellingPrice = $purchasePrice + ($purchasePrice * $marginPercent / 100);

        $data = [
            'code' => trim($_POST['code'] ?? ''),
            'name' => trim($_POST['name'] ?? ''),
            'category' => trim($_POST['category'] ?? ''),
            'unit' => trim($_POST['unit'] ?? 'pcs'),
            'purchase_price' => $purchasePrice,
            'margin_percent' => $marginPercent,
            'selling_price' => $sellingPrice,
            'stock' => (float) ($_POST['stock'] ?? 0),
            'min_stock' => (float) ($_POST['min_stock'] ?? 0),
            'is_active' => isset($_POST['is_active']) ? 1 : 0,
        ];

        $this->productModel->update((int) $id, $data);

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
}