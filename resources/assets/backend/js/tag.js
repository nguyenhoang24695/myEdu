/**
 * Created by hocvt on 12/16/15.
 */
$(document).ready(function(){
    $('a.edit_tag_button').click(function(){
        var $this = $(this), _id = $this.data('id'),
            $content = $($this.data('content')),
            $slug = $($this.data('slug')),
            _old_content = $content.text();
        bootbox.prompt({
                title: "Khi bạn thay đổi giá trị tag, toàn bộ các dữ liệu liên quan được đánh dấu bởi tag này " +
                "cũng sẽ bị thay đổi theo, hãy thận trọng với giá trị nhập vào.",
                value: _old_content,
                callback: function(result){
                    if(result && result != ''){
                        if(result == _old_content){
                            return;
                        }
                        //POST CHANGE
                        $.post(edit_tag_link, {id: _id, content: result}, function(data){
                            if(data.success){
                                $content.text(data.tag.name);
                                $slug.text(data.tag.slug);
                            }
                            bootbox.alert(data.message);

                        }, 'json');
                    }
                }
            });
        //$.post(edit_tag_link, {id: _id,})
    });
    $('a.update_tag_slug_button').click(function(){
        var $this = $(this), _id = $this.data('id'),
            $content = $($this.data('content')),
            $slug = $($this.data('slug')),
            _old_content = $content.text();
        $.post(edit_tag_link, {id: _id}, function(data){
            if(data.success){
                $content.text(data.tag.name);
                $slug.text(data.tag.slug);
            }
            bootbox.alert(data.message);

        }, 'json');
    });
});