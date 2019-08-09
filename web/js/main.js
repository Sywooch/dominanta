var mainPage = {
    displayAccountMenu: false,
    displayDropdownMenu: false,
    loadEvent: function() {
        mainPage.loadTrigger(this);
    },
    loadTrigger: function(obj) {
        $('#top_personal_link').on('click', this.displayAccountMenuEvent);
        $('.modal_tab').on('click', this.checkModalTabEvent);
        $(document).on('click', this.documentClickEvent);

        $("#user-phone").mask("+7(999) 999-99-99");
        $("#callback-phone").mask("+7(999) 999-99-99");

        $('.menu_icon').on('click', this.showDropdownMenuEvent);
        $('.dropdown_left_col a').on('mouseover', this.showDropdownSubmenuEvent);
    },
    showDropdownMenuEvent: function() {
        mainPage.showDropdownMenuTrigger(this);
        return false;
    },
    showDropdownMenuTrigger: function(obj) {
        if (this.displayDropdownMenu) {
            this.hideDropdownMenu();
            return;
        }

        $('.dropdown_menu').fadeIn(300, function() {
            mainPage.displayDropdownMenu = true;
        });
    },
    hideDropdownMenu: function() {
        if (!this.displayDropdownMenu) {
            return;
        }

        $('.dropdown_menu').fadeOut(300, function() {
            mainPage.displayDropdownMenu = false;
        });
    },
    showDropdownSubmenuEvent: function() {
        mainPage.showDropdownSubmenuTrigger(this);
    },
    showDropdownSubmenuTrigger: function(obj) {
        $('.dropdown_left_col a').removeClass('active_dropdown_left');
        $(obj).addClass('active_dropdown_left');

        $('.dropdown_subcat_menu').addClass('hidden');
        $('#dropdown_subcat_menu_' + $(obj).data('category')).removeClass('hidden');
    },
    documentClickEvent: function(e) {
        mainPage.documentClickTrigger(this, e);
    },
    documentClickTrigger: function(obj, e) {
        if (!$(e.target).closest("#account_menu").length && this.displayAccountMenu) {
            this.hideAccountMenu();
        }

        if (!$(e.target).closest(".dropdown_menu").length && this.displayDropdownMenu) {
            this.hideDropdownMenu();
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