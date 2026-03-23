<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-1">Tambah Work Order</h1>
        <p class="text-muted mb-0">Buat work order baru untuk pekerjaan bengkel.</p>
    </div>
    <div>
        <a href="<?= BASE_URL; ?>workorder" class="btn btn-secondary">
            <i class="bi bi-arrow-left me-1"></i> Kembali
        </a>
    </div>
</div>

<?php if (!empty($_SESSION['error'])): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <?= $_SESSION['error'];
        unset($_SESSION['error']); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<div class="card shadow-sm border-0">
    <div class="card-body">
        <form action="<?= BASE_URL; ?>workorder/store" method="POST" id="workOrderForm">
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Nomor WO</label>
                    <input type="text" class="form-control"
                        value="<?= htmlspecialchars($old['wo_number'] ?? $wo_number); ?>" readonly>
                    <input type="hidden" name="wo_number"
                        value="<?= htmlspecialchars($old['wo_number'] ?? $wo_number); ?>">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Tanggal Masuk</label>
                    <input type="date" name="work_date"
                        class="form-control <?= !empty($errors['work_date']) ? 'is-invalid' : ''; ?>"
                        value="<?= htmlspecialchars($old['work_date'] ?? date('Y-m-d')); ?>" required>
                    <?php if (!empty($errors['work_date'])): ?>
                        <div class="invalid-feedback"><?= $errors['work_date']; ?></div>
                    <?php endif; ?>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select <?= !empty($errors['status']) ? 'is-invalid' : ''; ?>"
                        required>
                        <option value="pending" <?= ($old['status'] ?? 'pending') === 'pending' ? 'selected' : ''; ?>>
                            Pending</option>
                        <option value="antri" <?= ($old['status'] ?? '') === 'antri' ? 'selected' : ''; ?>>Antri</option>
                        <option value="progress" <?= ($old['status'] ?? '') === 'progress' ? 'selected' : ''; ?>>Progress
                        </option>
                        <option value="done" <?= ($old['status'] ?? '') === 'done' ? 'selected' : ''; ?>>Done</option>
                        <option value="cancelled" <?= ($old['status'] ?? '') === 'cancelled' ? 'selected' : ''; ?>>
                            Cancelled</option>
                    </select>
                    <?php if (!empty($errors['status'])): ?>
                        <div class="invalid-feedback"><?= $errors['status']; ?></div>
                    <?php endif; ?>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Customer</label>
                    <select name="customer_id" id="customer_id"
                        class="form-select <?= !empty($errors['customer_id']) ? 'is-invalid' : ''; ?>" required>
                        <option value="">-- Pilih Customer --</option>
                        <?php foreach ($customers as $customer): ?>
                            <option value="<?= $customer['id']; ?>" <?= (string) ($old['customer_id'] ?? '') === (string) $customer['id'] ? 'selected' : ''; ?>>
                                <?= htmlspecialchars($customer['name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <?php if (!empty($errors['customer_id'])): ?>
                        <div class="invalid-feedback"><?= $errors['customer_id']; ?></div>
                    <?php endif; ?>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Kendaraan</label>
                    <select name="vehicle_id" id="vehicle_id"
                        class="form-select <?= !empty($errors['vehicle_id']) ? 'is-invalid' : ''; ?>" required>
                        <option value="">-- Pilih Kendaraan --</option>
                    </select>
                    <?php if (!empty($errors['vehicle_id'])): ?>
                        <div class="invalid-feedback"><?= $errors['vehicle_id']; ?></div>
                    <?php endif; ?>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Jasa Utama</label>
                    <select name="service_id" id="service_id"
                        class="form-select <?= !empty($errors['service_id']) ? 'is-invalid' : ''; ?>" required>
                        <option value="">-- Pilih Jasa --</option>
                        <?php foreach ($services as $service): ?>
                            <?php if ((int) $service['is_active'] !== 1)
                                continue; ?>
                            <option value="<?= $service['id']; ?>" data-price="<?= (float) $service['base_price']; ?>"
                                <?= (string) ($old['service_id'] ?? '') === (string) $service['id'] ? 'selected' : ''; ?>>
                                <?= htmlspecialchars($service['name']); ?> - Rp
                                <?= number_format((float) $service['base_price'], 0, ',', '.'); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <?php if (!empty($errors['service_id'])): ?>
                        <div class="invalid-feedback"><?= $errors['service_id']; ?></div>
                    <?php endif; ?>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Estimasi Biaya Jasa</label>
                    <input type="number" step="0.01" name="estimated_service_price" id="estimated_service_price"
                        class="form-control <?= !empty($errors['estimated_service_price']) ? 'is-invalid' : ''; ?>"
                        value="<?= htmlspecialchars((string) ($old['estimated_service_price'] ?? '0')); ?>" required>
                    <?php if (!empty($errors['estimated_service_price'])): ?>
                        <div class="invalid-feedback"><?= $errors['estimated_service_price']; ?></div>
                    <?php endif; ?>
                </div>

                <div class="col-12">
                    <label class="form-label">Keluhan / Request Customer</label>
                    <textarea name="complaint" rows="4"
                        class="form-control"><?= htmlspecialchars($old['complaint'] ?? ''); ?></textarea>
                </div>

                <div class="col-12">
                    <label class="form-label">Catatan Internal</label>
                    <textarea name="internal_notes" rows="4"
                        class="form-control"><?= htmlspecialchars($old['internal_notes'] ?? ''); ?></textarea>
                </div>
            </div>

            <hr class="my-4">

            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h5 class="mb-1">Add-on Pekerjaan</h5>
                    <p class="text-muted mb-0">Tambahkan pekerjaan tambahan bila diperlukan.</p>
                </div>
                <button type="button" class="btn btn-outline-primary" id="btnAddAddonRow">
                    <i class="bi bi-plus-circle me-1"></i> Tambah Add-on
                </button>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered align-middle" id="addonsTable">
                    <thead class="table-light">
                        <tr>
                            <th>Nama Add-on</th>
                            <th width="120">Harga</th>
                            <th width="100">Qty</th>
                            <th width="140">Subtotal</th>
                            <th>Catatan</th>
                            <th width="80">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="addonsTableBody"></tbody>
                </table>
            </div>

            <div class="row g-3 mt-2">
                <div class="col-md-4 offset-md-8">
                    <label class="form-label">Total Add-on</label>
                    <input type="text" id="addons_total_display" class="form-control" value="0" readonly>
                </div>
                <div class="col-md-4 offset-md-8">
                    <label class="form-label">Grand Total Work Order</label>
                    <input type="text" id="grand_total_display" class="form-control fw-semibold" value="0" readonly>
                </div>
            </div>

            <div class="mt-4 d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save me-1"></i> Simpan
                </button>
                <a href="<?= BASE_URL; ?>workorder" class="btn btn-light border">Batal</a>
            </div>
        </form>
    </div>
</div>

<script>
    window.workOrderVehicles = <?= $vehiclesJson ?: '[]'; ?>;
    window.workOrderAddons = <?= $addonsJson ?: '[]'; ?>;
    window.workOrderOldVehicleId = "<?= htmlspecialchars((string) ($old['vehicle_id'] ?? '')); ?>";
    window.workOrderOldCustomerId = "<?= htmlspecialchars((string) ($old['customer_id'] ?? '')); ?>";
    window.workOrderOldServiceId = "<?= htmlspecialchars((string) ($old['service_id'] ?? '')); ?>";
    window.workOrderSelectedAddons = <?= json_encode($oldAddons ?? [], JSON_UNESCAPED_UNICODE); ?>;
</script>