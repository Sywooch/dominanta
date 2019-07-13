var productPage = {
    currentSlide: 1,
    slideWidth: 450,
    loadEvent: function() {
        productPage.loadTrigger(this);
    },
    loadTrigger: function(obj) {
        $('.product_tabs a').on('click', this.checkProductTabEvent);
        $('.photo_left_arrow').on('click', this.prevSlideEvent);
        $('.photo_right_arrow').on('click', this.nextSlideEvent);
        $('.product_photo_slides img').on('click', this.selectSlideEvent);
    },
    checkProductTabEvent: function() {
        productPage.checkProductTabTrigger(this);
        return false;
    },
    checkProductTabTrigger: function(obj) {
        $('.product_tab_content').removeClass('product_active_content');
        $('.product_tabs a').removeClass('product_active_tab');
        $($(obj).attr('href')).addClass('product_active_content');
        $(obj).addClass('product_active_tab');
    },
    prevSlideEvent: function() {
        productPage.prevSlideTrigger(this);
    },
    prevSlideTrigger: function(obj) {
        var maxSlide = this.getMaxSlide();
        var maxSlideId = $(maxSlide).data('slide');

        if (this.currentSlide == 1) {
            this.selectSlideTrigger(maxSlide);
            return;
        }

        var prevSlideId = this.currentSlide - 1;

        $('.product_photo_slides img').each(function() {
            if ($(this).data('slide') == prevSlideId) {
                productPage.selectSlideTrigger(this);
                return
            }
        });
    },
    nextSlideEvent: function() {
        productPage.nextSlideTrigger(this);
    },
    nextSlideTrigger: function() {
        var maxSlide = this.getMaxSlide();
        var maxSlideId = $(maxSlide).data('slide');

        if (this.currentSlide == maxSlideId) {
            var nextSlideId = 1;
        } else {
            var nextSlideId = this.currentSlide + 1;
        }

        $('.product_photo_slides img').each(function() {
            if ($(this).data('slide') == nextSlideId) {
                productPage.selectSlideTrigger(this);
                return
            }
        });
    },
    getMaxSlide: function() {
        var maxSlide;

        $('.product_photo_slides img').each(function(){
            maxSlide = this;
        });

        return maxSlide;
    },
    selectSlideEvent: function() {
        productPage.selectSlideTrigger(this);
    },
    selectSlideTrigger: function(obj) {
        var slideId = $(obj).data('slide');

        if (this.currentSlide == slideId) {
            return;
        }

        this.currentSlide = slideId;

        $('.product_photo_slides img').removeClass('product_photo_active_slide');
        $(obj).addClass('product_photo_active_slide');

        $('.product_photo_big').animate({scrollLeft: this.slideWidth * (this.currentSlide - 1) }, 600);
    }

}

$(document).ready(productPage.loadEvent)