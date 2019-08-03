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
            success: shopcart.updateEvent,
        });
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
console.log($('.shopcart_item'));
            if (!$('.shopcart_item').length) {
                $('.shopcart_alert').slideDown(300, function() {
                    $(this).removeClass('hidden');
                });
            }
        });

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
        console.log('event')
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

        // FIXME update form
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