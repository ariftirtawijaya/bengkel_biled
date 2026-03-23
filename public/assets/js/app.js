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

        if (!$customer.length || !$vehicle.length) {
            return;
        }

        const vehicles = Array.isArray(window.workOrderVehicles) ? window.workOrderVehicles : [];
        const oldVehicleId = window.workOrderOldVehicleId || '';
        let firstInit = true;

        function buildVehicleLabel(vehicle) {
            const plate = vehicle.plate_number && vehicle.plate_number !== '' ? vehicle.plate_number : '-';
            return `${vehicle.brand} ${vehicle.model} - ${plate}`;
        }

        function renderVehicleOptions(customerId, selectedVehicleId = '') {
            $vehicle.empty();
            $vehicle.append('<option value="">-- Pilih Kendaraan --</option>');

            if (!customerId) {
                return;
            }

            const filtered = vehicles.filter(function (vehicle) {
                return String(vehicle.customer_id) === String(customerId);
            });

            filtered.forEach(function (vehicle) {
                const isSelected = String(selectedVehicleId) === String(vehicle.id) ? 'selected' : '';
                $vehicle.append(`<option value="${vehicle.id}" ${isSelected}>${buildVehicleLabel(vehicle)}</option>`);
            });
        }

        function fillEstimatedPriceFromService() {
            const price = parseFloat($service.find(':selected').data('price')) || 0;
            $estimated.val(price.toFixed(2));
        }

        $customer.on('change', function () {
            renderVehicleOptions($(this).val(), '');
        });

        $service.on('change', function () {
            fillEstimatedPriceFromService();
        });

        const currentCustomerId = $customer.val() || window.workOrderOldCustomerId || '';
        renderVehicleOptions(currentCustomerId, oldVehicleId);

        if (firstInit && $service.val() && (!$estimated.val() || parseFloat($estimated.val()) === 0)) {
            fillEstimatedPriceFromService();
        }

        firstInit = false;
    }

    initWorkOrderEnhancements();
});