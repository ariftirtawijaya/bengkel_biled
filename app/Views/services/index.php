<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
    <div>
        <h1 class="h3 mb-1">Master Jasa Utama</h1>
        <p class="text-muted mb-0">Kelola jasa inti untuk pekerjaan bengkel.</p>
    </div>
    <div>
        <a href="<?= BASE_URL; ?>service/create" class="btn btn-primary">
            <i class="bi bi-plus-circle me-1"></i> Tambah Jasa
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
            <table id="servicesTable" class="table table-bordered table-hover align-middle mb-0"
                data-has-data="<?= !empty($services) ? '1' : '0'; ?>">
                <thead class="table-light">
                    <tr>
                        <th width="60">No</th>
                        <th>Nama Jasa</th>
                        <th>Kategori Kendaraan</th>
                        <th class="text-end">Harga Dasar</th>
                        <th class="text-end">Estimasi</th>
                        <th>Deskripsi</th>
                        <th>Status</th>
                        <th width="180">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($services)): ?>
                        <?php foreach ($services as $index => $service): ?>
                            <tr>
                                <td>
                                    <?= $index + 1; ?>
                                </td>
                                <td>
                                    <?= htmlspecialchars($service['name']); ?>
                                </td>
                                <td>
                                    <?php
                                    $badgeClass = 'bg-secondary';
                                    if ($service['vehicle_category'] === 'motor')
                                        $badgeClass = 'bg-primary';
                                    if ($service['vehicle_category'] === 'mobil')
                                        $badgeClass = 'bg-success';
                                    ?>
                                    <span class="badge <?= $badgeClass; ?>">
                                        <?= ucfirst($service['vehicle_category']); ?>
                                    </span>
                                </td>
                                <td class="text-end">Rp
                                    <?= number_format((float) $service['base_price'], 0, ',', '.'); ?>
                                </td>
                                <td class="text-end">
                                    <?= (int) $service['estimated_minutes']; ?> menit
                                </td>
                                <td>
                                    <?= nl2br(htmlspecialchars($service['description'] ?: '-')); ?>
                                </td>
                                <td>
                                    <?php if ((int) $service['is_active'] === 1): ?>
                                        <span class="badge bg-success">Aktif</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">Nonaktif</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <a href="<?= BASE_URL; ?>service/edit/<?= $service['id']; ?>"
                                            class="btn btn-sm btn-warning">
                                            Edit
                                        </a>
                                        <button type="button" class="btn btn-sm btn-danger btn-delete-service"
                                            data-url="<?= BASE_URL; ?>service/delete/<?= $service['id']; ?>"
                                            data-name="<?= htmlspecialchars($service['name']); ?>">
                                            Hapus
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">
                                Belum ada data jasa.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>