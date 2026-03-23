<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-1">Detail Work Order</h1>
        <p class="text-muted mb-0"><?= htmlspecialchars($workOrder['wo_number']); ?></p>
    </div>
    <div class="d-flex gap-2">
        <a href="<?= BASE_URL; ?>workorder/edit/<?= $workOrder['id']; ?>" class="btn btn-warning">Edit</a>
        <a href="<?= BASE_URL; ?>workorder" class="btn btn-secondary">Kembali</a>
    </div>
</div>

<div class="row g-3">
    <div class="col-md-6">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body">
                <h5 class="card-title">Informasi Work Order</h5>
                <table class="table table-sm mb-0">
                    <tr>
                        <th width="180">Nomor WO</th>
                        <td><?= htmlspecialchars($workOrder['wo_number']); ?></td>
                    </tr>
                    <tr>
                        <th>Tanggal Masuk</th>
                        <td><?= htmlspecialchars($workOrder['work_date']); ?></td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td><?= ucfirst(htmlspecialchars($workOrder['status'])); ?></td>
                    </tr>
                    <tr>
                        <th>Jasa</th>
                        <td><?= htmlspecialchars($workOrder['service_name']); ?></td>
                    </tr>
                    <tr>
                        <th>Biaya Jasa</th>
                        <td>Rp <?= number_format((float) $workOrder['estimated_service_price'], 0, ',', '.'); ?></td>
                    </tr>
                    <tr>
                        <th>Total Add-on</th>
                        <td>Rp <?= number_format((float) $workOrder['addons_total'], 0, ',', '.'); ?></td>
                    </tr>
                    <tr>
                        <th>Total Produk</th>
                        <td>Rp
                            <?= number_format((float) $workOrder['products_total'], 0, ',', '.'); ?>
                        </td>
                    </tr>
                    <tr>
                        <th>Grand Total</th>
                        <td class="fw-semibold">Rp <?= number_format((float) $workOrder['grand_total'], 0, ',', '.'); ?>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body">
                <h5 class="card-title">Customer & Kendaraan</h5>
                <table class="table table-sm mb-0">
                    <tr>
                        <th width="180">Customer</th>
                        <td><?= htmlspecialchars($workOrder['customer_name']); ?></td>
                    </tr>
                    <tr>
                        <th>No HP</th>
                        <td><?= htmlspecialchars($workOrder['customer_phone'] ?: '-'); ?></td>
                    </tr>
                    <tr>
                        <th>Kendaraan</th>
                        <td><?= htmlspecialchars($workOrder['brand'] . ' ' . $workOrder['model']); ?></td>
                    </tr>
                    <tr>
                        <th>Plat Nomor</th>
                        <td><?= htmlspecialchars($workOrder['plate_number'] ?: '-'); ?></td>
                    </tr>
                    <tr>
                        <th>Warna</th>
                        <td><?= htmlspecialchars($workOrder['color'] ?: '-'); ?></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h5 class="card-title">Keluhan / Request Customer</h5>
                <p class="mb-0"><?= nl2br(htmlspecialchars($workOrder['complaint'] ?: '-')); ?></p>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h5 class="card-title">Daftar Add-on</h5>
                <?php if (!empty($addons)): ?>
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Nama Add-on</th>
                                    <th class="text-end">Harga</th>
                                    <th class="text-end">Qty</th>
                                    <th class="text-end">Subtotal</th>
                                    <th>Catatan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($addons as $addon): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($addon['addon_name']); ?></td>
                                        <td class="text-end">Rp <?= number_format((float) $addon['price'], 0, ',', '.'); ?></td>
                                        <td class="text-end"><?= (int) $addon['qty']; ?></td>
                                        <td class="text-end">Rp <?= number_format((float) $addon['subtotal'], 0, ',', '.'); ?>
                                        </td>
                                        <td><?= htmlspecialchars($addon['notes'] ?: '-'); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <p class="mb-0 text-muted">Belum ada add-on.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h5 class="card-title">Daftar Produk</h5>
                <?php if (!empty($products)): ?>
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Kode</th>
                                    <th>Nama Produk</th>
                                    <th class="text-end">Harga</th>
                                    <th class="text-end">Qty</th>
                                    <th class="text-end">Subtotal</th>
                                    <th>Catatan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($products as $product): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($product['product_code'] ?: '-'); ?></td>
                                        <td><?= htmlspecialchars($product['product_name']); ?></td>
                                        <td class="text-end">Rp <?= number_format((float) $product['price'], 0, ',', '.'); ?>
                                        </td>
                                        <td class="text-end"><?= number_format((float) $product['qty'], 2, ',', '.'); ?></td>
                                        <td class="text-end">Rp <?= number_format((float) $product['subtotal'], 0, ',', '.'); ?>
                                        </td>
                                        <td><?= htmlspecialchars($product['notes'] ?: '-'); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <p class="mb-0 text-muted">Belum ada produk.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h5 class="card-title">Catatan Internal</h5>
                <p class="mb-0"><?= nl2br(htmlspecialchars($workOrder['internal_notes'] ?: '-')); ?></p>
            </div>
        </div>
    </div>
</div>