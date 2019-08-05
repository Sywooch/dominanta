var orders = {
    loadEvent: function() {
        orders.loadTrigger(this);
    },
    loadTrigger: function(obj) {
        $('.order_item_collapse_button').on('click', this.collapseEvent);
    },
    collapseEvent: function() {
        orders.collapseTrigger(this);
    },
    collapseTrigger: function(obj) {
        var order_id = $(obj).data('id');
        $('#order_positions_' + order_id).collapse('toggle');
        $(obj).find('i').toggleClass('fa-angle-down fa-angle-up');
    }
}

$(document).ready(orders.loadEvent)