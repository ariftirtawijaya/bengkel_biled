<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-1">Dashboard</h1>
        <p class="text-muted mb-0">Ringkasan operasional bengkel.</p>
    </div>
</div>

<div class="row g-3">
    <div class="col-6 col-md-3">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body">
                <div class="text-muted small">Produk</div>
                <div class="fs-3 fw-bold">
                    <?= $summary['produk']; ?>
                </div>
            </div>
        </div>
    </div>

    <div class="col-6 col-md-3">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body">
                <div class="text-muted small">Customer</div>
                <div class="fs-3 fw-bold">
                    <?= $summary['customer']; ?>
                </div>
            </div>
        </div>
    </div>

    <div class="col-6 col-md-3">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body">
                <div class="text-muted small">Work Order</div>
                <div class="fs-3 fw-bold">
                    <?= $summary['work_order']; ?>
                </div>
            </div>
        </div>
    </div>

    <div class="col-6 col-md-3">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body">
                <div class="text-muted small">Omzet</div>
                <div class="fs-5 fw-bold">Rp
                    <?= number_format((float) $summary['omzet'], 0, ',', '.'); ?>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card shadow-sm border-0 mt-4">
    <div class="card-body">
        <h5 class="card-title">Catatan</h5>
        <p class="mb-0">
            Dashboard awal sudah aktif. Tahap berikutnya kita sambungkan ke database dan buat master produk.
        </p>
    </div>
</div>