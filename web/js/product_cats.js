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

        $('.hidden_subcats_' + id).each(function(){
            if ($(this).hasClass('show_category')) {
                $(this).slideUp();
                $(this).removeClass('show_category');
            } else {
                $(this).slideDown();
                $(this).addClass('show_category');
            }

        })

        $('.product_category_item_show_all' + id).find('i').toggleClass('fa-angle-down fa-angle-up');
    }

}

$(document).ready(productCats.loadEvent)