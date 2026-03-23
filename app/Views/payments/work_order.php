<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-1">Pembayaran Work Order</h1>
        <p class="text-muted mb-0">
            <?= htmlspecialchars($workOrder['wo_number']); ?>
        </p>
    </div>
    <div class="d-flex gap-2">
        <a href="<?= BASE_URL; ?>workorder/show/<?= $workOrder['id']; ?>" class="btn btn-info text-white">Detail WO</a>
        <a href="<?= BASE_URL; ?>payment" class="btn btn-secondary">Kembali</a>
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

<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <div class="text-muted small">Grand Total</div>
                <div class="fs-5 fw-bold">Rp
                    <?= number_format((float) $workOrder['grand_total'], 0, ',', '.'); ?>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <div class="text-muted small">Sudah Dibayar</div>
                <div class="fs-5 fw-bold">Rp
                    <?= number_format((float) $paidTotal, 0, ',', '.'); ?>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <div class="text-muted small">Sisa Tagihan</div>
                <div class="fs-5 fw-bold">Rp
                    <?= number_format((float) $remaining, 0, ',', '.'); ?>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <div class="text-muted small">Status Bayar</div>
                <div class="fs-5 fw-bold">
                    <?= strtoupper(htmlspecialchars($workOrder['payment_status'])); ?>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card shadow-sm border-0 mb-4">
    <div class="card-body">
        <h5 class="mb-3">Tambah Pembayaran</h5>

        <form action="<?= BASE_URL; ?>payment/store" method="POST">
            <input type="hidden" name="work_order_id" value="<?= $workOrder['id']; ?>">

            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Tanggal Bayar</label>
                    <input type="date" name="payment_date"
                        class="form-control <?= !empty($errors['payment_date']) ? 'is-invalid' : ''; ?>"
                        value="<?= htmlspecialchars($old['payment_date'] ?? date('Y-m-d')); ?>" required>
                    <?php if (!empty($errors['payment_date'])): ?>
                        <div class="invalid-feedback">
                            <?= $errors['payment_date']; ?>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Nominal</label>
                    <input type="number" step="0.01" name="amount"
                        class="form-control <?= !empty($errors['amount']) ? 'is-invalid' : ''; ?>"
                        value="<?= htmlspecialchars((string) ($old['amount'] ?? '0')); ?>" required>
                    <?php if (!empty($errors['amount'])): ?>
                        <div class="invalid-feedback">
                            <?= $errors['amount']; ?>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Metode Bayar</label>
                    <select name="payment_method"
                        class="form-select <?= !empty($errors['payment_method']) ? 'is-invalid' : ''; ?>" required>
                        <?php $method = $old['payment_method'] ?? 'cash'; ?>
                        <option value="cash" <?= $method === 'cash' ? 'selected' : ''; ?>>Cash</option>
                        <option value="transfer" <?= $method === 'transfer' ? 'selected' : ''; ?>>Transfer</option>
                        <option value="qris" <?= $method === 'qris' ? 'selected' : ''; ?>>QRIS</option>
                        <option value="debit" <?= $method === 'debit' ? 'selected' : ''; ?>>Debit</option>
                        <option value="credit" <?= $method === 'credit' ? 'selected' : ''; ?>>Credit</option>
                        <option value="other" <?= $method === 'other' ? 'selected' : ''; ?>>Other</option>
                    </select>
                    <?php if (!empty($errors['payment_method'])): ?>
                        <div class="invalid-feedback">
                            <?= $errors['payment_method']; ?>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="col-12">
                    <label class="form-label">Catatan</label>
                    <input type="text" name="notes" class="form-control"
                        value="<?= htmlspecialchars($old['notes'] ?? ''); ?>"
                        placeholder="Contoh: DP awal, pelunasan, transfer BCA">
                </div>
            </div>

            <div class="mt-3">
                <button type="submit" class="btn btn-primary">Simpan Pembayaran</button>
            </div>
        </form>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body">
        <h5 class="mb-3">Riwayat Pembayaran</h5>

        <div class="table-responsive">
            <table class="table table-bordered align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Tanggal</th>
                        <th class="text-end">Nominal</th>
                        <th>Metode</th>
                        <th>Catatan</th>
                        <th width="120">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($payments)): ?>
                        <?php foreach ($payments as $payment): ?>
                            <tr>
                                <td>
                                    <?= htmlspecialchars($payment['payment_date']); ?>
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
                                    <button type="button" class="btn btn-sm btn-danger btn-delete-payment"
                                        data-url="<?= BASE_URL; ?>payment/delete/<?= $payment['id']; ?>"
                                        data-name="<?= htmlspecialchars($workOrder['wo_number']); ?>">
                                        Hapus
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">Belum ada pembayaran.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>