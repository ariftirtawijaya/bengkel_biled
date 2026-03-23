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
});