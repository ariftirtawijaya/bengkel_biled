<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-1">Detail Work Order</h1>
        <p class="text-muted mb-0">
            <?= htmlspecialchars($workOrder['wo_number']); ?>
        </p>
    </div>
    <div class="d-flex gap-2">
        <a href="<?= BASE_URL; ?>workorder/edit/<?= $workOrder['id']; ?>" class="btn btn-warning">Edit</a>
        <a href="<?= BASE_URL; ?>workorder" class="btn btn-secondary">Kembali</a>
    </div>
</div>

<div class="row g-3">
    <div class="col-md-6">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body">
                <h5 class="card-title">Informasi Work Order</h5>
                <table class="table table-sm mb-0">
                    <tr>
                        <th width="180">Nomor WO</th>
                        <td>
                            <?= htmlspecialchars($workOrder['wo_number']); ?>
                        </td>
                    </tr>
                    <tr>
                        <th>Tanggal Masuk</th>
                        <td>
                            <?= htmlspecialchars($workOrder['work_date']); ?>
                        </td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td>
                            <?= ucfirst(htmlspecialchars($workOrder['status'])); ?>
                        </td>
                    </tr>
                    <tr>
                        <th>Jasa</th>
                        <td>
                            <?= htmlspecialchars($workOrder['service_name']); ?>
                        </td>
                    </tr>
                    <tr>
                        <th>Estimasi Biaya</th>
                        <td>Rp
                            <?= number_format((float) $workOrder['estimated_service_price'], 0, ',', '.'); ?>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body">
                <h5 class="card-title">Customer & Kendaraan</h5>
                <table class="table table-sm mb-0">
                    <tr>
                        <th width="180">Customer</th>
                        <td>
                            <?= htmlspecialchars($workOrder['customer_name']); ?>
                        </td>
                    </tr>
                    <tr>
                        <th>No HP</th>
                        <td>
                            <?= htmlspecialchars($workOrder['customer_phone'] ?: '-'); ?>
                        </td>
                    </tr>
                    <tr>
                        <th>Kendaraan</th>
                        <td>
                            <?= htmlspecialchars($workOrder['brand'] . ' ' . $workOrder['model']); ?>
                        </td>
                    </tr>
                    <tr>
                        <th>Plat Nomor</th>
                        <td>
                            <?= htmlspecialchars($workOrder['plate_number'] ?: '-'); ?>
                        </td>
                    </tr>
                    <tr>
                        <th>Warna</th>
                        <td>
                            <?= htmlspecialchars($workOrder['color'] ?: '-'); ?>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h5 class="card-title">Keluhan / Request Customer</h5>
                <p class="mb-0">
                    <?= nl2br(htmlspecialchars($workOrder['complaint'] ?: '-')); ?>
                </p>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h5 class="card-title">Catatan Internal</h5>
                <p class="mb-0">
                    <?= nl2br(htmlspecialchars($workOrder['internal_notes'] ?: '-')); ?>
                </p>
            </div>
        </div>
    </div>
</div>