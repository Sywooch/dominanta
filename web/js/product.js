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
        $('.prod_quantity_control_minus').on('click', this.minusEvent);
        $('.prod_quantity_control_plus').on('click', this.plusEvent);
        $('.review_form_stars span').on('mouseover', this.starMouseOverEvent);
        $('.review_form_stars span').on('mouseout', this.starMouseOutEvent);
        $('.review_form_stars span').on('click', this.starSelectEvent);
        this.setSlideshowWidth();
    },
    starMouseOverEvent: function() {
        productPage.starMouseOverTrigger(this);
    },
    starMouseOutEvent: function() {
        productPage.starMouseOutTrigger(this);
    },
    starMouseOverTrigger: function(obj) {
        var findStar = false;

        $('.review_form_stars span').each(function() {
            if (findStar) {
                $(this).removeClass('review_form_star_active');
                $(this).addClass('review_form_star_inactive');
            } else {
                $(this).removeClass('review_form_star_inactive');
                $(this).addClass('review_form_star_active');
            }

            if (this == obj) {
                findStar = true;
            }
        });
    },
    starMouseOutTrigger: function(obj) {
        var findStar = false;
        var hasRate = $('span.selected_star').length;

        $('.review_form_stars span').each(function() {
            if (findStar || !hasRate) {
                $(this).removeClass('review_form_star_active');
                $(this).addClass('review_form_star_inactive');
            } else if (hasRate) {
                $(this).removeClass('review_form_star_inactive');
                $(this).addClass('review_form_star_active');
            }

            if ($(this).hasClass('selected_star')) {
                findStar = true;
            }
        });
    },
    starSelectEvent: function() {
        productPage.starSelectTrigger(this);
    },
    starSelectTrigger: function(obj) {
        var findStar = false;

        $('.review_form_stars span').removeClass('selected_star');
        $(obj).addClass('selected_star');

        $('.review_form_stars span').each(function(idx, el) {
            if (findStar) {
                $(this).removeClass('review_form_star_active');
                $(this).addClass('review_form_star_inactive');
            } else {
                $(this).removeClass('review_form_star_inactive');
                $(this).addClass('review_form_star_active');
            }

            if (this == obj) {
                findStar = true;
                var rate = idx + 1;
                $('#productreview-rate').val(rate);
            }
        });
    },
    setSlideshowWidth: function() {
        $('.product_photo_big_slideshow').width($('.product_photo_big_slide').length * this.slideWidth);
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

        if ($('.product_photo_slides img').length > 3) {
            $('.product_photo_slides').animate({scrollTop: (slideId - 1) * 160 }, 600);
        }

        $('.product_photo_big').animate({scrollLeft: this.slideWidth * (this.currentSlide - 1) }, 600);
    },
    minusEvent: function() {
        productPage.minusTrigger(this);
    },
    minusTrigger: function(obj) {
        var currentCount = parseInt($('.prod_quantity_control_count').html());

        if (currentCount == 1) {
            return;
        }

        var newCount = currentCount - 1;
        this.updateButtonCnt(newCount);
    },
    plusEvent: function() {
        productPage.plusTrigger(this);
    },
    plusTrigger: function(obj) {
        var currentCount = parseInt($('.prod_quantity_control_count').html());

        if (currentCount == 100) {
            return;
        }

        var newCount = currentCount + 1;
        this.updateButtonCnt(newCount);
    },
    updateButtonCnt: function(cnt) {
        $('.prod_quantity_control_count').html(cnt);
        $('button.add_shopcart').each(function() {
            $(this).data('cnt', cnt);
        });

        var dataWeight = $('.prod_quantity_control_count').data('weight');

        if (dataWeight) {
            dataWeight = parseFloat(dataWeight);
            var totalWeight = (dataWeight * cnt).toFixed(2);


            totalWeight += ' кг';
            totalWeight = totalWeight.replace(/\./, ',');
            $('.product_weight_value').html(totalWeight);
        }
    }
}

$(document).ready(productPage.loadEvent)