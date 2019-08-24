var productCats = {
    loadEvent: function() {
        productCats.loadTrigger(this);
    },
    loadTrigger: function(obj) {
        $('.product_category_item_show_all').on('click', this.toggleSubcatEvent);
    },
    toggleSubcatEvent: function() {
        productCats.toggleSubcatTrigger(this);
    },
    toggleSubcatTrigger: function(obj)
    {
        var id = $(obj).data('id');

        if ($('#hidden_subcats_' + id).hasClass('show_category')) {
            $('#hidden_subcats_' + id).slideUp();
            $('#hidden_subcats_' + id).removeClass('show_category');
        } else {
            $('#hidden_subcats_' + id).slideDown();
            $('#hidden_subcats_' + id).addClass('show_category');
        }

        $(obj).find('i').toggleClass('fa-angle-down fa-angle-up');
    }

}

$(document).ready(productCats.loadEvent)