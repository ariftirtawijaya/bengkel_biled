<div class="d-flex justify-content-between align-items-start mb-4">
    <div>
        <h2 class="mb-1">Work Order / Invoice</h2>
        <div><strong>
                <?= APP_NAME; ?>
            </strong></div>
        <div>Sistem Internal Bengkel BILED</div>
    </div>
    <div class="text-end">
        <div><strong>No WO:</strong>
            <?= htmlspecialchars($workOrder['wo_number']); ?>
        </div>
        <div><strong>Tanggal:</strong>
            <?= htmlspecialchars($workOrder['work_date']); ?>
        </div>
        <div><strong>Status:</strong>
            <?= ucfirst(htmlspecialchars($workOrder['status'])); ?>
        </div>
    </div>
</div>

<div class="mb-3 no-print">
    <button class="btn btn-primary" onclick="window.print()">Print</button>
    <a href="<?= BASE_URL; ?>workorder/show/<?= $workOrder['id']; ?>" class="btn btn-secondary">Kembali</a>
</div>

<div class="invoice-box">
    <div class="section-title">Informasi Customer</div>
    <table class="table table-sm mb-0">
        <tr>
            <th width="180">Nama Customer</th>
            <td>
                <?= htmlspecialchars($workOrder['customer_name']); ?>
            </td>
        </tr>
        <tr>
            <th>No HP</th>
            <td>
                <?= htmlspecialchars($workOrder['customer_phone'] ?: '-'); ?>
            </td>
        </tr>
        <tr>
            <th>Alamat</th>
            <td>
                <?= nl2br(htmlspecialchars($workOrder['customer_address'] ?: '-')); ?>
            </td>
        </tr>
    </table>
</div>

<div class="invoice-box">
    <div class="section-title">Informasi Kendaraan</div>
    <table class="table table-sm mb-0">
        <tr>
            <th width="180">Kategori</th>
            <td>
                <?= ucfirst(htmlspecialchars($workOrder['vehicle_category'])); ?>
            </td>
        </tr>
        <tr>
            <th>Kendaraan</th>
            <td>
                <?= htmlspecialchars($workOrder['brand'] . ' ' . $workOrder['model']); ?>
            </td>
        </tr>
        <tr>
            <th>Tahun</th>
            <td>
                <?= htmlspecialchars($workOrder['year'] ?: '-'); ?>
            </td>
        </tr>
        <tr>
            <th>Plat Nomor</th>
            <td>
                <?= htmlspecialchars($workOrder['plate_number'] ?: '-'); ?>
            </td>
        </tr>
        <tr>
            <th>Warna</th>
            <td>
                <?= htmlspecialchars($workOrder['color'] ?: '-'); ?>
            </td>
        </tr>
    </table>
</div>

<div class="invoice-box">
    <div class="section-title">Keluhan / Request</div>
    <p class="mb-0">
        <?= nl2br(htmlspecialchars($workOrder['complaint'] ?: '-')); ?>
    </p>
</div>

<div class="invoice-box">
    <div class="section-title">Jasa Utama</div>
    <table class="table table-bordered mb-0">
        <thead class="table-light">
            <tr>
                <th>Nama Jasa</th>
                <th class="text-end" width="180">Biaya</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>
                    <?= htmlspecialchars($workOrder['service_name']); ?>
                </td>
                <td class="text-end">Rp
                    <?= number_format((float) $workOrder['estimated_service_price'], 0, ',', '.'); ?>
                </td>
            </tr>
        </tbody>
    </table>
</div>

<div class="invoice-box">
    <div class="section-title">Add-on Pekerjaan</div>
    <?php if (!empty($addons)): ?>
        <table class="table table-bordered mb-0">
            <thead class="table-light">
                <tr>
                    <th>Nama Add-on</th>
                    <th class="text-end" width="120">Harga</th>
                    <th class="text-end" width="80">Qty</th>
                    <th class="text-end" width="140">Subtotal</th>
                    <th>Catatan</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($addons as $addon): ?>
                    <tr>
                        <td>
                            <?= htmlspecialchars($addon['addon_name']); ?>
                        </td>
                        <td class="text-end">Rp
                            <?= number_format((float) $addon['price'], 0, ',', '.'); ?>
                        </td>
                        <td class="text-end">
                            <?= (int) $addon['qty']; ?>
                        </td>
                        <td class="text-end">Rp
                            <?= number_format((float) $addon['subtotal'], 0, ',', '.'); ?>
                        </td>
                        <td>
                            <?= htmlspecialchars($addon['notes'] ?: '-'); ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p class="mb-0 text-muted">Belum ada add-on.</p>
    <?php endif; ?>
</div>

<div class="invoice-box">
    <div class="section-title">Produk / Barang</div>
    <?php if (!empty($products)): ?>
        <table class="table table-bordered mb-0">
            <thead class="table-light">
                <tr>
                    <th>Kode</th>
                    <th>Nama Produk</th>
                    <th class="text-end" width="120">Harga</th>
                    <th class="text-end" width="90">Qty</th>
                    <th class="text-end" width="140">Subtotal</th>
                    <th>Catatan</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($products as $product): ?>
                    <tr>
                        <td>
                            <?= htmlspecialchars($product['product_code'] ?: '-'); ?>
                        </td>
                        <td>
                            <?= htmlspecialchars($product['product_name']); ?>
                        </td>
                        <td class="text-end">Rp
                            <?= number_format((float) $product['price'], 0, ',', '.'); ?>
                        </td>
                        <td class="text-end">
                            <?= number_format((float) $product['qty'], 2, ',', '.'); ?>
                        </td>
                        <td class="text-end">Rp
                            <?= number_format((float) $product['subtotal'], 0, ',', '.'); ?>
                        </td>
                        <td>
                            <?= htmlspecialchars($product['notes'] ?: '-'); ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p class="mb-0 text-muted">Belum ada produk.</p>
    <?php endif; ?>
</div>

<div class="invoice-box">
    <div class="section-title">Ringkasan Biaya</div>
    <table class="table table-sm mb-0">
        <tr>
            <th width="220">Biaya Jasa</th>
            <td class="text-end">Rp
                <?= number_format((float) $workOrder['estimated_service_price'], 0, ',', '.'); ?>
            </td>
        </tr>
        <tr>
            <th>Total Add-on</th>
            <td class="text-end">Rp
                <?= number_format((float) $workOrder['addons_total'], 0, ',', '.'); ?>
            </td>
        </tr>
        <tr>
            <th>Total Produk</th>
            <td class="text-end">Rp
                <?= number_format((float) $workOrder['products_total'], 0, ',', '.'); ?>
            </td>
        </tr>
        <tr>
            <th class="fs-5">Grand Total</th>
            <td class="text-end fs-5 fw-bold">Rp
                <?= number_format((float) $workOrder['grand_total'], 0, ',', '.'); ?>
            </td>
        </tr>
    </table>
</div>

<div class="invoice-box">
    <div class="section-title">Catatan Internal</div>
    <p class="mb-0">
        <?= nl2br(htmlspecialchars($workOrder['internal_notes'] ?: '-')); ?>
    </p>
</div>