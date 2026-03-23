<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
    <div>
        <h1 class="h3 mb-1">Master Customer</h1>
        <p class="text-muted mb-0">Kelola data customer bengkel.</p>
    </div>
    <div>
        <a href="<?= BASE_URL; ?>customer/create" class="btn btn-primary">
            <i class="bi bi-plus-circle me-1"></i> Tambah Customer
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
            <table id="customersTable" class="table table-bordered table-hover align-middle mb-0"
                data-has-data="<?= !empty($customers) ? '1' : '0'; ?>">
                <thead class="table-light">
                    <tr>
                        <th width="60">No</th>
                        <th>Nama</th>
                        <th>No HP</th>
                        <th>Alamat</th>
                        <th width="180">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($customers)): ?>
                        <?php foreach ($customers as $index => $customer): ?>
                            <tr>
                                <td>
                                    <?= $index + 1; ?>
                                </td>
                                <td>
                                    <?= htmlspecialchars($customer['name']); ?>
                                </td>
                                <td>
                                    <?= htmlspecialchars($customer['phone'] ?: '-'); ?>
                                </td>
                                <td>
                                    <?= nl2br(htmlspecialchars($customer['address'] ?: '-')); ?>
                                </td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <a href="<?= BASE_URL; ?>customer/edit/<?= $customer['id']; ?>"
                                            class="btn btn-sm btn-warning">
                                            Edit
                                        </a>
                                        <button type="button" class="btn btn-sm btn-danger btn-delete-customer"
                                            data-url="<?= BASE_URL; ?>customer/delete/<?= $customer['id']; ?>"
                                            data-name="<?= htmlspecialchars($customer['name']); ?>">
                                            Hapus
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">
                                Belum ada data customer.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>