<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
    <div>
        <h1 class="h3 mb-1">Master Kendaraan</h1>
        <p class="text-muted mb-0">Kelola data kendaraan customer.</p>
    </div>
    <div>
        <a href="<?= BASE_URL; ?>vehicle/create" class="btn btn-primary">
            <i class="bi bi-plus-circle me-1"></i> Tambah Kendaraan
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
            <table id="vehiclesTable" class="table table-bordered table-hover align-middle mb-0"
                data-has-data="<?= !empty($vehicles) ? '1' : '0'; ?>">
                <thead class="table-light">
                    <tr>
                        <th width="60">No</th>
                        <th>Customer</th>
                        <th>Kategori</th>
                        <th>Merk</th>
                        <th>Model</th>
                        <th>Tahun</th>
                        <th>Plat Nomor</th>
                        <th>Warna</th>
                        <th>Catatan</th>
                        <th width="180">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($vehicles)): ?>
                        <?php foreach ($vehicles as $index => $vehicle): ?>
                            <tr>
                                <td><?= $index + 1; ?></td>
                                <td><?= htmlspecialchars($vehicle['customer_name']); ?></td>
                                <td>
                                    <span class="badge <?= $vehicle['category'] === 'motor' ? 'bg-primary' : 'bg-success'; ?>">
                                        <?= ucfirst($vehicle['category']); ?>
                                    </span>
                                </td>
                                <td><?= htmlspecialchars($vehicle['brand']); ?></td>
                                <td><?= htmlspecialchars($vehicle['model']); ?></td>
                                <td><?= htmlspecialchars($vehicle['year'] ?: '-'); ?></td>
                                <td><?= htmlspecialchars($vehicle['plate_number'] ?: '-'); ?></td>
                                <td><?= htmlspecialchars($vehicle['color'] ?: '-'); ?></td>
                                <td><?= nl2br(htmlspecialchars($vehicle['notes'] ?: '-')); ?></td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <a href="<?= BASE_URL; ?>vehicle/edit/<?= $vehicle['id']; ?>"
                                            class="btn btn-sm btn-warning">
                                            Edit
                                        </a>
                                        <button type="button" class="btn btn-sm btn-danger btn-delete-vehicle"
                                            data-url="<?= BASE_URL; ?>vehicle/delete/<?= $vehicle['id']; ?>"
                                            data-name="<?= htmlspecialchars($vehicle['brand'] . ' ' . $vehicle['model']); ?>">
                                            Hapus
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="10" class="text-center text-muted py-4">
                                Belum ada data kendaraan.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>