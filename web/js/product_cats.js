var productCats = {
    collapsedHeight: 55,
    descHeight: 0,
    subcatsInits: {},
    loadEvent: function() {
        productCats.loadTrigger(this);
    },
    loadTrigger: function(obj) {
        if ($.browser.mobile) {
            $('.product_category_item_show_all').on('click', this.clickSubcatEvent);
            $(document).on('click', this.documentClickEvent);
        } else {
            $('.product_category_item_show_all, .product_category_item_show_all *').on('mouseover', this.showSubcatEvent);
            $('.product_category_item_show_all').on('mouseout', this.hideSubcatEvent);
        }

        this.collapseDesc();
        $(window).resize(function(){
            $('.category_description').css('height', 'auto');
            productCats.collapseDesc();
        })
    },
    clickSubcatEvent: function() {
        productCats.clickSubcatTrigger(this);
    },
    clickSubcatTrigger: function(obj) {
        var id = $(obj).data('id');

        if ($('.product_category_item_subcats' + id).hasClass('product_category_item_subcats_visible')) {
            this.hideSubcat(id);
            $('.product_category_item_subcats' + id).removeClass('product_category_item_subcats_visible');
        } else {
            this.showSubcat(id);
            $('.product_category_item_subcats' + id).addClass('product_category_item_subcats_visible');
        }
    },
    documentClickEvent: function(e) {
        productCats.documentClickTrigger(this, e);
    },
    documentClickTrigger: function(obj, e) {
        if (!$(e.target).closest($('.product_category_item_subcats')).length && !$(e.target).closest($('.product_category_item_show_all')).length) {
            $('.product_category_item_subcats').fadeOut();
            $('.product_category_item_subcats').removeClass('product_category_item_subcats_visible');
        }
        //e.stopPropagation();
    },
    showSubcatEvent: function() {
        productCats.showSubcatTrigger(this);
    },
    hideSubcatEvent: function() {
        productCats.hideSubcatTrigger(this);
    },
    showSubcatTrigger: function(obj) {
        var id = $(obj).data('id');

        if (this.subcatsInits[id]) {
            clearTimeout(this.subcatsInits[id]);
            this.subcatsInits[id] = false;
        }

        this.showSubcat(id);
    },
    showSubcat: function(id) {
        $('.product_category_item_subcats' + id).fadeIn();
        $('.product_category_item_show_all' + id).find('i').removeClass('fa-angle-down');
        $('.product_category_item_show_all' + id).find('i').addClass('fa-angle-up');
    },
    hideSubcatTrigger: function(obj) {
        var id = $(obj).data('id');

        if (!this.subcatsInits[id]) {
            this.subcatsInits[id] = setTimeout('productCats.hideSubcat(' + id + ')', 150);
        }
    },
    hideSubcat: function(id) {
        $('.product_category_item_subcats' + id).fadeOut()
        $('.product_category_item_show_all' + id).find('i').addClass('fa-angle-down');
        $('.product_category_item_show_all' + id).find('i').removeClass('fa-angle-up');
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