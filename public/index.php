<?php
declare(strict_types=1);

session_start();

require_once __DIR__ . '/../app/Config/config.php';
require_once __DIR__ . '/../app/Core/Controller.php';
require_once __DIR__ . '/../app/Core/View.php';
require_once __DIR__ . '/../app/Core/Database.php';
require_once __DIR__ . '/../app/Core/App.php';

// Models
require_once __DIR__ . '/../app/Models/DashboardModel.php';
require_once __DIR__ . '/../app/Models/ProductModel.php';
require_once __DIR__ . '/../app/Models/CustomerModel.php';
require_once __DIR__ . '/../app/Models/VehicleModel.php';
require_once __DIR__ . '/../app/Models/ServiceModel.php';
require_once __DIR__ . '/../app/Models/ServiceAddonModel.php';
require_once __DIR__ . '/../app/Models/WorkOrderModel.php';
require_once __DIR__ . '/../app/Models/PaymentModel.php';

// Controllers
require_once __DIR__ . '/../app/Controllers/HomeController.php';
require_once __DIR__ . '/../app/Controllers/DashboardController.php';
require_once __DIR__ . '/../app/Controllers/ProductController.php';
require_once __DIR__ . '/../app/Controllers/CustomerController.php';
require_once __DIR__ . '/../app/Controllers/VehicleController.php';
require_once __DIR__ . '/../app/Controllers/ServiceController.php';
require_once __DIR__ . '/../app/Controllers/ServiceAddonController.php';
require_once __DIR__ . '/../app/Controllers/WorkOrderController.php';
require_once __DIR__ . '/../app/Controllers/PaymentController.php';

$app = new App();