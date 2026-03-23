<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
    <div>
        <h1 class="h3 mb-1">Pembayaran</h1>
        <p class="text-muted mb-0">Riwayat pembayaran semua work order.</p>
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
            <table id="paymentsTable" class="table table-bordered table-hover align-middle mb-0"
                data-has-data="<?= !empty($payments) ? '1' : '0'; ?>">
                <thead class="table-light">
                    <tr>
                        <th>Tanggal</th>
                        <th>No WO</th>
                        <th>Customer</th>
                        <th class="text-end">Nominal</th>
                        <th>Metode</th>
                        <th>Catatan</th>
                        <th width="140">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($payments)): ?>
                        <?php foreach ($payments as $payment): ?>
                            <tr>
                                <td>
                                    <?= htmlspecialchars($payment['payment_date']); ?>
                                </td>
                                <td>
                                    <?= htmlspecialchars($payment['wo_number']); ?>
                                </td>
                                <td>
                                    <?= htmlspecialchars($payment['customer_name']); ?>
                                </td>
                                <td class="text-end">Rp
                                    <?= number_format((float) $payment['amount'], 0, ',', '.'); ?>
                                </td>
                                <td>
                                    <?= strtoupper(htmlspecialchars($payment['payment_method'])); ?>
                                </td>
                                <td>
                                    <?= htmlspecialchars($payment['notes'] ?: '-'); ?>
                                </td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <a href="<?= BASE_URL; ?>payment/workorder/<?= $payment['work_order_id']; ?>"
                                            class="btn btn-sm btn-info text-white">Detail</a>
                                        <button type="button" class="btn btn-sm btn-danger btn-delete-payment"
                                            data-url="<?= BASE_URL; ?>payment/delete/<?= $payment['id']; ?>"
                                            data-name="<?= htmlspecialchars($payment['wo_number']); ?>">
                                            Hapus
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">Belum ada data pembayaran.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>