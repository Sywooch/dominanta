var productList = {
    loadEvent: function() {
        productList.loadTrigger(this);
    },
    loadTrigger: function(obj) {
        this.setPriceSlider();
        $('#filter_min_price').on('change', this.changeMinPriceEvent);
        $('#filter_max_price').on('change', this.changeMaxPriceEvent);
        $('.product_filter_value').on('click', this.productFilterEvent);
        $('.product_filter_actions a').on('click', this.clearFilterEvent);
    },
    clearFilterEvent: function() {
        productList.clearFilterTrigger(this);
        return false;
    },
    clearFilterTrigger: function() {
        var priceSlider = $('#slider-range');
        var minPrice = priceSlider.data('min');
        var maxPrice = priceSlider.data('max');

        $('#filter_min_price').val(minPrice);
        $('#filter_max_price').val(maxPrice);
        $('#slider-range').slider('values', [minPrice, maxPrice]);

        $('.product_filter_value span.product_filter_checkbox_active').each(function(){
            $(this).addClass('product_filter_checkbox');
            $(this).removeClass('product_filter_checkbox_active');
        });

        $('.product_filter_value input[type=hidden]').remove();
        this.getProductsCount();
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
            },
            stop: function( event, ui ) {
                productList.getProductsCount();
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
        this.getProductsCount();
    },
    changeMaxPriceEvent: function() {
        productList.changeMaxPriceTrigger(this);
    },
    changeMaxPriceTrigger: function(obj) {
        var maxPrice = $(obj).val();
        var minPrice = $('#filter_min_price').val();
        $('#slider-range').slider('values', [minPrice, maxPrice]);
        this.getProductsCount();
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

        this.getProductsCount();
    },
    getProductsCount: function() {
        var url = location.pathname;
        var filter = {
            get_count: 1
        };

        $('#product_list_form input').each(function(){
            filter[$(this).attr('name')] = $(this).val();
        });

        $.ajax({
            url: url,
            method: 'GET',
            data: filter,
            success: function(data) {
                $('.product_filter_actions input').val('Показать ' + data + ' товаров');

                if ($('.product_filter_actions input').css('display') == 'none') {
                    $('.product_filter_actions input').slideDown();
                }

                if (data == '0') {
                    $('.product_filter_actions input').attr('disabled', true);
                } else {
                    $('.product_filter_actions input').attr('disabled', false);
                }
            }
        });
    }
}

$(document).ready(productList.loadEvent)