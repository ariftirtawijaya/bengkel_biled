<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
    <div>
        <h1 class="h3 mb-1">Master Produk</h1>
        <p class="text-muted mb-0">Kelola semua barang, bahan baku, dan bahan pendukung.</p>
    </div>
    <div>
        <a href="<?= BASE_URL; ?>product/create" class="btn btn-primary">
            <i class="bi bi-plus-circle me-1"></i> Tambah Produk
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
            <table id="productsTable" class="table table-bordered table-hover align-middle mb-0"
                data-has-data="<?= !empty($products) ? '1' : '0'; ?>">
                <thead class="table-light">
                    <tr>
                        <th width="60">No</th>
                        <th>Kode</th>
                        <th>Nama Produk</th>
                        <th>Kategori</th>
                        <th>Satuan</th>
                        <th class="text-end">Harga Beli</th>
                        <th class="text-end">Margin %</th>
                        <th class="text-end">Harga Jual</th>
                        <th class="text-end">Stok</th>
                        <th class="text-end">Min Stok</th>
                        <th>Status</th>
                        <th width="180">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($products)): ?>
                        <?php foreach ($products as $index => $product): ?>
                            <?php
                            $isLowStock = (float) $product['stock'] <= (float) $product['min_stock'] && (float) $product['min_stock'] > 0;
                            ?>
                            <tr class="<?= $isLowStock ? 'table-warning' : ''; ?>">
                                <td><?= $index + 1; ?></td>
                                <td><?= htmlspecialchars($product['code']); ?></td>
                                <td>
                                    <?= htmlspecialchars($product['name']); ?>
                                    <?php if ($isLowStock): ?>
                                        <div><small class="text-danger fw-semibold">Stok menipis</small></div>
                                    <?php endif; ?>
                                </td>
                                <td><?= htmlspecialchars($product['category'] ?? '-'); ?></td>
                                <td><?= htmlspecialchars($product['unit']); ?></td>
                                <td class="text-end">Rp <?= number_format((float) $product['purchase_price'], 0, ',', '.'); ?>
                                </td>
                                <td class="text-end"><?= number_format((float) $product['margin_percent'], 2, ',', '.'); ?></td>
                                <td class="text-end">Rp <?= number_format((float) $product['selling_price'], 0, ',', '.'); ?>
                                </td>
                                <td class="text-end"><?= number_format((float) $product['stock'], 2, ',', '.'); ?></td>
                                <td class="text-end"><?= number_format((float) $product['min_stock'], 2, ',', '.'); ?></td>
                                <td>
                                    <?php if ((int) $product['is_active'] === 1): ?>
                                        <span class="badge bg-success">Aktif</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">Nonaktif</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <a href="<?= BASE_URL; ?>product/edit/<?= $product['id']; ?>"
                                            class="btn btn-sm btn-warning">
                                            Edit
                                        </a>
                                        <button type="button" class="btn btn-sm btn-danger btn-delete-product"
                                            data-url="<?= BASE_URL; ?>product/delete/<?= $product['id']; ?>"
                                            data-name="<?= htmlspecialchars($product['name']); ?>">
                                            Hapus
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="12" class="text-center text-muted py-4">
                                Belum ada data produk.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>