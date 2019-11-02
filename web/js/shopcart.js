var shopcart = {
    updating: false,
    loadEvent: function() {
        shopcart.loadTrigger(this);
    },
    loadTrigger: function(obj) {
        $('.add_shopcart').on('click', this.addEvent);
        $('.shopcart_item button').on('click', this.delEvent);
        $('.quantity_control_minus').on('click', this.minusEvent);
        $('.quantity_control_plus').on('click', this.plusEvent);
        $('span.order_address').on('click', this.selAddressEvent);
        $('.new_address_link').on('click', this.newAddressEvent);
        $('.custom_form_selector').on('click', this.customSelEvent);
        $('.custom_checkbox').on('click', this.checkboxEvent);
        $('#shoporder-phone').mask("+7(999) 999-99-99");
        $('.product_widget .product_item_quantity_control_minus, .find_products .product_item_quantity_control_minus').on('click', this.minusWidgetEvent);
        $('.product_widget .product_item_quantity_control_plus, .find_products .product_item_quantity_control_plus').on('click', this.plusWidgetEvent);
    },
    getCurrentCount: function(obj) {
        var el = $('#product_item_quantity_control_' + $(obj).data('id') + $(obj).data('widget'));
        return parseInt($(el).find('.product_item_quantity_control_count').html());
    },
    minusWidgetEvent: function() {
        shopcart.minusWidgetTrigger(this);
    },
    minusWidgetTrigger: function(obj) {
        var currentCount = this.getCurrentCount(obj);

        if (currentCount == 1) {
            return;
        }

        var newCount = currentCount - 1;
        this.updateButtonCnt(obj, newCount);
    },
    plusWidgetEvent: function() {
        shopcart.plusWidgetTrigger(this);
    },
    plusWidgetTrigger: function(obj) {
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
    },
    addEvent: function() {
        shopcart.addTrigger(this);
    },
    addTrigger: function(obj) {
        var product_id = $(obj).data('id');
        var quantity   = $(obj).data('cnt');

        $.ajax({
            url: '/shopcart/add',
            method: 'GET',
            data: {product_id: product_id, quantity: quantity},
            success: shopcart.addItemEvent,
        });
    },
    addItemEvent: function(data) {
        shopcart.addItemTrigger(data);
    },
    addItemTrigger: function(data) {
        this.updateEvent(data);
        $('#modal_shopcart').modal('show');

        var product = data.message.product;

        var html = '<div class="row shopcart_item" id="shopcart_item_' + product.item_id + '">';
            html += '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">'
                html += '<a href="' + product.link + '" target="_blank"><img src="' + product.photo + '" />';
                html += '<div class="shopcart_item_link"><a href="' + product.link +'">' + product.title + '</a></div>';
            html += '</div>';

            html += '<div class="col-lg-4 col-md-4 col-sm-4 col-xs-6">';
                html += '<div class="shopcart_item_qprice">';
                    html += '<div class="shopcart_item_q">'
                        html += '<div class="shopcart_info_label">Количество</div>';
                        html += '<div class="quantity_control" id="quantity_control_' + product.item_id + '">';
                            html += '<span class="quantity_control_minus" data-id="' + product.item_id + '">&ndash;</span>';
                            html += '<span class="quantity_control_plus" data-id="' + product.item_id + '">+</span>';
                            html += '<span class="quantity_control_count">' + product.cnt + '</span>';
                        html += '</div>';
                    html += '</div>';
                html += '</div>';
            html += '</div>';
            html += '<div class="col-lg-4 col-md-4 col-sm-4 col-xs-6 text-center">';
                html += '<div class="shopcart_item_price"><div class="shopcart_info_label">Цена</div>' + product.price + ' <i class="fa fa-ruble"></i></div>';
            html += '</div>';
            html += '<div class="col-lg-4 col-md-4 col-sm-4 hidden-xs text-right">';
                html += '<div class="shopcart_item_price"><div class="shopcart_info_label">Стоимость</div><span class="shopcart_item_calc_sum">' + product.sum + '</span> <i class="fa fa-ruble"></i></div>';
            html += '</div>';
            html += '<div class="hidden-lg hidden-md hidden-sm col-xs-12 text-center">';
                html += '<div class="shopcart_item_price" style="margin-top: 10px;"><div class="shopcart_info_label">Стоимость</div><span class="shopcart_item_calc_sum">' + product.sum + '</span> <i class="fa fa-ruble"></i></div>';
            html += '</div>';
        html += '</div>';

        $('#shopcart_add_info').html(html);

        $('.add_shopcart' + product.id).addClass('added_shopcart');

        $('.add_shopcart' + product.id).data('cnt', '0');

        $('.add_shopcart' + product.id).html('<i class="fa fa-check"></i> Добавлено!');

        $('.product_item_q' + product.id).addClass('hidden');
        $('.product_counter_add').addClass('hidden');

        $('.quantity_control_minus').on('click', this.minusEvent);
        $('.quantity_control_plus').on('click', this.plusEvent);
    },
    delEvent: function() {
        shopcart.delTrigger(this);
    },
    delTrigger: function(obj) {
        var item_id = $(obj).data('id');

        $.ajax({
            url: '/shopcart/delete',
            method: 'GET',
            data: {item_id: item_id},
            success: shopcart.delItemEvent,
        });
    },
    delItemEvent: function(data) {
        shopcart.updateTrigger(data);
        shopcart.delItemTrigger(data);
    },
    delItemTrigger: function(data) {
        $('#shopcart_item_' + data.message.id).slideUp(300, function() {
            $(this).remove();

            if (!$('.shopcart_item').length) {
                $('.shopcart_alert').slideDown(300, function() {
                    $(this).removeClass('hidden');
                    $('#shopcart_form').remove();
                });
            }
        });

        $('.order_total_amount').html(data.message.sum + ' <i class="fa fa-ruble"></i>');
        // FIXME update form
    },
    minusEvent: function() {
        shopcart.minusTrigger(this);
    },
    minusTrigger: function(obj) {
        var item_id = $(obj).data('id');
        var currentCount = parseInt($('#quantity_control_' + item_id + ' .quantity_control_count').html());

        if (currentCount == 1) {
            return;
        }

        var newCount = currentCount - 1;
        this.updateCntInit(item_id, newCount);
    },
    plusEvent: function() {
        shopcart.plusTrigger(this);
    },
    plusTrigger: function(obj) {
        var item_id = $(obj).data('id');
        var currentCount = parseInt($('#quantity_control_' + item_id + ' .quantity_control_count').html());

        if (currentCount == 100) {
            return;
        }

        var newCount = currentCount + 1;
        this.updateCntInit(item_id, newCount);
    },
    updateCntInit: function(item_id, cnt) {
        $('#quantity_control_' + item_id).css('opacity', '0.5');
        $('#quantity_control_' + item_id + ' .quantity_control_count').html(cnt);

        if (this.updating) {
            clearTimeout(this.updating);
        }

        this.updating = setTimeout('shopcart.updateCnt(' + item_id + ', ' + cnt + ')', 700);
    },
    updateCnt: function(item_id, cnt) {
        $.ajax({
            url: '/shopcart/update_cnt',
            method: 'GET',
            data: {item_id: item_id, cnt: cnt},
            success: shopcart.updateCntEvent,
        });

    },
    updateCntEvent: function(data) {
        shopcart.updateCntTrigger(data);
        shopcart.updateTrigger(data);
    },
    updateCntTrigger: function(data) {
        $('#quantity_control_' + data.message.id).css('opacity', '1.0');
        $('.order_total_amount').html(data.message.sum + ' <i class="fa fa-ruble"></i>');
        $('.shopcart_item_calc_sum').html(data.message.product_sum);
        // FIXME update form
    },
    selAddressEvent: function() {
        shopcart.selAddressTrigger(this);
    },
    selAddressTrigger: function(obj) {
        $('span.order_address').removeClass('order_address_selected');
        $(obj).addClass('order_address_selected');
        $('#selected_address').val($(obj).data('id'));
        $('#shoporder-address').val($(obj).data('id'));

        if (!$('.new_address_input').hasClass('hidden')) {
            $('.new_address_input').slideUp(300, function() {
                $(this).addClass('hidden');
            });
        }
    },
    newAddressEvent: function() {
        shopcart.newAddressTrigger(this);
        return false;
    },
    newAddressTrigger: function(obj) {
        if ($('.new_address_input').hasClass('hidden')) {
            $('.new_address_input').slideDown(300, function() {
                $(this).removeClass('hidden');
                $('span.order_address').removeClass('order_address_selected');
                $('#selected_address').val(0);
                $('#shoporder-address').val('');
            });
        } else {
            $('.new_address_input').slideUp(300, function() {
                $(this).addClass('hidden');
            });

            $('span.order_address:first').addClass('order_address_selected');
            $('#selected_address').val($('span.order_address:first').data('id'));
        }
    },
    customSelEvent: function() {
        shopcart.customSelTrigger(this);
    },
    customSelTrigger: function(obj) {
        var parent = $(obj).parents('.custom_sel_row');
        $(parent).find('.custom_form_selector').removeClass('custom_form_selector_active');
        $(parent).find('input').val($(obj).data('id'));
        $(obj).addClass('custom_form_selector_active');

        if ($(obj).hasClass('delivery_sel')) {
            if ($(obj).data('id')) {
                $('.delivery_row').removeClass('hidden');
            } else {
                $('.delivery_row').addClass('hidden');
            }
        }
    },
    checkboxEvent: function() {
        shopcart.checkboxTrigger(this);
    },
    checkboxTrigger: function(obj) {
        if ($(obj).hasClass('checkbox_active')) {
            $(obj).removeClass('checkbox_active');
            $(obj).addClass('checkbox_inactive');
            $('#shoporder-agreement').val('');
        } else {
            $(obj).removeClass('checkbox_inactive');
            $(obj).addClass('checkbox_active');
            $('#shoporder-agreement').val('1');
        }
    },
    updateEvent: function(data) {
        shopcart.updateTrigger(data)
    },
    updateTrigger: function(data) {
        this.setCount(data.message.cnt);
        this.setAmount(data.message.sum);
    },
    setCount: function(cnt) {
        $('.shopping_cart_badge').html(cnt)
    },
    setAmount: function(sum) {
        $('.shopping_cart_amount span').html(sum);
    }
}

$(document).ready(shopcart.loadEvent);