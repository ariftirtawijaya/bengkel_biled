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

// Controllers
require_once __DIR__ . '/../app/Controllers/HomeController.php';
require_once __DIR__ . '/../app/Controllers/DashboardController.php';
require_once __DIR__ . '/../app/Controllers/ProductController.php';
require_once __DIR__ . '/../app/Controllers/CustomerController.php';
require_once __DIR__ . '/../app/Controllers/VehicleController.php';
require_once __DIR__ . '/../app/Controllers/ServiceController.php';

$app = new App();