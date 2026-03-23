<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-1">Edit Work Order</h1>
        <p class="text-muted mb-0">Ubah data work order.</p>
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
        <form action="<?= BASE_URL; ?>workorder/update/<?= $workOrder['id']; ?>" method="POST">
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Nomor WO</label>
                    <input type="text" class="form-control" value="<?= htmlspecialchars($workOrder['wo_number']); ?>"
                        readonly>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Tanggal Masuk</label>
                    <input type="date" name="work_date"
                        class="form-control <?= !empty($errors['work_date']) ? 'is-invalid' : ''; ?>"
                        value="<?= htmlspecialchars($workOrder['work_date']); ?>" required>
                    <?php if (!empty($errors['work_date'])): ?>
                        <div class="invalid-feedback">
                            <?= $errors['work_date']; ?>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select <?= !empty($errors['status']) ? 'is-invalid' : ''; ?>"
                        required>
                        <option value="pending" <?= $workOrder['status'] === 'pending' ? 'selected' : ''; ?>>Pending
                        </option>
                        <option value="antri" <?= $workOrder['status'] === 'antri' ? 'selected' : ''; ?>>Antri</option>
                        <option value="progress" <?= $workOrder['status'] === 'progress' ? 'selected' : ''; ?>>Progress
                        </option>
                        <option value="done" <?= $workOrder['status'] === 'done' ? 'selected' : ''; ?>>Done</option>
                        <option value="cancelled" <?= $workOrder['status'] === 'cancelled' ? 'selected' : ''; ?>>Cancelled
                        </option>
                    </select>
                    <?php if (!empty($errors['status'])): ?>
                        <div class="invalid-feedback">
                            <?= $errors['status']; ?>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Customer</label>
                    <select name="customer_id"
                        class="form-select <?= !empty($errors['customer_id']) ? 'is-invalid' : ''; ?>" required>
                        <option value="">-- Pilih Customer --</option>
                        <?php foreach ($customers as $customer): ?>
                            <option value="<?= $customer['id']; ?>"
                                <?= (string) $workOrder['customer_id'] === (string) $customer['id'] ? 'selected' : ''; ?>>
                                <?= htmlspecialchars($customer['name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <?php if (!empty($errors['customer_id'])): ?>
                        <div class="invalid-feedback">
                            <?= $errors['customer_id']; ?>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Kendaraan</label>
                    <select name="vehicle_id"
                        class="form-select <?= !empty($errors['vehicle_id']) ? 'is-invalid' : ''; ?>" required>
                        <option value="">-- Pilih Kendaraan --</option>
                        <?php foreach ($vehicles as $vehicle): ?>
                            <option value="<?= $vehicle['id']; ?>"
                                <?= (string) $workOrder['vehicle_id'] === (string) $vehicle['id'] ? 'selected' : ''; ?>>
                                <?= htmlspecialchars($vehicle['brand'] . ' ' . $vehicle['model'] . ' - ' . ($vehicle['plate_number'] ?: '-')); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <?php if (!empty($errors['vehicle_id'])): ?>
                        <div class="invalid-feedback">
                            <?= $errors['vehicle_id']; ?>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Jasa Utama</label>
                    <select name="service_id"
                        class="form-select <?= !empty($errors['service_id']) ? 'is-invalid' : ''; ?>" required>
                        <option value="">-- Pilih Jasa --</option>
                        <?php foreach ($services as $service): ?>
                            <option value="<?= $service['id']; ?>"
                                <?= (string) $workOrder['service_id'] === (string) $service['id'] ? 'selected' : ''; ?>>
                                <?= htmlspecialchars($service['name']); ?> - Rp
                                <?= number_format((float) $service['base_price'], 0, ',', '.'); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <?php if (!empty($errors['service_id'])): ?>
                        <div class="invalid-feedback">
                            <?= $errors['service_id']; ?>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Estimasi Biaya Jasa</label>
                    <input type="number" step="0.01" name="estimated_service_price"
                        class="form-control <?= !empty($errors['estimated_service_price']) ? 'is-invalid' : ''; ?>"
                        value="<?= (float) $workOrder['estimated_service_price']; ?>" required>
                    <?php if (!empty($errors['estimated_service_price'])): ?>
                        <div class="invalid-feedback">
                            <?= $errors['estimated_service_price']; ?>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="col-12">
                    <label class="form-label">Keluhan / Request Customer</label>
                    <textarea name="complaint" rows="4"
                        class="form-control"><?= htmlspecialchars($workOrder['complaint'] ?? ''); ?></textarea>
                </div>

                <div class="col-12">
                    <label class="form-label">Catatan Internal</label>
                    <textarea name="internal_notes" rows="4"
                        class="form-control"><?= htmlspecialchars($workOrder['internal_notes'] ?? ''); ?></textarea>
                </div>
            </div>

            <div class="mt-4 d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save me-1"></i> Update
                </button>
                <a href="<?= BASE_URL; ?>workorder" class="btn btn-light border">Batal</a>
            </div>
        </form>
    </div>
</div>