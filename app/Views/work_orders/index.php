<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
    <div>
        <h1 class="h3 mb-1">Work Order</h1>
        <p class="text-muted mb-0">Kelola antrian dan pekerjaan bengkel.</p>
    </div>
    <div>
        <a href="<?= BASE_URL; ?>workorder/create" class="btn btn-primary">
            <i class="bi bi-plus-circle me-1"></i> Tambah Work Order
        </a>
    </div>
</div>

<?php if (!empty($_SESSION['success'])): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?= $_SESSION['success'];
        unset($_SESSION['success']); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<?php if (!empty($_SESSION['error'])): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <?= $_SESSION['error'];
        unset($_SESSION['error']); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<div class="card shadow-sm border-0">
    <div class="card-body">
        <div class="table-responsive">
            <table id="workOrdersTable" class="table table-bordered table-hover align-middle mb-0"
                data-has-data="<?= !empty($workOrders) ? '1' : '0'; ?>">
                <thead class="table-light">
                    <tr>
                        <th>No WO</th>
                        <th>Tanggal</th>
                        <th>Customer</th>
                        <th>Kendaraan</th>
                        <th>Jasa</th>
                        <th class="text-end">Estimasi Biaya</th>
                        <th>Status</th>
                        <th width="240">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($workOrders)): ?>
                        <?php foreach ($workOrders as $wo): ?>
                            <tr>
                                <td>
                                    <?= htmlspecialchars($wo['wo_number']); ?>
                                </td>
                                <td>
                                    <?= htmlspecialchars($wo['work_date']); ?>
                                </td>
                                <td>
                                    <?= htmlspecialchars($wo['customer_name']); ?>
                                </td>
                                <td>
                                    <?= htmlspecialchars($wo['brand'] . ' ' . $wo['model']); ?>
                                    <div><small class="text-muted">
                                            <?= htmlspecialchars($wo['plate_number'] ?: '-'); ?>
                                        </small></div>
                                </td>
                                <td>
                                    <?= htmlspecialchars($wo['service_name']); ?>
                                </td>
                                <td class="text-end">Rp
                                    <?= number_format((float) $wo['estimated_service_price'], 0, ',', '.'); ?>
                                </td>
                                <td>
                                    <?php
                                    $statusClass = 'bg-secondary';
                                    if ($wo['status'] === 'pending')
                                        $statusClass = 'bg-secondary';
                                    if ($wo['status'] === 'antri')
                                        $statusClass = 'bg-info text-dark';
                                    if ($wo['status'] === 'progress')
                                        $statusClass = 'bg-warning text-dark';
                                    if ($wo['status'] === 'done')
                                        $statusClass = 'bg-success';
                                    if ($wo['status'] === 'cancelled')
                                        $statusClass = 'bg-danger';
                                    ?>
                                    <span class="badge <?= $statusClass; ?>">
                                        <?= ucfirst($wo['status']); ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex flex-wrap gap-2">
                                        <a href="<?= BASE_URL; ?>workorder/show/<?= $wo['id']; ?>"
                                            class="btn btn-sm btn-info text-white">Detail</a>
                                        <a href="<?= BASE_URL; ?>workorder/edit/<?= $wo['id']; ?>"
                                            class="btn btn-sm btn-warning">Edit</a>
                                        <button type="button" class="btn btn-sm btn-danger btn-delete-workorder"
                                            data-url="<?= BASE_URL; ?>workorder/delete/<?= $wo['id']; ?>"
                                            data-name="<?= htmlspecialchars($wo['wo_number']); ?>">
                                            Hapus
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">
                                Belum ada data work order.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>