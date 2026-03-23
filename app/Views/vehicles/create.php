<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-1">Tambah Kendaraan</h1>
        <p class="text-muted mb-0">Tambahkan kendaraan milik customer.</p>
    </div>
    <div>
        <a href="<?= BASE_URL; ?>vehicle" class="btn btn-secondary">
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
        <form action="<?= BASE_URL; ?>vehicle/store" method="POST">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Customer</label>
                    <select name="customer_id"
                        class="form-select <?= !empty($errors['customer_id']) ? 'is-invalid' : ''; ?>" required>
                        <option value="">-- Pilih Customer --</option>
                        <?php foreach ($customers as $customer): ?>
                            <option value="<?= $customer['id']; ?>" <?= (string) ($old['customer_id'] ?? '') === (string) $customer['id'] ? 'selected' : ''; ?>>
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
                    <label class="form-label">Kategori Kendaraan</label>
                    <select name="category" class="form-select <?= !empty($errors['category']) ? 'is-invalid' : ''; ?>"
                        required>
                        <option value="">-- Pilih Kategori --</option>
                        <option value="motor" <?= ($old['category'] ?? '') === 'motor' ? 'selected' : ''; ?>>Motor
                        </option>
                        <option value="mobil" <?= ($old['category'] ?? '') === 'mobil' ? 'selected' : ''; ?>>Mobil
                        </option>
                    </select>
                    <?php if (!empty($errors['category'])): ?>
                        <div class="invalid-feedback">
                            <?= $errors['category']; ?>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Merk</label>
                    <input type="text" name="brand"
                        class="form-control <?= !empty($errors['brand']) ? 'is-invalid' : ''; ?>"
                        value="<?= htmlspecialchars($old['brand'] ?? ''); ?>"
                        placeholder="Contoh: Honda, Yamaha, Toyota" required>
                    <?php if (!empty($errors['brand'])): ?>
                        <div class="invalid-feedback">
                            <?= $errors['brand']; ?>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Model</label>
                    <input type="text" name="model"
                        class="form-control <?= !empty($errors['model']) ? 'is-invalid' : ''; ?>"
                        value="<?= htmlspecialchars($old['model'] ?? ''); ?>"
                        placeholder="Contoh: Vario LED New, Avanza" required>
                    <?php if (!empty($errors['model'])): ?>
                        <div class="invalid-feedback">
                            <?= $errors['model']; ?>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Tahun</label>
                    <input type="text" name="year" class="form-control"
                        value="<?= htmlspecialchars($old['year'] ?? ''); ?>" placeholder="Contoh: 2021">
                </div>

                <div class="col-md-6">
                    <label class="form-label">Plat Nomor</label>
                    <input type="text" name="plate_number" class="form-control"
                        value="<?= htmlspecialchars($old['plate_number'] ?? ''); ?>" placeholder="Contoh: F 1234 XX">
                </div>

                <div class="col-md-6">
                    <label class="form-label">Warna</label>
                    <input type="text" name="color" class="form-control"
                        value="<?= htmlspecialchars($old['color'] ?? ''); ?>" placeholder="Contoh: Hitam">
                </div>

                <div class="col-12">
                    <label class="form-label">Catatan</label>
                    <textarea name="notes" rows="4"
                        class="form-control"><?= htmlspecialchars($old['notes'] ?? ''); ?></textarea>
                </div>
            </div>

            <div class="mt-4 d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save me-1"></i> Simpan
                </button>
                <a href="<?= BASE_URL; ?>vehicle" class="btn btn-light border">Batal</a>
            </div>
        </form>
    </div>
</div>