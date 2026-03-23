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

// Controllers
require_once __DIR__ . '/../app/Controllers/HomeController.php';
require_once __DIR__ . '/../app/Controllers/DashboardController.php';
require_once __DIR__ . '/../app/Controllers/ProductController.php';

$app = new App();