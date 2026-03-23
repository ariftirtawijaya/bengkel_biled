<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-1">Tambah Produk</h1>
        <p class="text-muted mb-0">Tambahkan produk baru ke master produk.</p>
    </div>
    <div>
        <a href="<?= BASE_URL; ?>product" class="btn btn-secondary">
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
        <form action="<?= BASE_URL; ?>product/store" method="POST" id="productForm">
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Kode Produk</label>
                    <input type="text" name="code"
                        class="form-control <?= !empty($errors['code']) ? 'is-invalid' : ''; ?>"
                        value="<?= htmlspecialchars($old['code'] ?? $product_code); ?>" required>
                    <?php if (!empty($errors['code'])): ?>
                        <div class="invalid-feedback"><?= $errors['code']; ?></div>
                    <?php endif; ?>
                </div>

                <div class="col-md-8">
                    <label class="form-label">Nama Produk</label>
                    <input type="text" name="name"
                        class="form-control <?= !empty($errors['name']) ? 'is-invalid' : ''; ?>"
                        value="<?= htmlspecialchars($old['name'] ?? ''); ?>" required>
                    <?php if (!empty($errors['name'])): ?>
                        <div class="invalid-feedback"><?= $errors['name']; ?></div>
                    <?php endif; ?>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Kategori</label>
                    <input type="text" name="category" class="form-control"
                        placeholder="Contoh: BILED, Chemical, Saklar"
                        value="<?= htmlspecialchars($old['category'] ?? ''); ?>">
                </div>

                <div class="col-md-2">
                    <label class="form-label">Satuan</label>
                    <input type="text" name="unit"
                        class="form-control <?= !empty($errors['unit']) ? 'is-invalid' : ''; ?>"
                        value="<?= htmlspecialchars($old['unit'] ?? 'pcs'); ?>" required>
                    <?php if (!empty($errors['unit'])): ?>
                        <div class="invalid-feedback"><?= $errors['unit']; ?></div>
                    <?php endif; ?>
                </div>

                <div class="col-md-2">
                    <label class="form-label">Stok Awal</label>
                    <input type="number" step="0.01" name="stock"
                        class="form-control <?= !empty($errors['stock']) ? 'is-invalid' : ''; ?>"
                        value="<?= htmlspecialchars((string) ($old['stock'] ?? '0')); ?>">
                    <?php if (!empty($errors['stock'])): ?>
                        <div class="invalid-feedback"><?= $errors['stock']; ?></div>
                    <?php endif; ?>
                </div>

                <div class="col-md-2">
                    <label class="form-label">Min Stok</label>
                    <input type="number" step="0.01" name="min_stock"
                        class="form-control <?= !empty($errors['min_stock']) ? 'is-invalid' : ''; ?>"
                        value="<?= htmlspecialchars((string) ($old['min_stock'] ?? '0')); ?>">
                    <?php if (!empty($errors['min_stock'])): ?>
                        <div class="invalid-feedback"><?= $errors['min_stock']; ?></div>
                    <?php endif; ?>
                </div>

                <div class="col-md-2 d-flex align-items-end">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="is_active" id="is_active"
                            <?= isset($old['is_active']) ? ((int) $old['is_active'] === 1 ? 'checked' : '') : 'checked'; ?>>
                        <label class="form-check-label" for="is_active">
                            Aktif
                        </label>
                    </div>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Harga Beli</label>
                    <input type="number" step="0.01" name="purchase_price" id="purchase_price"
                        class="form-control <?= !empty($errors['purchase_price']) ? 'is-invalid' : ''; ?>"
                        value="<?= htmlspecialchars((string) ($old['purchase_price'] ?? '0')); ?>" required>
                    <?php if (!empty($errors['purchase_price'])): ?>
                        <div class="invalid-feedback"><?= $errors['purchase_price']; ?></div>
                    <?php endif; ?>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Margin (%)</label>
                    <input type="number" step="0.01" name="margin_percent" id="margin_percent"
                        class="form-control <?= !empty($errors['margin_percent']) ? 'is-invalid' : ''; ?>"
                        value="<?= htmlspecialchars((string) ($old['margin_percent'] ?? '0')); ?>" required>
                    <?php if (!empty($errors['margin_percent'])): ?>
                        <div class="invalid-feedback"><?= $errors['margin_percent']; ?></div>
                    <?php endif; ?>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Harga Jual Otomatis</label>
                    <input type="number" step="0.01" name="selling_price_preview" id="selling_price_preview"
                        class="form-control" value="0" readonly>
                    <small class="text-muted">Dihitung otomatis dari harga beli + margin.</small>
                </div>
            </div>

            <div class="mt-4 d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save me-1"></i> Simpan
                </button>
                <a href="<?= BASE_URL; ?>product" class="btn btn-light border">Batal</a>
            </div>
        </form>
    </div>
</div>