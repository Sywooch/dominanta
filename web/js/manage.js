var manage = {
    modalWindow: '',
    loadEvent: function() {
        manage.loadTrigger(this);
    },
    loadTrigger: function(object) {
        $('.modal').on('hidden.bs.modal', this.modalHiddenEvent);
        $('.modal').on('shown.bs.modal', this.modalShownEvent);
        $('.manage-popover').on('click', this.popoverEvent);
        this.autocompleteHandler('.manage-autocomplete');
        this.datepickerHandler('.datepicker');
        this.switchery('body .js-switch-custom');
        this.fancybox();
        this.tooltips();
        this.modalWindow = $('#admin_modal_window').clone();
    },
    tooltips: function() {
        $('[data-tooltip=true]').tooltip();
    },
    fancybox: function() {
        $('a.single_image').fancybox();
        $('a.multiple_image').fancybox({
            transitionIn: 'elastic',
            transitionOut: 'elastic',
            speedIn: 600,
            speedOut: 200,
            overlayShow: false
        });
    },
    selectMenu: function(url) {
        $('#sidebar-menu').find('a[href="' + url + '"]').parent('li').addClass('current-page');

        $('#sidebar-menu').find('a').filter(function () {
            return $(this).attr('href') == url;
        }).parent('li').addClass('current-page').parents('ul').slideDown(function() {
            manage.setContentHeight();
        }).parent().addClass('active');
    },
    setContentHeight: function() {
        $('.right_col').css('min-height', $(window).height());

        var bodyHeight = $('body').outerHeight(),
            footerHeight = $('body').hasClass('footer_fixed') ? -10 : $('footer').height(),
            leftColHeight = $('.left_col').eq(1).height() + $('.sidebar-footer').height(),
            contentHeight = bodyHeight < leftColHeight ? leftColHeight : bodyHeight;

        contentHeight -= $('.nav_menu').height() + footerHeight;
        $('.right_col').css('min-height', contentHeight);
    },
    switchery: function(selector) {
        if ($(selector)[0]) {
            var elems = Array.prototype.slice.call(document.querySelectorAll(selector));
            elems.forEach(function (html) {
                var switchery = new Switchery(html, {
                    color: '#26B99A',
                    size: 'small',
                });
            });
        }
    },
    panels: function() {
        $('.modal .collapse-link').on('click', function() {
            var $BOX_PANEL = $(this).closest('.x_panel'),
                $ICON = $(this).find('i'),
                $BOX_CONTENT = $BOX_PANEL.find('.x_content');

            if ($BOX_PANEL.attr('style')) {
                $BOX_CONTENT.slideToggle(200, function(){
                    $BOX_PANEL.removeAttr('style');
                });
            } else {
                $BOX_CONTENT.slideToggle(200);
                $BOX_PANEL.css('height', 'auto');
            }

            $ICON.toggleClass('fa-chevron-up fa-chevron-down');
        });

        $('.modal .close-link').click(function () {
            var $BOX_PANEL = $(this).closest('.x_panel');

            $BOX_PANEL.remove();
        });
    },
    autocompleteHandler: function(selector) {
        var autocompleteOptions = {
            source: manage.autocompleteEvent,
            minLength: 2,
            select: manage.autocompleteSelectEvent
        };

        if (selector.indexOf('.modal') >= 0 || selector.indexOf('linkform') >= 0) {
            autocompleteOptions.appendTo = '.modal-body';
        }

        $(selector).autocomplete(autocompleteOptions);
        $(selector).each(function(){
            $(this).on('focus', manage.autocompleteAutosearch);
        });
    },
    datepickerHandler: function(selector) {
        $(selector).datepicker({dateFormat: 'dd.mm.yy'});
    },
    modalHiddenEvent: function() {
        manage.modalHiddenTrigger(this);
    },
    modalHiddenTrigger: function(object) {
        //$('#admin_modal_window').remove();
        //$('#main_admin_container').prepend(this.modalWindow);

        $(object).removeData('bs.modal');
        $('.modal-content').find('script').remove();
    },
    modalShownEvent: function() {
        manage.modalShownTrigger(this);
        $('.manage-popover').popover('hide');
    },
    modalShownTrigger: function(object) {
        this.autocompleteHandler('.modal .manage-autocomplete');
        this.switchery('.modal .js-switch-custom');
        this.panels();
        this.fancybox();
    },
    autocompleteEvent: function(request, response) {
        var element = this.element;

        var data = {
            model: element.attr('data-model'),
            field: element.attr('data-field'),
            value: element.val()
        };

        var link = element.attr('data-link');
        var add_url = element.attr('data-add');

        if (link) {
            data.link = link;
        }

        $.ajax({
            url: "/manage/autocomplete",
            dataType: "json",
            data: data,
            method: 'post',
            success: function(data) {
                var resp = [];

                for (idx in data) {
                    resp.push({label: data[idx], value: data[idx], id: idx})
                }

                if (link && add_url && resp.length == 0) {
                    manage.autocompleteAddlink(element);
                } else {
                    response(resp);
                }
            }
        });
    },
    autocompleteAddlink: function(element) {
        $(element).autocomplete('close');
        $(element).parents('.form-group').find('.help-block').html('<div class="well">Не найдено. <a href="#" class="ac_add_link" data-target="' + $(element).attr('id') + '">Добавить?</a></div>');

        $('.ac_add_link').off('click');
        $('.ac_add_link').on('click', manage.autocompleteAddEvent);
    },
    autocompleteAddEvent: function() {
        var element = $('#' + $(this).attr('data-target'));
        manage.autocompleteAddTrigger(element);
        return false;
    },
    autocompleteAddTrigger: function(element) {
        var url = element.attr('data-add');
        var data = {};
        data.linked = element.attr('data-field');
        data[element.attr('data-model')] = {};
        data[element.attr('data-model')][element.attr('data-field')] = element.val()
        var input = element;

        $.ajax({
            url: url,
            data: data,
            method: 'get',
            success: function(msg) {
                manage.autocompleteAddForm(msg, input)
            }
        });
    },
    autocompleteAddForm: function(form, element) {
        var wellId = 'linkform-' + element.attr('id');
        $('#primary_modal_content').hide();
        $('#secondary_modal_content').show();
        $('#secondary_modal_content').html('<div class="well linkedform" id="' + wellId + '" data-link="' + element.attr('id') + '">' + form + '</div>')
        element.parents('.form-group').find('.help-block').html('');
        $('.modal-footer').hide();
        this.autocompleteHandler('#' + wellId + ' .manage-autocomplete');
        this.datepickerHandler('#' + wellId + ' .datepicker');
        this.switchery('#' + wellId + ' .js-switch-custom');
    },
    autocompleteAfterAdd: function(object) {
        $('#primary_modal_content').show();
        $('#secondary_modal_content').html('');

        var element = $('#linkfield-' + object.model + '-' + object.field);
        var value   = {
            id: object.id,
            value: object.value
        };

        manage.autocompleteSelectValue(element, value);
        element.parents('.form-group').find('.help-block').html('');
        $('.modal-footer').show();
    },
    autocompleteSelectValue: function(element, value) {
        element.val(value.value);
        var linked = element.attr('data-link');

        if (linked) {
            $('#' + linked).val(value.id);
        }

        if (element.parents('tr.filters').length) {
            element.trigger('change');
        }
    },
    autocompleteSelectEvent: function(event, ui) {
        var element = $(event.target);
        var value   = ui.item;
        manage.autocompleteSelectValue(element, value);
        return true;
    },
    autocompleteAutosearch: function() {
        $(this).autocomplete('search', 'autosearch');
    },
    popoverEvent: function() {
        return manage.popoverTrigger(this);
    },
    popoverTrigger: function(object) {
        $(object).popover('toggle');
        return false;
    }

};


$(document).ready(manage.loadEvent);