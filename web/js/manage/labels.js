var productLabel = {
    loadEvent: function() {
        productLabel.loadTrigger(this);
    },
    loadTrigger: function(obj) {
        $('.all_labels_list span.label').on('click', this.addLabelEvent);
        $('.current_labels_list span.label').on('click', this.delLabelEvent);
    },
    addLabelEvent: function() {
        productLabel.addLabelTrigger(this);
    },
    addLabelTrigger: function(obj) {
        var id = $(obj).data('id');

        var label = '<span class="label label-default" id="cur_label_' + id + '" data-id="' + id + '">';
        label += $(obj).html() + '<input type="hidden" name="labels[' + id + ']" value="1" /></span>';

        $('.current_labels_list').append(label);
        $('#cur_label_' + id).on('click', this.delLabelEvent);
        $(obj).addClass('hidden');
    },
    delLabelEvent: function() {
        productLabel.delLabelTrigger(this);
    },
    delLabelTrigger: function(obj) {
        var id = $(obj).data('id');

        $('#add_label_' + id).removeClass('hidden');
        $(obj).remove();
    }
}

$(document).ready(productLabel.loadEvent);