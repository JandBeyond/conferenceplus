Conferenceplus = window.Conferenceplus || {};

Conferenceplus.Upload = {

    url : '',
    basedir : '',
    id : '',
    placeholder : '',
    spinner : '',
    dataType: 'json',
    dropZone: {},
    pasteZone: {},
    formData: [{name: 'task', value: 'browse'}],
    counter : 0,
    fields : [],
    thumbnail : '',

    remove : function(id) {
        jQuery('input#' + id).val('');
        jQuery('#thumbnail' + id).attr('src', this.placeholder);
        jQuery('#img' + id).attr('src', '');
        return false;
    },

    add: function(id, filetypes) {
            'use strict';
            var url = this.url + '&filetypes=' + filetypes;
            var basedir = this.basedir;
            var dataType = this.dataType;
            var dropZone = this.dropZone;
            var pasteZone = this.pasteZone;
            var formData =   this.formData;
            var spinner =   this.spinner;

            jQuery('#file' + id).fileupload({
                url : url,
                basedir : basedir,
                dataType : dataType,
                dropZone : dropZone,
                pasteZone : pasteZone,
                formData : formData,
                spinner: spinner,
                start: function () {
                    this.thumbnail = jQuery('#thumbnail' + id).attr('src');
                    jQuery('#thumbnail' + id).attr('src', spinner);
                },
                done: function (e, data) {
                    jQuery.each(data.result.files, function (index, file) {
                        jQuery('input#' + id).val(file.name);
                        jQuery('#thumbnail' + id).attr('src', file.thumbnailUrl);
                        jQuery('#img' + id).attr('src',basedir + '/' + file.url);
                    });
                },
                fail: function(e, data) {
                    jQuery('#thumbnail' + id).attr('src', this.thumbnail);
                    alert('Upload Failed');
                }
            }).prop('disabled', !jQuery.support.fileInput)
                .parent().addClass(jQuery.support.fileInput ? undefined : 'disabled');
    }
}
