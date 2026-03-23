<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-1">Tambah Customer</h1>
        <p class="text-muted mb-0">Tambahkan customer baru.</p>
    </div>
    <div>
        <a href="<?= BASE_URL; ?>customer" class="btn btn-secondary">
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
        <form action="<?= BASE_URL; ?>customer/store" method="POST">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Nama Customer</label>
                    <input type="text" name="name"
                        class="form-control <?= !empty($errors['name']) ? 'is-invalid' : ''; ?>"
                        value="<?= htmlspecialchars($old['name'] ?? ''); ?>" required>
                    <?php if (!empty($errors['name'])): ?>
                        <div class="invalid-feedback">
                            <?= $errors['name']; ?>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="col-md-6">
                    <label class="form-label">No HP</label>
                    <input type="text" name="phone"
                        class="form-control <?= !empty($errors['phone']) ? 'is-invalid' : ''; ?>"
                        value="<?= htmlspecialchars($old['phone'] ?? ''); ?>" placeholder="Contoh: 081234567890">
                    <?php if (!empty($errors['phone'])): ?>
                        <div class="invalid-feedback">
                            <?= $errors['phone']; ?>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="col-12">
                    <label class="form-label">Alamat</label>
                    <textarea name="address" rows="4"
                        class="form-control"><?= htmlspecialchars($old['address'] ?? ''); ?></textarea>
                </div>
            </div>

            <div class="mt-4 d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save me-1"></i> Simpan
                </button>
                <a href="<?= BASE_URL; ?>customer" class="btn btn-light border">Batal</a>
            </div>
        </form>
    </div>
</div>