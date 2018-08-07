/**
 * Created by hocvt on 9/12/15.
 */

var sizeBox = document.getElementById('selected_file_size'), // container for file size info
    progress = document.getElementById('upload_percent'); // the element we're using for a progress bar

var uploader = new ss.SimpleUpload({
    button: 'browse_file_upload', // file upload button
    dropzone: 'dop_file_here',
    url: upload_document_link, // server side handler
    name: 'upload_file', // upload parameter name
    progressUrl: '/helper/upload/uploadProgress.php', // enables cross-browser progress support (more info below)
    responseType: 'json',
    allowedExtensions: upload_document_exts,
    maxSize: upload_document_max_size/1024, // kilobytes
    hoverClass: 'ui-state-hover',
    focusClass: 'ui-state-focus',
    disabledClass: 'ui-state-disabled',
    multipart: true,
    data: {_token: $('meta[name="_token"]').attr('content')},
    onChange: function(fileName){
        $('#upload_notice').hide();
        $('#upload_file_name').text(fileName);
    },
    onSizeError: function(){
        $('#upload_notice').show();
    },
    onExtError: function(){
        $('#upload_notice').show();
    },
    onProgress: function(pct){
        $('#file_upload_percent').text(pct + '%');
    },
    onSubmit: function(filename, extension) {
        $(progress).css('width',0);
        this.setFileSizeBox(sizeBox); // designate this element as file size container
        this.setProgressBar(progress); // designate as progress bar
        $('#browse_file_upload,#dop_file_here').hide();
    },
    onError: function(){
        $('#browse_file_upload,#dop_file_here').show();
    },
    onComplete: function(filename, response) {
        uploader.setData({
            _token: $('meta[name="_token"]').attr('content')
        });
        if (!response || response.success == false) {
            alert(filename + 'upload failed');
            $('#browse_file_upload,#dop_file_here').show();
            return false;
        }
        // do something with response...
        $('#media_id').val(response.media_id);
        $('#save_media_button').prop('disabled', false);
        uploader.destroy();
    }
});

jQuery(document).ready(function(){

});
