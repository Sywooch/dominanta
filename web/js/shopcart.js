var shopcart = {
    loadEvent: function() {
        shopcart.loadTrigger(this);
    },
    loadTrigger: function(obj) {
        $('.add_shopcart').on('click', this.addEvent);
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