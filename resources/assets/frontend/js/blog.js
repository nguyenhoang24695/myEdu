$(function(){

    $(".delete_blog").click(function(event){
        event.preventDefault();
        if(confirm('Blog này sẽ được xóa khỏi danh sách.')){
            window.location.href = $(this).attr("href");
        }
    });

    if($(".summernote").length > 0){

        $('.summernote').summernote({
            height: 300,   //set editable area's height
            codemirror: { // codemirror options
            theme: 'monokai'
            },
            onImageUpload: function(files, editor, welEditable) {
               //Upload ảnh nội dung
               upLoadFile(files[0],editor,welEditable);
            }
        });
    }
    
});


//Function upload ảnh nội dung blog
function upLoadFile(file,editor,welEditable) {
    data  = new FormData();
    token = $('meta[name="_token"]').attr('content');
    data.append("file", file);
    data.append("_token", token);
    
    $.ajax({
        data: data,
        type: "POST",
        url: "/blog/saveimage",
        cache: false,
        contentType: false,
        processData: false,
        success: function(url) {
        	$('.summernote').summernote("insertImage", url);
        }
    });
}
