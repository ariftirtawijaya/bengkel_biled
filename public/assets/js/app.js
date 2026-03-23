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

    const $productsTable = $('#productsTable');
    if ($productsTable.length && $productsTable.data('has-data') == 1) {
        $productsTable.DataTable({
            responsive: true,
            pageLength: 10,
            order: [],
            language: dtLanguage
        });
    }

    const $customersTable = $('#customersTable');
    if ($customersTable.length && $customersTable.data('has-data') == 1) {
        $customersTable.DataTable({
            responsive: true,
            pageLength: 10,
            order: [],
            language: dtLanguage
        });
    }

    const $vehiclesTable = $('#vehiclesTable');
    if ($vehiclesTable.length && $vehiclesTable.data('has-data') == 1) {
        $vehiclesTable.DataTable({
            responsive: true,
            pageLength: 10,
            order: [],
            language: dtLanguage
        });
    }

    const $servicesTable = $('#servicesTable');
    if ($servicesTable.length && $servicesTable.data('has-data') == 1) {
        $servicesTable.DataTable({
            responsive: true,
            pageLength: 10,
            order: [],
            language: dtLanguage
        });
    }

    $(document).on('click', '.btn-delete-product', function () {
        const url = $(this).data('url');
        const name = $(this).data('name');

        Swal.fire({
            title: 'Hapus produk?',
            html: `Produk <b>${name}</b> akan dihapus.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, hapus',
            cancelButtonText: 'Batal',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = url;
            }
        });
    });

    $(document).on('click', '.btn-delete-customer', function () {
        const url = $(this).data('url');
        const name = $(this).data('name');

        Swal.fire({
            title: 'Hapus customer?',
            html: `Customer <b>${name}</b> akan dihapus.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, hapus',
            cancelButtonText: 'Batal',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = url;
            }
        });
    });

    $(document).on('click', '.btn-delete-vehicle', function () {
        const url = $(this).data('url');
        const name = $(this).data('name');

        Swal.fire({
            title: 'Hapus kendaraan?',
            html: `Kendaraan <b>${name}</b> akan dihapus.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, hapus',
            cancelButtonText: 'Batal',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = url;
            }
        });
    });

    $(document).on('click', '.btn-delete-service', function () {
        const url = $(this).data('url');
        const name = $(this).data('name');

        Swal.fire({
            title: 'Hapus jasa?',
            html: `Jasa <b>${name}</b> akan dihapus.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, hapus',
            cancelButtonText: 'Batal',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = url;
            }
        });
    });
});