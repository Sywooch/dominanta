var pagePhoto = {
    loadEvent: function() {
        pagePhoto.loadTrigger(this);
    },
    loadTrigger: function(obj) {
        this.hideDelButtons();
        $('.uploaded_photo_list button.close').on('click', this.delPhotoEvent);
    },
    delPhotoEvent: function() {
        pagePhoto.delPhotoTrigger(this);
    },
    delPhotoTrigger: function(obj) {
        $('#thumb_photo').remove();
    },
    hideDelButtons: function() {
        $('.fileupload-buttonbar a.delete, .fileupload-buttonbar input.toggle').hide();
    },
    uploadedPhoto: function(result) {
        if (result.status == 'error') {
            alert(result.message);
            return;
        }

        for (f = 0; f < result.files.length; f++) {
            var html = '<span class="img-thumbnail" id="thumb_photo">';
            html += '<img src="' + result.files[f].thumbnail + '" alt="" />';
            html += '<input type="hidden" name="photo" value="' + result.files[f].photo_id + '" />';
            html += '<button type="button" class="close" aria-label="Close" style="position: absolute;  margin-left: 15px;""><span aria-hidden="true">&times;</span></button></span>';
            $('.uploaded_photo_list').html(html);
            $('#thumb_photo button.close').on('click', this.delPhotoEvent);
        }
    },
}

$(document).ready(pagePhoto.loadEvent);