var productCats = {
    collapsedHeight: 55,
    descHeight: 0,
    loadEvent: function() {
        productCats.loadTrigger(this);
    },
    loadTrigger: function(obj) {
        $('.product_category_item_show_all').on('click', this.toggleSubcatEvent);
        this.collapseDesc();
        $(window).resize(function(){
            $('.category_description').css('height', 'auto');
            productCats.collapseDesc();
        })
    },
    toggleSubcatEvent: function() {
        productCats.toggleSubcatTrigger(this);
    },
    toggleSubcatTrigger: function(obj) {
        var id = $(obj).data('id');

        $('.hidden_subcats_' + id).each(function(){
            if ($(this).hasClass('show_category')) {
                $(this).slideUp();
                $(this).removeClass('show_category');
            } else {
                $(this).slideDown();
                $(this).addClass('show_category');
            }

        });

        $('.product_category_item_show_all' + id).find('i').toggleClass('fa-angle-down fa-angle-up');
    },
    collapseDesc: function() {
        this.descHeight = $('.category_description').height();

        if (this.descHeight > this.collapsedHeight) {
            $('.category_description').animate({height: this.collapsedHeight + 'px'});
            $('.category_description_link').html('<a href="#">Показать текст полностью <i class="fa fa-angle-down"></a>');
            $('.category_description_link a').on('click', function() {
                productCats.fullDesc();
                return false;
            });
        }
    },
    fullDesc: function() {
        $('.category_description').animate({height: this.descHeight + 'px'});
        $('.category_description_link').html('<a href="#">Скрыть текст <i class="fa fa-angle-up"></a>');

        $('.category_description_link a').on('click', function() {
            productCats.collapseDesc();
            return false;
        });
    }
}

$(document).ready(productCats.loadEvent)