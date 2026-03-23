<!doctype html>
<html lang="id">

<head>
    <?php require __DIR__ . '/header.php'; ?>
</head>

<body>
    <div class="wrapper">
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
            <div class="container-fluid">
                <a class="navbar-brand fw-bold" href="<?= BASE_URL; ?>">Bengkel BILED</a>

                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="mainNavbar">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link" href="<?= BASE_URL; ?>">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= BASE_URL; ?>dashboard">Dashboard</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= BASE_URL; ?>product">Produk</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= BASE_URL; ?>customer">Customer</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= BASE_URL; ?>vehicle">Kendaraan</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <main class="container py-4">
            <?= $content; ?>
        </main>
    </div>

    <?php require __DIR__ . '/footer.php'; ?>
</body>

</html>