$(document).ready(function () {
    function calculateSellingPrice() {
        const purchasePrice = parseFloat($('#purchase_price').val()) || 0;
        const marginPercent = parseFloat($('#margin_percent').val()) || 0;
        const sellingPrice = purchasePrice + (purchasePrice * marginPercent / 100);

        $('#selling_price_preview').val(sellingPrice.toFixed(2));
    }

    $(document).on('input', '#purchase_price, #margin_percent', function () {
        calculateSellingPrice();
    });

    calculateSellingPrice();

    const dtLanguage = {
        search: 'Cari:',
        lengthMenu: 'Tampilkan _MENU_ data',
        info: 'Menampilkan _START_ sampai _END_ dari _TOTAL_ data',
        infoEmpty: 'Tidak ada data',
        zeroRecords: 'Data tidak ditemukan',
        paginate: {
            first: 'Awal',
            last: 'Akhir',
            next: '›',
            previous: '‹'
        }
    };

    function initDataTable(selector) {
        const $table = $(selector);
        if ($table.length && $table.data('has-data') == 1) {
            $table.DataTable({
                responsive: true,
                pageLength: 10,
                order: [],
                language: dtLanguage
            });
        }
    }

    initDataTable('#productsTable');
    initDataTable('#customersTable');
    initDataTable('#vehiclesTable');
    initDataTable('#servicesTable');
    initDataTable('#serviceAddonsTable');
    initDataTable('#workOrdersTable');

    function confirmDelete(buttonSelector, titleText, entityLabel) {
        $(document).on('click', buttonSelector, function () {
            const url = $(this).data('url');
            const name = $(this).data('name');

            Swal.fire({
                title: titleText,
                html: `${entityLabel} <b>${name}</b> akan dihapus.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, hapus',
                cancelButtonText: 'Batal',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) window.location.href = url;
            });
        });
    }

    confirmDelete('.btn-delete-product', 'Hapus produk?', 'Produk');
    confirmDelete('.btn-delete-customer', 'Hapus customer?', 'Customer');
    confirmDelete('.btn-delete-vehicle', 'Hapus kendaraan?', 'Kendaraan');
    confirmDelete('.btn-delete-service', 'Hapus jasa?', 'Jasa');
    confirmDelete('.btn-delete-addon', 'Hapus add-on?', 'Add-on');
    confirmDelete('.btn-delete-workorder', 'Hapus work order?', 'Work order');

    function initWorkOrderEnhancements() {
        const $customer = $('#customer_id');
        const $vehicle = $('#vehicle_id');
        const $service = $('#service_id');
        const $estimated = $('#estimated_service_price');

        const $addonsBody = $('#addonsTableBody');
        const $btnAddAddon = $('#btnAddAddonRow');

        const $productsBody = $('#productsWorkOrderTableBody');
        const $btnAddProduct = $('#btnAddProductRow');

        const $addonsTotal = $('#addons_total_display');
        const $productsTotal = $('#products_total_display');
        const $grandTotal = $('#grand_total_display');

        if (!$customer.length || !$vehicle.length) {
            return;
        }

        const vehicles = Array.isArray(window.workOrderVehicles) ? window.workOrderVehicles : [];
        const addonsMaster = Array.isArray(window.workOrderAddons) ? window.workOrderAddons : [];
        const productsMaster = Array.isArray(window.workOrderProducts) ? window.workOrderProducts : [];
        const selectedAddons = Array.isArray(window.workOrderSelectedAddons) ? window.workOrderSelectedAddons : [];
        const selectedProducts = Array.isArray(window.workOrderSelectedProducts) ? window.workOrderSelectedProducts : [];
        const oldVehicleId = window.workOrderOldVehicleId || '';

        function formatNumber(num) {
            return new Intl.NumberFormat('id-ID', {
                minimumFractionDigits: 0,
                maximumFractionDigits: 0
            }).format(parseFloat(num || 0));
        }

        function escapeHtml(text) {
            return $('<div>').text(text ?? '').html();
        }

        function buildVehicleLabel(vehicle) {
            const plate = vehicle.plate_number && vehicle.plate_number !== '' ? vehicle.plate_number : '-';
            return `${vehicle.brand} ${vehicle.model} - ${plate}`;
        }

        function renderVehicleOptions(customerId, selectedVehicleId = '') {
            $vehicle.empty();
            $vehicle.append('<option value="">-- Pilih Kendaraan --</option>');

            if (!customerId) return;

            const filtered = vehicles.filter(vehicle => String(vehicle.customer_id) === String(customerId));

            filtered.forEach(function (vehicle) {
                const isSelected = String(selectedVehicleId) === String(vehicle.id) ? 'selected' : '';
                $vehicle.append(`<option value="${vehicle.id}" ${isSelected}>${buildVehicleLabel(vehicle)}</option>`);
            });
        }

        function fillEstimatedPriceFromService() {
            const price = parseFloat($service.find(':selected').data('price')) || 0;
            $estimated.val(price.toFixed(2));
            calculateWorkOrderTotals();
        }

        function buildAddonOptions(selectedId = '') {
            let html = '<option value="">-- Pilih Add-on --</option>';
            addonsMaster.forEach(function (addon) {
                if (parseInt(addon.is_active) !== 1) return;
                const selected = String(selectedId) === String(addon.id) ? 'selected' : '';
                html += `<option value="${addon.id}" data-name="${escapeHtml(addon.name)}" data-price="${addon.price}" ${selected}>${escapeHtml(addon.name)} - Rp ${formatNumber(addon.price)}</option>`;
            });
            return html;
        }

        function buildProductOptions(selectedId = '') {
            let html = '<option value="">-- Pilih Produk --</option>';
            productsMaster.forEach(function (product) {
                if (parseInt(product.is_active) !== 1) return;
                const selected = String(selectedId) === String(product.id) ? 'selected' : '';
                html += `<option value="${product.id}" data-code="${escapeHtml(product.code)}" data-name="${escapeHtml(product.name)}" data-price="${product.selling_price}" ${selected}>${escapeHtml(product.name)} - Rp ${formatNumber(product.selling_price)}</option>`;
            });
            return html;
        }

        function createAddonRow(addon = null) {
            const addonId = addon ? addon.addon_id : '';
            const addonName = addon ? addon.addon_name : '';
            const addonPrice = addon ? parseFloat(addon.price) || 0 : 0;
            const addonQty = addon ? parseInt(addon.qty) || 1 : 1;
            const addonSubtotal = addon ? parseFloat(addon.subtotal) || (addonPrice * addonQty) : 0;
            const addonNotes = addon ? (addon.notes || '') : '';

            const rowHtml = `
                <tr>
                    <td>
                        <select name="addon_id[]" class="form-select addon-select">
                            ${buildAddonOptions(addonId)}
                        </select>
                        <input type="hidden" name="addon_name[]" class="addon-name-hidden" value="${escapeHtml(addonName)}">
                    </td>
                    <td><input type="number" step="0.01" name="addon_price[]" class="form-control addon-price" value="${addonPrice}"></td>
                    <td><input type="number" name="addon_qty[]" class="form-control addon-qty" value="${addonQty}" min="1"></td>
                    <td><input type="number" step="0.01" name="addon_subtotal[]" class="form-control addon-subtotal" value="${addonSubtotal}" readonly></td>
                    <td><input type="text" name="addon_notes[]" class="form-control" value="${escapeHtml(addonNotes)}"></td>
                    <td class="text-center"><button type="button" class="btn btn-sm btn-outline-danger btn-remove-addon">Hapus</button></td>
                </tr>
            `;
            $addonsBody.append(rowHtml);
        }

        function createProductRow(product = null) {
            const productId = product ? product.product_id : '';
            const productCode = product ? product.product_code : '';
            const productName = product ? product.product_name : '';
            const productPrice = product ? parseFloat(product.price) || 0 : 0;
            const productQty = product ? parseFloat(product.qty) || 1 : 1;
            const productSubtotal = product ? parseFloat(product.subtotal) || (productPrice * productQty) : 0;
            const productNotes = product ? (product.notes || '') : '';

            const rowHtml = `
                <tr>
                    <td>
                        <select name="product_id[]" class="form-select product-select">
                            ${buildProductOptions(productId)}
                        </select>
                        <input type="hidden" name="product_code[]" class="product-code-hidden" value="${escapeHtml(productCode)}">
                        <input type="hidden" name="product_name[]" class="product-name-hidden" value="${escapeHtml(productName)}">
                    </td>
                    <td><input type="number" step="0.01" name="product_price[]" class="form-control product-price" value="${productPrice}"></td>
                    <td><input type="number" step="0.01" name="product_qty[]" class="form-control product-qty" value="${productQty}" min="0.01"></td>
                    <td><input type="number" step="0.01" name="product_subtotal[]" class="form-control product-subtotal" value="${productSubtotal}" readonly></td>
                    <td><input type="text" name="product_notes[]" class="form-control" value="${escapeHtml(productNotes)}"></td>
                    <td class="text-center"><button type="button" class="btn btn-sm btn-outline-danger btn-remove-product">Hapus</button></td>
                </tr>
            `;
            $productsBody.append(rowHtml);
        }

        function updateAddonRowFromSelect($row) {
            const $selected = $row.find('.addon-select option:selected');
            const addonName = $selected.data('name') || '';
            const addonPrice = parseFloat($selected.data('price')) || 0;
            const qty = parseInt($row.find('.addon-qty').val()) || 1;

            $row.find('.addon-name-hidden').val(addonName);
            $row.find('.addon-price').val(addonPrice.toFixed(2));
            $row.find('.addon-subtotal').val((addonPrice * qty).toFixed(2));

            calculateWorkOrderTotals();
        }

        function updateProductRowFromSelect($row) {
            const $selected = $row.find('.product-select option:selected');
            const code = $selected.data('code') || '';
            const name = $selected.data('name') || '';
            const price = parseFloat($selected.data('price')) || 0;
            const qty = parseFloat($row.find('.product-qty').val()) || 1;

            $row.find('.product-code-hidden').val(code);
            $row.find('.product-name-hidden').val(name);
            $row.find('.product-price').val(price.toFixed(2));
            $row.find('.product-subtotal').val((price * qty).toFixed(2));

            calculateWorkOrderTotals();
        }

        function updateAddonSubtotal($row) {
            const price = parseFloat($row.find('.addon-price').val()) || 0;
            const qty = parseInt($row.find('.addon-qty').val()) || 0;
            $row.find('.addon-subtotal').val((price * qty).toFixed(2));
            calculateWorkOrderTotals();
        }

        function updateProductSubtotal($row) {
            const price = parseFloat($row.find('.product-price').val()) || 0;
            const qty = parseFloat($row.find('.product-qty').val()) || 0;
            $row.find('.product-subtotal').val((price * qty).toFixed(2));
            calculateWorkOrderTotals();
        }

        function calculateWorkOrderTotals() {
            let addonsTotal = 0;
            let productsTotal = 0;

            $addonsBody.find('tr').each(function () {
                addonsTotal += parseFloat($(this).find('.addon-subtotal').val()) || 0;
            });

            $productsBody.find('tr').each(function () {
                productsTotal += parseFloat($(this).find('.product-subtotal').val()) || 0;
            });

            const serviceTotal = parseFloat($estimated.val()) || 0;
            const grandTotal = serviceTotal + addonsTotal + productsTotal;

            $addonsTotal.val(formatNumber(addonsTotal));
            $productsTotal.val(formatNumber(productsTotal));
            $grandTotal.val(formatNumber(grandTotal));
        }

        $customer.on('change', function () {
            renderVehicleOptions($(this).val(), '');
        });

        $service.on('change', function () {
            fillEstimatedPriceFromService();
        });

        $estimated.on('input', function () {
            calculateWorkOrderTotals();
        });

        $btnAddAddon.on('click', function () {
            createAddonRow();
        });

        $btnAddProduct.on('click', function () {
            createProductRow();
        });

        $(document).on('change', '.addon-select', function () {
            updateAddonRowFromSelect($(this).closest('tr'));
        });

        $(document).on('change', '.product-select', function () {
            updateProductRowFromSelect($(this).closest('tr'));
        });

        $(document).on('input', '.addon-price, .addon-qty', function () {
            updateAddonSubtotal($(this).closest('tr'));
        });

        $(document).on('input', '.product-price, .product-qty', function () {
            updateProductSubtotal($(this).closest('tr'));
        });

        $(document).on('click', '.btn-remove-addon', function () {
            $(this).closest('tr').remove();
            calculateWorkOrderTotals();
        });

        $(document).on('click', '.btn-remove-product', function () {
            $(this).closest('tr').remove();
            calculateWorkOrderTotals();
        });

        const currentCustomerId = $customer.val() || window.workOrderOldCustomerId || '';
        renderVehicleOptions(currentCustomerId, oldVehicleId);

        if ($service.val() && (!$estimated.val() || parseFloat($estimated.val()) === 0)) {
            fillEstimatedPriceFromService();
        }

        if (selectedAddons.length > 0) {
            selectedAddons.forEach(function (addon) {
                createAddonRow(addon);
            });
        }

        if (selectedProducts.length > 0) {
            selectedProducts.forEach(function (product) {
                createProductRow(product);
            });
        }

        calculateWorkOrderTotals();
    }

    initWorkOrderEnhancements();
});