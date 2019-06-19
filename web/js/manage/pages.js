$(document).ready(function(){
console.log('pages');

    $('#page-pid').on('change', function(){
        var elem = this;

        $.ajax({
            url: "/manage/site/pages/address?page_id=" + $(elem).val(),
            method: 'get',
            success: function(data) {
                $('.field-page-slug .input-group-addon').html(data);
            }
        });
    });

    $('#css-select').on('change', function() {
        var css_id = $(this).val();
        var css_name = $(this).find('option[value=' + css_id + ']').html();
        var css_pos= $('#page_css_list li').length + 1;

        var html = '<li class="list-group-item text-left" id="page_css_' + css_id + '">';
        html += '<i class="fa fa-reorder" style="cursor: move"></i> &nbsp; <span style="float: right">';
        html += '<button type="button" class="close" aria-label="Close" data-css="' + css_id +'">';
        html += '<span aria-hidden="true">&times;</span></button></span>' + css_name;
        html += '<input type="hidden" name="css[' + css_id + ']" value="' + css_pos + '" /></li>';

        $('#page_css_list').append(html);
        $('#no_page_css').addClass('hidden');
        $('#emp_css_sel').prop('selected', true);
        $(this).find('option[value=' + css_id + ']').prop('disabled', true);
        $('#page_css_' + css_id + ' button').on('click', delCss);
    });

    function delCss() {
        var css_id = $(this).data('css');
        $('#page_css_' + css_id).remove();
        $('#css-select option[value=' + css_id + ']').prop('disabled', false);

        if ($('#page_css_list li').length) {
            resortCss();
        } else {
            $('#no_page_css').removeClass('hidden');
        }
    }

    function resortCss() {
        $('#page_css_list input').each(function(idx, el) {
            var sort = idx + 1;
            $(this).val(sort);
        })
    }

    $("#page_css_list").sortable({
        item: "> li",
        handle: ".fa-reorder",
        update: resortCss
    }).disableSelection();

    $('#page_css_list button').on('click', delCss);

    $('#js-select').on('change', function() {
        var js_id = $(this).val();
        var js_name = $(this).find('option[value=' + js_id + ']').html();
        var js_ext  = $(this).find('option[value=' + js_id + ']').data('ext');
        var js_pos= $('#page_css_list li').length + 1;
        var js_def_pos = 3;

        if (!js_ext) {
            var html = '<li class="list-group-item text-left js-reorder js-item" id="page_js_' + js_id + '">';
            html += '<i class="fa fa-reorder" style="cursor: move"></i>';
        } else {
            var html = '<li class="list-group-item text-left js-item" id="page_js_' + js_id + '">';
        }

        html += '&nbsp; <span style="float: right">';
        html += '<button type="button" class="close" aria-label="Close" data-js="' + js_id +'" data-pos="' + js_def_pos + '">';
        html += '<span aria-hidden="true">&times;</span></button></span>' + js_name;
        html += '<input type="hidden" name="js[' + js_id + ']" value="' + js_def_pos + '|' + js_pos + '" /></li>';

        $('#page_js_list_' + js_def_pos).append(html);
        $('#no_page_js_' + js_def_pos).addClass('hidden');
        $('#emp_js_sel').prop('selected', true);
        $(this).find('option[value=' + js_id + ']').prop('disabled', true);
        $('#page_js_' + js_id + ' button').on('click', delJs);
    });

    function delJs() {
        var js_id  = $(this).data('js');
        var js_pos = $(this).data('pos');
        $('#page_js_' + js_id).remove();
        $('#js-select option[value=' + js_id + ']').prop('disabled', false);
        resortJs();
    }

    $(".page_js_list").sortable({
        item: "> li.js-reorder",
        handle: ".fa-reorder",
        update: resortJs,
        connectWith: ".page_js_list"
    }).disableSelection();

    function resortJs() {
        $(".page_js_list").each(function() {
            var pos = $(this).data('pos');

            if ($(this).find('.js-item').length) {
                $('#no_page_js_' + pos).addClass('hidden');

                $(this).find('.js-item input').each(function(idx, el) {
                    var sort = idx + 1;
                    $(this).val(pos + '|' + sort);
                })
            } else {
                $('#no_page_js_' + pos).removeClass('hidden');
            }
        })
    }

    $('.page_js_list button').on('click', delJs);
});