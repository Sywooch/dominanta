var products = {
    loadEvent: function() {
        products.loadTrigger(this);
    },
    loadTrigger: function(obj) {
        $('.save_properties .input-group-addon').on('click', this.delPropEvent);
        $('#product-new-prop-select').on('change', this.selPropEvent);
        $('.add_prop_btn').on('click', this.savePropEvent);
    },
    delPropEvent: function() {
        products.delPropTrigger(this);
    },
    delPropTrigger: function(obj) {
        var id = $(obj).data('id');
        $('#save_property' + id).remove();
        $('#prop_list_val' + id).prop('disabled', false);
    },
    selPropEvent: function() {
        products.selPropTrigger(this);
    },
    selPropTrigger: function(obj) {
        var value = $(obj).val();

        if (value == 'new') {
            $('#product-new-prop').show();
        } else {
            $('#product-new-prop').hide();
        }
    },
    savePropEvent: function() {
        products.savePropTrigger(this);
        return false;
    },
    savePropTrigger: function(obj) {
        var propId = $('#product-new-prop-select').val();
        var newProp = (propId == 'new');

        if (newProp) {
            var propName = $('#product-new-propname').val().trim();
            propId = this.getRandId();
        } else {
            var propName = 'propId::' + propId;
            var selOption = $('#prop_list_val' + propId);
        }

        var propValue = $('#product-new-propvalue').val().trim();

        if (propName != '' && propValue != '') {
            var html = '<div class="form-group" id="save_property' + propId + '">';
            html += '<label class="control-label" for="new-product-prop-' + propId + '">';

            if (newProp) {
                html += propName;
            } else {
                html += selOption.html();
                selOption.prop('disabled', true);
            }

            html += '</label><div class="input-group">';
            html += '<input type="text" name="new_property[' + propName + ']" value="' + propValue +'" class="form-control" id="new-product-prop-' + propId + '" />';
            html += '<span class="input-group-addon" data-id="' + propId + '"><i class="fa fa-remove"></i></span></div>';

            $('.save_properties').append(html);
            $('#save_property' + propId + ' .input-group-addon').on('click', this.delPropEvent);

            $('#product-new-prop-select').val('new');
            $('#product-new-prop').show();
            $('#product-new-propname').val('');
            $('#product-new-propvalue').val('');

        }
    },
    getRandId: function() {
        return Math.floor(Math.random() * (9999999 - 0 + 1)) + 0;
    }
}

$(document).ready(products.loadEvent);