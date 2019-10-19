var productList = {
    mobileFilterShow: false,
    mobileActionShow: {
       sort: false,
       show: false
    },
    loadEvent: function() {
        productList.loadTrigger(this);
    },
    loadTrigger: function(obj) {
        this.setPriceSlider();
        $('#filter_min_price').on('change', this.changeMinPriceEvent);
        $('#filter_max_price').on('change', this.changeMaxPriceEvent);
        $('.product_filter_value').on('click', this.productFilterEvent);
        $('.product_filter_actions a').on('click', this.clearFilterEvent);
        $('.product_filter_header_text').on('click', this.filterCollapseEvent);
        $('.product_mobile_filters_button a.mobile_filters_button').on('click', this.mobileFilterShowEvent);
        $('.product_mobile_filters_button a.mobile_action_button').on('click', this.mobileActionEvent);
        $('.product_items .product_item_quantity_control_minus').on('click', this.minusEvent);
        $('.product_items .product_item_quantity_control_plus').on('click', this.plusEvent);
    },
    mobileFilterShowEvent: function() {
        productList.mobileFilterShowTrigger(this);
        return false;
    },
    mobileFilterShowTrigger: function(obj) {
        if (this.mobileFilterShow) {
            $('.product_filter_column').fadeOut(300);
            this.mobileFilterShow = false;
        } else {
            $('.product_filter_column').fadeIn(300);
            this.mobileFilterShow = true;
        }
    },
    mobileActionEvent: function(obj) {
        productList.mobileActionTrigger(this);
        return false;
    },
    mobileActionTrigger: function(obj) {
        var action = $(obj).data('action');

        if (this.mobileActionShow[action]) {
            $('#product_list_action_items_mobile_' + action).addClass('hidden');
            this.mobileActionShow[action] = false;
        } else {
            this.hideActions();
            $('#product_list_action_items_mobile_' + action).removeClass('hidden');
            this.mobileActionShow[action] = true;
        }
    },
    hideActions: function() {
        for (action in this.mobileActionShow) {
            if (this.mobileActionShow[action]) {
                $('#product_list_action_items_mobile_' + action).addClass('hidden');
                this.mobileActionShow[action] = false;
            }
        }
    },
    filterCollapseEvent: function() {
        productList.filterCollapseTrigger(this);
    },
    filterCollapseTrigger: function(obj) {
        var filter = $(obj).data('filter');

        if ($('#product_filter_' + filter).hasClass('collapse')) {
            $('#product_filter_' + filter).slideDown(300, function() {
                $(this).removeClass('collapse')
            });
        } else {
            $('#product_filter_' + filter).slideUp(300, function() {
                $(this).addClass('collapse')
            });
        }

        $(obj).toggleClass('product_filter_header product_filter_header_collapsed');
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

            if ($(obj).data('filter') == 'vendor') {
                $(obj).append('<input type="hidden" name="vendor[]" value="' + $(obj).data('value') + '" data-filter="vendor" />');
            } else {
                $(obj).append('<input type="hidden" name="filter[' + $(obj).data('filter') + '][]" value="' + $(obj).data('value') + '" data-filter="' + $(obj).data('filter') + '" />');
            }
        }

        this.getProductsCount();
    },
    getProductsCount: function() {
        var url = location.pathname;
        var filter = {
            get_count: 1,
            filter: {}
        };

        $('#product_list_form input').each(function(){
            if ($(this).attr('name') != 'filter_button') {
                if ($(this).data('filter')) {
                    if ($(this).data('filter') == 'vendor') {
                        if (!filter['vendor']) {
                            filter['vendor'] = [$(this).val()];
                        } else {
                            filter['vendor'][filter['vendor'].length] = $(this).val();
                        }
                    } else {
                        if (!filter['filter'][$(this).data('filter')]) {
                            filter['filter'][$(this).data('filter')] = [$(this).val()];
                        } else {
                            filter['filter'][$(this).data('filter')][filter['filter'][$(this).data('filter')].length] = $(this).val();
                        }
                    }
                } else {
                    filter[$(this).attr('name')] = $(this).val()
                }
            }
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
    },
    getCurrentCount: function(obj) {
        var el = $('#product_item_quantity_control_' + $(obj).data('id') + $(obj).data('widget'));
        return parseInt($(el).find('.product_item_quantity_control_count').html());
    },
    minusEvent: function() {
        productList.minusTrigger(this);
    },
    minusTrigger: function(obj) {
        var currentCount = this.getCurrentCount(obj);

        if (currentCount == 1) {
            return;
        }

        var newCount = currentCount - 1;
        this.updateButtonCnt(obj, newCount);
    },
    plusEvent: function() {
        productList.plusTrigger(this);
    },
    plusTrigger: function(obj) {
        var currentCount = this.getCurrentCount(obj);

        if (currentCount == 100) {
            return;
        }

        var newCount = currentCount + 1;
        this.updateButtonCnt(obj, newCount);
    },
    updateButtonCnt: function(obj, cnt) {
        var el = $('#product_item_quantity_control_' + $(obj).data('id') + $(obj).data('widget'));
        $(el).find('.product_item_quantity_control_count').html(cnt);
        $('button.add_shopcart' + $(obj).data('id') + $(obj).data('widget')).data('cnt', cnt);
    }
}

$(document).ready(productList.loadEvent)