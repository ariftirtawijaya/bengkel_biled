<?php
declare(strict_types=1);

class WorkOrderController extends Controller
{
    private WorkOrderModel $workOrderModel;
    private CustomerModel $customerModel;
    private VehicleModel $vehicleModel;
    private ServiceModel $serviceModel;
    private ServiceAddonModel $serviceAddonModel;
    private ProductModel $productModel;

    public function __construct()
    {
        $this->workOrderModel = new WorkOrderModel();
        $this->customerModel = new CustomerModel();
        $this->vehicleModel = new VehicleModel();
        $this->serviceModel = new ServiceModel();
        $this->serviceAddonModel = new ServiceAddonModel();
        $this->productModel = new ProductModel();
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
        $customers = $this->customerModel->getAll();
        $vehicles = $this->vehicleModel->getAll();
        $services = $this->serviceModel->getAll();
        $addons = $this->serviceAddonModel->getAll();
        $products = $this->productModel->getAll();

        $data = [
            'title' => 'Tambah Work Order',
            'wo_number' => $this->workOrderModel->generateWoNumber(),
            'customers' => $customers,
            'vehicles' => $vehicles,
            'services' => $services,
            'addons' => $addons,
            'products' => $products,
            'vehiclesJson' => json_encode($vehicles, JSON_UNESCAPED_UNICODE),
            'addonsJson' => json_encode($addons, JSON_UNESCAPED_UNICODE),
            'productsJson' => json_encode($products, JSON_UNESCAPED_UNICODE),
            'old' => $_SESSION['old'] ?? [],
            'errors' => $_SESSION['errors'] ?? [],
            'oldAddons' => $_SESSION['old_addons'] ?? [],
            'oldProducts' => $_SESSION['old_products'] ?? [],
        ];

        unset($_SESSION['old'], $_SESSION['errors'], $_SESSION['old_addons'], $_SESSION['old_products']);

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
            'addons_total' => 0,
            'products_total' => 0,
            'grand_total' => 0,
            'status' => trim($_POST['status'] ?? 'pending'),
            'internal_notes' => trim($_POST['internal_notes'] ?? ''),
        ];

        if ($formData['wo_number'] === '') {
            $formData['wo_number'] = $this->workOrderModel->generateWoNumber();
        }

        $addons = $this->parseAddonInputs();
        $products = $this->parseProductInputs();

        $formData['addons_total'] = $this->sumSubtotals($addons);
        $formData['products_total'] = $this->sumSubtotals($products);
        $formData['grand_total'] = $formData['estimated_service_price'] + $formData['addons_total'] + $formData['products_total'];

        $errors = $this->validateWorkOrder($formData);

        $customer = $this->customerModel->getById($formData['customer_id']);
        $vehicle = $this->vehicleModel->getById($formData['vehicle_id']);
        $service = $this->serviceModel->getById($formData['service_id']);

        if ($formData['customer_id'] <= 0 || !$customer) {
            $errors['customer_id'] = 'Customer wajib dipilih.';
        }

        if ($formData['vehicle_id'] <= 0 || !$vehicle) {
            $errors['vehicle_id'] = 'Kendaraan wajib dipilih.';
        }

        if ($formData['service_id'] <= 0 || !$service) {
            $errors['service_id'] = 'Jasa wajib dipilih.';
        }

