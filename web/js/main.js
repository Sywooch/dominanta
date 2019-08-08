var mainPage = {
    displayAccountMenu: false,
    loadEvent: function() {
        mainPage.loadTrigger(this);
    },
    loadTrigger: function(obj) {
        $('#top_personal_link').on('click', this.displayAccountMenuEvent);
        $('.modal_tab').on('click', this.checkModalTabEvent);
        $(document).on('click', this.documentClickEvent);

        $("#user-phone").mask("+7(999) 999-99-99");
        $("#callback-phone").mask("+7(999) 999-99-99");
    },
    documentClickEvent: function(e) {
        mainPage.documentClickTrigger(this, e);
    },
    documentClickTrigger: function(obj, e) {
        if (!$(e.target).closest("#account_menu").length && this.displayAccountMenu) {
            this.hideAccountMenu();
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