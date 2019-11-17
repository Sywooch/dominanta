var productPhoto = {
    loadEvent: function() {
        productPhoto.loadTrigger(this);
    },
    loadTrigger: function(obj) {
        this.hideDelButtons();
        this.sortable();
        $('.uploaded_photo_list button.close').on('click', this.delPhotoEvent);
    },
    delPhotoEvent: function() {
        productPhoto.delPhotoTrigger(this);
    },
    delPhotoTrigger: function(obj) {
        var id = $(obj).data('id');
        $('#thumb_photo' + id).remove();
        this.resortPhotos();
    },
    hideDelButtons: function() {
        $('.fileupload-buttonbar a.delete, .fileupload-buttonbar input.toggle').hide();
    },
    uploadedPhoto: function(result) {
        for (f = 0; f < result.files.length; f++) {
            $('tbody.files tr.template-upload').remove();
            var html = '<span class="img-thumbnail" id="thumb_photo' + result.files[f].photo_id + '">';
            html += '<img src="' + result.files[f].thumbnail + '" alt="" data-id="' + result.files[f].photo_id + '" />';
            html += '<input type="hidden" name="photo[' + result.files[f].photo_id + ']" id="sort_photo' + result.files[f].photo_id + '" />';
            html += '<button type="button" class="close" aria-label="Close" style="position: absolute;  margin-left: -15px; margin-top: 0px;" data-id="' + result.files[f].photo_id + '"><span aria-hidden="true">&times;</span></button></span>';
            $('.uploaded_photo_list').append(html);
            $('#thumb_photo' + result.files[f].photo_id + ' button.close').on('click', this.delPhotoEvent);
        }

        this.resortPhotos();
    },
    sortable: function() {
        $(".uploaded_photo_list").sortable({
            item: "> span.img-thumbnail",
            //handle: "img",
            update: productPhoto.resortPhotos,
        }).disableSelection();
    },
    resortPhotos: function() {
        $(".uploaded_photo_list img").each(function(idx, el) {
            var id = $(this).data('id');
            var pos = idx + 1;
            $('#sort_photo' + id).val(pos);
        });
    }
}

$(document).ready(productPhoto.loadEvent);