/**
 * Created by hocvt on 9/20/15.
 */
/**
 * Created by hocvt on 9/12/15.
 */

var sizeBox = $('#selected_file_size'), // container for file size info
    filenameBox = $('#selected_file_name'),
    progress = $('#upload_percent'); // the element we're using for a progress bar

var uploader = new ss.SimpleUpload({
    autoSubmit: false,
    noParams: true,
    button: 'browse_button', // file upload button
    dropzone: 'dopable_zone',
    url: upload_avatar_link, // server side handler
    name: 'cou_avatar', // upload parameter name
    progressUrl: '/helper/upload/uploadProgress.php', // enables cross-browser progress support (more info below)
    responseType: 'json',
    allowedExtensions: upload_image_exts,
    maxSize: upload_image_max_size/1024, // kilobytes
    hoverClass: 'ui-state-hover',
    focusClass: 'ui-state-focus',
    disabledClass: 'ui-state-disabled',
    data: {_token: $('meta[name="_token"]').attr('content'), id: $('input#course_id').val()},
    multipart: true,
    onChange: function(fileName, extension, uploadBtn, size){
        $('#error_message_zone').hide();
        filenameBox.text(fileName);
        sizeBox.text(" ( " + size + 'kb ) ');
    },
    onSizeError: function(){
        $('#error_message_zone').show();
    },
    onExtError: function(){
        $('#error_message_zone').show();
    },
    onProgress: function(pct){
        $('#process_percent').text(pct + '%');
    },
    onSubmit: function(filename, extension) {
        //$(progress).css('width',0);
        //this.setFileSizeBox(sizeBox); // designate this element as file size container
        //this.setProgressBar(progress); // designate as progress bar
    },
    onComplete: function(filename, response) {
        //uploader.setData({
        //    _token: $('meta[name="_token"]').attr('content'), old_file: response.file
        //});
        if (!response || response.success == false) {
            alert(response.message);
            return false;
        }else{
            window.location.reload();
        }
        //// do something with response...
        //$('#input_upload_tmp_file').val(response.file_tmp);
        //$('#input_upload_file_name').val(response.file_name);
    },onError: function(filename, type, status, statusText, response, uploadBtn){
        $.notify({message: status + ":" + statusText}, {type: 'danger'});
        //console.log(filename, type, status, statusText, response, uploadBtn);
    }
});

$('#save_avatar').click(function(){uploader.submit()});

jQuery(document).ready(function(){

});
