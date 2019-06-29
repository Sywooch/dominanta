$(document).ready(function(){
    $(".page_prop_list").sortable({
        item: "> li.prop-item",
        handle: ".fa-reorder",
        update: resortJs,
        connectWith: ".page_prop_list"
    }).disableSelection();

    function resortJs() {
        $(".page_prop_list").each(function() {
            var pos = $(this).data('pos');

            $(this).find('.prop-item input').each(function(idx, el) {
                if (parseInt(pos) > 0) {
                    var sort = idx + 1;
                } else {
                    sort = pos;
                }

                $(this).val(sort);
            })
        })
    }
});