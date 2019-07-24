var productList = {
    loadEvent: function() {
        productList.loadTrigger(this);
    },
    loadTrigger: function(obj) {
        this.setPriceSlider();
        $('#filter_min_price').on('change', this.changeMinPriceEvent);
        $('#filter_max_price').on('change', this.changeMaxPriceEvent);
        $('.product_filter_value').on('click', this.productFilterEvent);
    },
    setPriceSlider: function() {
        var priceSlider = $('#slider-range');

        priceSlider.slider({
            range: true,
            min: priceSlider.data('min'),
            max: priceSlider.data('max'),
            values: [$('#filter_min_price').val(), $('#filter_max_price').val()],
            slide: function( event, ui ) {
                $('#filter_min_price').val(ui.values[0]);
                $('#filter_max_price').val(ui.values[1]);
            }
        });
    },
    changeMinPriceEvent: function() {
        productList.changeMinPriceTrigger(this);
    },
    changeMinPriceTrigger: function(obj) {
        var maxPrice = $('#filter_max_price').val();
        var minPrice = $(obj).val();
        $('#slider-range').slider('values', [minPrice, maxPrice]);
    },
    changeMaxPriceEvent: function() {
        productList.changeMaxPriceTrigger(this);
    },
    changeMaxPriceTrigger: function(obj) {
        var maxPrice = $(obj).val();
        var minPrice = $('#filter_min_price').val();
        $('#slider-range').slider('values', [minPrice, maxPrice]);
    },
    productFilterEvent: function() {
        productList.productFilterTrigger(this);
    },
    productFilterTrigger: function(obj) {
        if ($(obj).find('input').length) {
            $(obj).find('span').removeClass('product_filter_checkbox_active');
            $(obj).find('span').addClass('product_filter_checkbox');
            $(obj).find('input').remove();
        } else {
            $(obj).find('span').removeClass('product_filter_checkbox');
            $(obj).find('span').addClass('product_filter_checkbox_active');
            $(obj).append('<input type="hidden" name="filter[' + $(obj).data('filter') + '][]" value="' + $(obj).data('value') + '" />');
        }
    }
}

$(document).ready(productList.loadEvent)