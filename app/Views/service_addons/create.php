<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-1">Tambah Add-on Pekerjaan</h1>
        <p class="text-muted mb-0">Tambahkan pekerjaan tambahan bengkel.</p>
    </div>
    <div>
        <a href="<?= BASE_URL; ?>serviceaddon" class="btn btn-secondary">
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
        <form action="<?= BASE_URL; ?>serviceaddon/store" method="POST">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Nama Add-on</label>
                    <input type="text" name="name"
                        class="form-control <?= !empty($errors['name']) ? 'is-invalid' : ''; ?>"
                        value="<?= htmlspecialchars($old['name'] ?? ''); ?>" required>
                    <?php if (!empty($errors['name'])): ?>
                        <div class="invalid-feedback">
                            <?= $errors['name']; ?>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="col-md-3">
                    <label class="form-label">Kategori Add-on</label>
                    <input type="text" name="addon_category" class="form-control"
                        value="<?= htmlspecialchars($old['addon_category'] ?? ''); ?>"
                        placeholder="Contoh: Wiring, Saklar, Bodywork">
                </div>

                <div class="col-md-3">
                    <label class="form-label">Kategori Kendaraan</label>
                    <select name="vehicle_category"
                        class="form-select <?= !empty($errors['vehicle_category']) ? 'is-invalid' : ''; ?>" required>
                        <option value="">-- Pilih --</option>
                        <option value="motor" <?= ($old['vehicle_category'] ?? '') === 'motor' ? 'selected' : ''; ?>
                            >Motor</option>
                        <option value="mobil" <?= ($old['vehicle_category'] ?? '') === 'mobil' ? 'selected' : ''; ?>
                            >Mobil</option>
                        <option value="umum" <?= ($old['vehicle_category'] ?? '') === 'umum' ? 'selected' : ''; ?>>Umum
                        </option>
                    </select>
                    <?php if (!empty($errors['vehicle_category'])): ?>
                        <div class="invalid-feedback">
                            <?= $errors['vehicle_category']; ?>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Harga Add-on</label>
                    <input type="number" step="0.01" name="price"
                        class="form-control <?= !empty($errors['price']) ? 'is-invalid' : ''; ?>"
                        value="<?= htmlspecialchars((string) ($old['price'] ?? '0')); ?>" required>
                    <?php if (!empty($errors['price'])): ?>
                        <div class="invalid-feedback">
                            <?= $errors['price']; ?>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Estimasi Durasi (menit)</label>
                    <input type="number" name="estimated_minutes"
                        class="form-control <?= !empty($errors['estimated_minutes']) ? 'is-invalid' : ''; ?>"
                        value="<?= htmlspecialchars((string) ($old['estimated_minutes'] ?? '0')); ?>" required>
                    <?php if (!empty($errors['estimated_minutes'])): ?>
                        <div class="invalid-feedback">
                            <?= $errors['estimated_minutes']; ?>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="col-md-4 d-flex align-items-end">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="is_active" id="is_active"
                            <?= isset($old['is_active']) ? ((int) $old['is_active'] === 1 ? 'checked' : '') : 'checked'; ?>>
                        <label class="form-check-label" for="is_active">
                            Aktif
                        </label>
                    </div>
                </div>

                <div class="col-12">
                    <label class="form-label">Deskripsi</label>
                    <textarea name="description" rows="4"
                        class="form-control"><?= htmlspecialchars($old['description'] ?? ''); ?></textarea>
                </div>
            </div>

            <div class="mt-4 d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save me-1"></i> Simpan
                </button>
                <a href="<?= BASE_URL; ?>serviceaddon" class="btn btn-light border">Batal</a>
            </div>
        </form>
    </div>
</div>