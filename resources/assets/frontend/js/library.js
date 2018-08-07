/**
 * Created by hocvt on 9/10/15.
 */
$(document).ready(function(){

    // enable library tabs

    // script xử lý nút xóa video
    $('a.ajax_link_delete').click(function(){
        if(!disable_control(this))return false;
        var delete_link = this.href,
            $this = $(this);
        bootbox.confirm(__('delete_video_confirm', "Bạn muốn xóa video này ?"), function(result){
            if(result){
                $.post(delete_link, {}, function(data){
                    enable_control($this);
                    if(data.success == true){
                        $this.parents('div.media').remove();
                    }
                    bootbox.alert(data.message);
                }).error(function(){
                    enable_control($this);
                    default_error_handle();
                });
            }else{
                enable_control($this);
            }
        });
        return false;
    });

});
