<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-1">Edit Produk</h1>
        <p class="text-muted mb-0">Ubah data produk.</p>
    </div>
    <div>
        <a href="<?= BASE_URL; ?>product" class="btn btn-secondary">
            <i class="bi bi-arrow-left me-1"></i> Kembali
        </a>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body">
        <form action="<?= BASE_URL; ?>product/update/<?= $product['id']; ?>" method="POST" id="productForm">
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Kode Produk</label>
                    <input type="text" name="code" class="form-control"
                        value="<?= htmlspecialchars($product['code']); ?>" required>
                </div>

                <div class="col-md-8">
                    <label class="form-label">Nama Produk</label>
                    <input type="text" name="name" class="form-control"
                        value="<?= htmlspecialchars($product['name']); ?>" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Kategori</label>
                    <input type="text" name="category" class="form-control"
                        value="<?= htmlspecialchars($product['category'] ?? ''); ?>">
                </div>

                <div class="col-md-2">
                    <label class="form-label">Satuan</label>
                    <input type="text" name="unit" class="form-control"
                        value="<?= htmlspecialchars($product['unit']); ?>" required>
                </div>

                <div class="col-md-2">
                    <label class="form-label">Stok</label>
                    <input type="number" step="0.01" name="stock" class="form-control"
                        value="<?= (float) $product['stock']; ?>">
                </div>

                <div class="col-md-2">
                    <label class="form-label">Min Stok</label>
                    <input type="number" step="0.01" name="min_stock" class="form-control"
                        value="<?= (float) $product['min_stock']; ?>">
                </div>

                <div class="col-md-2 d-flex align-items-end">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="is_active" id="is_active"
                            <?= (int) $product['is_active'] === 1 ? 'checked' : ''; ?>>
                        <label class="form-check-label" for="is_active">
                            Aktif
                        </label>
                    </div>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Harga Beli</label>
                    <input type="number" step="0.01" name="purchase_price" id="purchase_price" class="form-control"
                        value="<?= (float) $product['purchase_price']; ?>" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Margin (%)</label>
                    <input type="number" step="0.01" name="margin_percent" id="margin_percent" class="form-control"
                        value="<?= (float) $product['margin_percent']; ?>" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Harga Jual Otomatis</label>
                    <input type="number" step="0.01" name="selling_price_preview" id="selling_price_preview"
                        class="form-control" value="<?= (float) $product['selling_price']; ?>" readonly>
                    <small class="text-muted">Dihitung otomatis dari harga beli + margin.</small>
                </div>
            </div>

            <div class="mt-4 d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save me-1"></i> Update
                </button>
                <a href="<?= BASE_URL; ?>product" class="btn btn-light border">Batal</a>
            </div>
        </form>
    </div>
</div>