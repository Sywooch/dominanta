var mainPage = {
    displayAccountMenu: false,
    displayDropdownMenu: false,
    mouseOverIcon: false,
    mouseOverMenu: false,
    menuInit: false,
    loadEvent: function() {
        mainPage.loadTrigger(this);
    },
    loadTrigger: function(obj) {
        $('#top_personal_link').on('click', this.displayAccountMenuEvent);
        $('.modal_tab').on('click', this.checkModalTabEvent);
        $(document).on('click', this.documentClickEvent);

        $("#user-phone").mask("+7(999) 999-99-99");
        $("#callback-phone").mask("+7(999) 999-99-99");

        if ($.browser.mobile) {
            $('.menu_icon').on('click', this.showDropdownMenuEvent);
            $('.dropdown_left_col a').on('click', this.showDropdownSubmenuEvent);
            this.setMobileMenu();
        } else {
            $('.menu_icon, .dropdown_menu').on('mouseover', this.showDropdownMenuEvent);
            $('.menu_icon, .dropdown_menu').on('mouseout', this.hideDropdownMenuEvent);
            $('.dropdown_left_col a').on('mouseover', this.showDropdownSubmenuEvent);
        }
    },
    setMobileMenu: function() {
        var html = '<div style="border-top: 1px solid #d7d7d7"></div>';

        $('.menu_item').each(function(){
            html += '<a href="' + $(this).attr('href') + '" class="mobile_menu_item">' + $(this).html() + '</a>';
        });

        $('.dropdown_left_col').append(html);

        var html = '<div class="hidden-lg hidden-md hidden-sm col-xs-12"><div class="dropdown_right_col">';
        html += '<a href="#" class="back_in_catalog"><i class="fa fa-angle-left"></i> В каталог</a></div></div>';

        $('.dropdown_subcat_menu').each(function(){
            $(this).prepend(html);
        });

        $('a.active_dropdown_left').removeClass('active_dropdown_left');

        $('.back_in_catalog').on('click', this.backInCatalogEvent)
    },
    backInCatalogEvent: function() {
        mainPage.backInCatalogTrigger(this);
        return false;
    },
    backInCatalogTrigger: function() {
        $('.dropdown_left_col').removeClass('hidden-xs');
        $('.dropdown_subcat_menu').addClass('hidden');
    },
    showDropdownMenuEvent: function() {
        mainPage.showDropdownMenuTrigger(this);
        return false;
    },
    hideDropdownMenuEvent: function() {
        mainPage.hideDropdownMenuTrigger(this);
        return false;
    },
    showDropdownMenuTrigger: function(obj) {
        if (this.menuInit) {
            clearTimeout(this.menuInit);
            this.menuInit = false;
        }

        if ($(obj).hasClass('menu_icon')) {
            this.mouseOverIcon = true;
        } else {
            this.mouseOverMenu = true;
        }

        if (this.displayDropdownMenu) {
            if ($.browser.mobile) {
                this.hideDropdownMenu();
            }
            return;
        }

        $('.dropdown_menu').fadeIn(300, function() {
            mainPage.displayDropdownMenu = true;
        });
    },
    hideDropdownMenuTrigger: function(obj) {
        if ($(obj).hasClass('menu_icon')) {
            this.mouseOverIcon = false;
        } else {
            this.mouseOverMenu = false;
        }

        if (this.mouseOverIcon || this.mouseOverMenu) {
            return;
        }

        if (!this.menuInit) {
            this.menuInit = setTimeout('mainPage.hideDropdownMenu()', 150);
        }
    },
    hideDropdownMenu: function() {
        $('.dropdown_menu').fadeOut(150, function() {
            mainPage.displayDropdownMenu = false;
            mainPage.menuInit = false;
        });
    },
    showDropdownSubmenuEvent: function() {
        return mainPage.showDropdownSubmenuTrigger(this);
    },
    showDropdownSubmenuTrigger: function(obj) {
        if ($(obj).hasClass('mobile_menu_item')) {
            return true;
        }

        if ($.browser.mobile) {
            $('.dropdown_left_col').addClass('hidden-xs');
        }

        $('.dropdown_left_col a').removeClass('active_dropdown_left');
        $(obj).addClass('active_dropdown_left');

        $('.dropdown_subcat_menu').addClass('hidden');
        $('#dropdown_subcat_menu_' + $(obj).data('category')).removeClass('hidden');
        $('#dropdown_subcat_menu_' + $(obj).data('category')).removeClass('hidden-xs');

        if ($.browser.mobile) {
            return false;
        } else {
            return true;
        }
    },
    documentClickEvent: function(e) {
        mainPage.documentClickTrigger(this, e);
    },
    documentClickTrigger: function(obj, e) {
        if (!$(e.target).closest("#account_menu").length && this.displayAccountMenu) {
            this.hideAccountMenu();
        }

        if (window.productList) {
            if (!$(e.target).closest(".mobile_action_button").length) {
                productList.hideActions();
            }

            if (!$(e.target).closest(".product_filter_column").length && productList.mobileFilterShow) {
                $('.product_filter_column').fadeOut(300);
                productList.mobileFilterShow = false;
            }
        }

        if ($.browser.mobile) {
            if (!$(e.target).closest(".dropdown_menu").length  && this.displayDropdownMenu) {
                this.hideDropdownMenu();
            }
        }

        //e.stopPropagation();
    },
    displayAccountMenuEvent: function() {
        return mainPage.displayAccountMenuTrigger(this);
    },
    displayAccountMenuTrigger: function(obj) {
        if ($(obj).data('toggle') == 'modal') {
            return true;
        }

        this.showAccountMenu();
        return false;
    },
    showAccountMenu: function() {
        if (this.displayAccountMenu) {
            return;
        }

        $('#account_menu').fadeIn(300, function() {
            mainPage.displayAccountMenu = true;
        });
    },
    hideAccountMenu: function() {
        if (!this.displayAccountMenu) {
            return;
        }

        $('#account_menu').fadeOut(300, function() {
            mainPage.displayAccountMenu = false;
        });
    },
    checkModalTabEvent: function() {
        mainPage.checkModalTabTrigger(this);
        return false;
    },
    checkModalTabTrigger: function(obj) {
        $('.modal_tab_panel').removeClass('active_modal_tab_panel');
        $('.modal_tab').removeClass('active_modal_tab');
        $($(obj).attr('href')).addClass('active_modal_tab_panel');

        if ($(obj).parents('.modal_tabs').length) {
            $(obj).addClass('active_modal_tab');
        }
    }


}

$(document).ready(mainPage.loadEvent)