        if ($customer && $vehicle && (int) $vehicle['customer_id'] !== (int) $customer['id']) {
            $errors['vehicle_id'] = 'Kendaraan tidak cocok dengan customer yang dipilih.';
        }

        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['old'] = $formData;
            $_SESSION['old_addons'] = $addons;
            $_SESSION['old_products'] = $products;
            $_SESSION['error'] = 'Gagal menyimpan work order. Periksa kembali input.';
            header('Location: ' . BASE_URL . 'workorder/create');
            exit;
        }

        $this->workOrderModel->create($formData, $addons, $products);

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

        $customers = $this->customerModel->getAll();
        $vehicles = $this->vehicleModel->getAll();
        $services = $this->serviceModel->getAll();
        $addons = $this->serviceAddonModel->getAll();
        $products = $this->productModel->getAll();

        $data = [
            'title' => 'Edit Work Order',
            'workOrder' => $workOrder,
            'customers' => $customers,
            'vehicles' => $vehicles,
            'services' => $services,
            'addons' => $addons,
            'products' => $products,
            'vehiclesJson' => json_encode($vehicles, JSON_UNESCAPED_UNICODE),
            'addonsJson' => json_encode($addons, JSON_UNESCAPED_UNICODE),
            'productsJson' => json_encode($products, JSON_UNESCAPED_UNICODE),
            'errors' => $_SESSION['errors'] ?? [],
            'selectedAddons' => $this->workOrderModel->getAddonsByWorkOrderId((int) $id),
            'selectedProducts' => $this->workOrderModel->getProductsByWorkOrderId((int) $id),
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
            'addons_total' => 0,
            'products_total' => 0,
            'grand_total' => 0,
            'status' => trim($_POST['status'] ?? 'pending'),
            'internal_notes' => trim($_POST['internal_notes'] ?? ''),
        ];

        $addons = $this->parseAddonInputs();
        $products = $this->parseProductInputs();

        $formData['addons_total'] = $this->sumSubtotals($addons);
        $formData['products_total'] = $this->sumSubtotals($products);
        $formData['grand_total'] = $formData['estimated_service_price'] + $formData['addons_total'] + $formData['products_total'];

        $errors = $this->validateWorkOrder($formData);

        $customer = $this->customerModel->getById($formData['customer_id']);
        $vehicle = $this->vehicleModel->getById($formData['vehicle_id']);
        $service = $this->serviceModel->getById($formData['service_id']);

        if ($formData['customer_id'] <= 0 || !$customer) {
            $errors['customer_id'] = 'Customer wajib dipilih.';
        }

        if ($formData['vehicle_id'] <= 0 || !$vehicle) {
            $errors['vehicle_id'] = 'Kendaraan wajib dipilih.';
        }

        if ($formData['service_id'] <= 0 || !$service) {
            $errors['service_id'] = 'Jasa wajib dipilih.';
        }

        if ($customer && $vehicle && (int) $vehicle['customer_id'] !== (int) $customer['id']) {
            $errors['vehicle_id'] = 'Kendaraan tidak cocok dengan customer yang dipilih.';
        }

        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['error'] = 'Gagal mengupdate work order. Periksa kembali input.';
            header('Location: ' . BASE_URL . 'workorder/edit/' . $id);
            exit;
        }

        $this->workOrderModel->update((int) $id, $formData, $addons, $products);

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
            'addons' => $this->workOrderModel->getAddonsByWorkOrderId((int) $id),
            'products' => $this->workOrderModel->getProductsByWorkOrderId((int) $id),
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

    private function parseAddonInputs(): array
    {
        $addonIds = $_POST['addon_id'] ?? [];
        $addonNames = $_POST['addon_name'] ?? [];
        $addonPrices = $_POST['addon_price'] ?? [];
        $addonQtys = $_POST['addon_qty'] ?? [];
        $addonSubtotals = $_POST['addon_subtotal'] ?? [];
        $addonNotes = $_POST['addon_notes'] ?? [];

        $result = [];

        foreach ($addonIds as $index => $addonId) {
            $addonId = (int) $addonId;
            $name = trim($addonNames[$index] ?? '');
            $price = (float) ($addonPrices[$index] ?? 0);
            $qty = (int) ($addonQtys[$index] ?? 0);
            $subtotal = (float) ($addonSubtotals[$index] ?? 0);
            $notes = trim($addonNotes[$index] ?? '');

            if ($addonId <= 0 || $name === '' || $qty <= 0) {
                continue;
            }

            $result[] = [
                'addon_id' => $addonId,
                'addon_name' => $name,
                'price' => $price,
                'qty' => $qty,
                'subtotal' => $subtotal > 0 ? $subtotal : ($price * $qty),
                'notes' => $notes,
            ];
        }

        return $result;
    }

    private function parseProductInputs(): array
    {
        $productIds = $_POST['product_id'] ?? [];
        $productCodes = $_POST['product_code'] ?? [];
        $productNames = $_POST['product_name'] ?? [];
        $productPrices = $_POST['product_price'] ?? [];
        $productQtys = $_POST['product_qty'] ?? [];
        $productSubtotals = $_POST['product_subtotal'] ?? [];
        $productNotes = $_POST['product_notes'] ?? [];

        $result = [];

        foreach ($productIds as $index => $productId) {
            $productId = (int) $productId;
            $code = trim($productCodes[$index] ?? '');
            $name = trim($productNames[$index] ?? '');
            $price = (float) ($productPrices[$index] ?? 0);
            $qty = (float) ($productQtys[$index] ?? 0);
            $subtotal = (float) ($productSubtotals[$index] ?? 0);
            $notes = trim($productNotes[$index] ?? '');

            if ($productId <= 0 || $name === '' || $qty <= 0) {
                continue;
            }

            $result[] = [
                'product_id' => $productId,
                'product_code' => $code,
                'product_name' => $name,
                'price' => $price,
                'qty' => $qty,
                'subtotal' => $subtotal > 0 ? $subtotal : ($price * $qty),
                'notes' => $notes,
            ];
        }

        return $result;
    }

    private function sumSubtotals(array $items): float
    {
        $total = 0;
        foreach ($items as $item) {
            $total += (float) $item['subtotal'];
        }
        return $total;
    }
}