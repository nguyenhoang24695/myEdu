$(function(){
    // update view status
    $.post(view_status_link, {course_id: current_course_id}, function(data){
        if(data.success){
            for(var cc_id in data.status){
                var view_stt = data.status[cc_id];
                if(view_stt == 0){
                    $('#' + cc_id).width('50%');
                }else if(view_stt == 1){
                    $('#' + cc_id).width('100%');
                }

                var pc = parseInt(data.progress*100) + '%';
                $('#course_progress_text').html(pc);
                $('#course_progress').width(pc);
                
                //console.log(view_stt, cc_id, $('#' + cc_id));
            }
        }
    }, 'json').error(default_error_handle);
    // auto send log
    var send_log_interval,send_view_log = function(){
        $.post(view_status_log_link, {content_id: current_content_id, view_token: view_token}, function(data){
            if(data.success == true){
                if(data.finished){
                    $('#cc_status_' + current_content_id).width('100%');
                    clearInterval(send_log_interval);
                }else if(data.stop_log){
                    clearInterval(send_log_interval);
                }
            }
        }, 'json').error(function(){
            $.notify({
                message : "Lỗi kết nối"
            }, {type : 'warning'});
        });
    }
    if(typeof current_content_id !== 'undefined'){
        send_log_interval = setInterval(send_view_log, step_log*1000);
    }

	$(".dis_title").click(function(){
        $(this).addClass('hide');
		$(this).parents("form").find(".ishide").removeClass('hide');
        $(this).parents("form").find(".content").focus();
	});
	$(".btn_edus_back").click(function(){
		$(this).parents("form").find(".ishide").addClass('hide');
        $(this).parents("form").find(".dis_title").removeClass('hide');
	});

    //Gửi thảo luận
	$('#lecture_discussions').ajaxForm({

        success: function(data){

            var u_info = {
                name : $(".avata").attr('title'),
                src  : $(".avata").attr('href'),
                img  : $(".avata").find('img').attr('src')
            };

            var html  =  genHtmlDiscussions(data,u_info,'discussion');

            var html_full  = '<li class="media animated zoomIn">'+html+'</li>';
            if($('#list_discussion').find('li').length > 0){
                var firstChild = $('#list_discussion li:first-child');
                $(html_full).insertBefore(firstChild);
            } else {
                $("#list_discussion").append(html_full);
            }

			//Reset lại các attribute
			$('#lecture_discussions').resetForm();
			$(".discussions_err").hide();
			$(".discussions_err").text('');
			$('[data-toggle="tooltip"]').tooltip()

        },
        error: function(data){
        	var errors = data.responseJSON;
            $(".discussions_err").text(errors.message);
            $(".discussions_err").show();
        }

    });

});

/**
* @param: obj,parent_id,content_id => id của từng bài học
* 
**/
function reply(obj,parent_id,content_id){
    if($(obj).parents('.media-body').find('.media_reply').length > 0){
        $(obj).parents('.media-body').find('.media_reply').show();
    } else {
        var reply = $("#frm_reply").html();
        $(obj).parents('.media-body').append(reply);
        $(".parent_id").val(parent_id);
        $(".content_id").val(content_id);
        FromReplyDiscussions(obj);
    }
}

/**
* @param: obj
* 
**/
function cancel_reply(obj){
    $(obj).parents('.media_reply').hide();
    $(obj).parents('.media_reply').next('.activity').show();
}

/**
* @param: obj
* @return : repon
**/
function FromReplyDiscussions(obj){
    //Trả lời thảo luận
    $('.frm_reply').ajaxForm({
        success: function(data){
            var u_info = {
                name : $(".avata").attr('title'),
                src  : $(".avata").attr('href'),
                img  : $(".avata").find('img').attr('src')
            };
            var html  =  genHtmlDiscussions(data,u_info,'reply');
            var html_full  = '<div class="media animated zoomIn">'+html+'</div>';
            $(html_full).insertBefore($(obj).parents('.media').find('.media_reply'));

            //Reset lại các attribute
            $('.frm_reply').resetForm();
            $(".err_reply").hide();
            $(".err_reply").text('');
            $('[data-toggle="tooltip"]').tooltip()
        },
        error: function(data){
            var errors = data.responseJSON;
            $(".err_reply").text(errors.message);
            $(".err_reply").show();
        }
    });
}

/**
* @param: json => data, html => u_info.
* @return : html
**/
function genHtmlDiscussions(data,u_info,type){
    var data   = data;
    var u_info = u_info;

    var html  = '<div class="media-left"><a class="avata" href="'+u_info.src+'" title="'+u_info.name+'"><img src="'+u_info.img+'" alt="'+u_info.name+'" class="media-object img-circle img-responsive"></a></div>';
        html += '<div class="media-body">';
        html += '<h4 class="media-heading" title="'+u_info.name+'"><a href="'+u_info.src+'">'+u_info.name+'</a><span class="time" style="margin-left: 10px;">vài giây trước</span></h4>';
        html += '<h6>'+data.title+'</h6>';
        html += '<p class="content">'+data.content+'</p>';
        html += '<p class="activity">';
        if(type == "discussion"){
            html += '<span class="reply" onclick="reply(this,'+data.id+','+data.lec_id+')"><i class="fa fa-reply-all"></i> Trả lời</span>';
            html += '<span class="like" onclick="voteUp(this,'+data.id+')"><i class="fa fa-thumbs-o-up"></i> Thích</span>';
            html += '<span class="count">(<span class="numlike">0</span>)</span>';
        }
        html += '<span class="report" onclick="report(this,'+data.id+')" data-toggle="tooltip" data-placement="right" title="Báo cáo vi phạm"><i class="fa fa-flag"></i></span>';
        html += '</p>';
        html += '</div>';

    return html;
}

/**
* @param: obj,id
* @return : jcon data
**/
function voteUp(obj,id){
    var _this = obj;
    if(!disable_control(_this,0,true))return false;

    $.ajax({
        url : "/discussion/vote",
        data : {id:id},
        method : "POST",
        success : function(data){
            $(_this).next('.count').text('('+data.vote+')');
        },
        error: function(data){
            var errors = data.responseJSON;
            alert(errors.message);
        }
    });

}

/**
* @param: obj,id
* @return : jcon data
**/
function report(obj,id){
    var _this = obj;
    if(!disable_control(_this,0,true))return false;

    $.ajax({
        url : "/discussion/report",
        data : {id:id},
        method : "POST",
        success : function(data){
            $(_this).addClass('report_active');
            $(_this).attr('data-original-title', data.message);
        },
        error: function(data){
            var errors = data.responseJSON;
            alert(errors.message);
        }
    });
}

////// INFO
var $info_tab = $('#lecture_attachments');
$('a#toggle_lecture_attachments').on('show.bs.tab', function(e){
    if($info_tab.find('div.list_external_source').length){ // loaded
        return;
    }
    $.get(link_to_lecture_info,function(data){
        $info_tab.html(data);
    }).error(default_error_handle);

});

////// NOTES
var $note_list = $('#lecture_notes');
$('a#toggle-note-tab').on('show.bs.tab', function(e){
    if($note_list.find('h4.notes-list-header').length){ // loaded
        return;
    }
    $.get(link_my_note_list,function(data){
        if(data.success){
            $note_list.html(data.html);
            prepare_note_list_action();
        }else{
            bootbox.alert(data.message);
        }
    }, 'json').error(default_error_handle);

});

var prepare_note_list_action = function(){
    var $list_group = $note_list.find('.group-list');
    var $demo_item = $note_list.find('.demo-item');
    //
    $note_list.find('.saved-item .note-content').editable(editable_options);

    // add
    $demo_item.click(function(){
        $.post(link_my_note_list, {action: 'create'}, function(data){
            if(data.success){
                // clone
                var cloned = $demo_item.clone();
                cloned.removeClass('demo-item').addClass('saved-item').attr('data-id',data.note.id);
                // append
                cloned.insertAfter($demo_item);
                // assign value
                cloned.find('.note-timer').html(data.note.created_at);
                // assign action
                cloned.find('.note-content').attr('data-pk', data.note.id).html("").on('init', function(e, editable){
                    //alert(1);
                    setTimeout(function(editable){
                        //alert(2);
                        editable.show();
                    }, 500, editable)
                }).editable(editable_options);

            }else{
                bootbox.alert(data.message);
            }
        }).error(default_error_handle);

    });

    // save note


    // remove note


}
var editable_options = {
    ajaxOptions: {
        type: 'post'
    },
    mode: 'inline',
    type: 'textarea',
    emptytext: 'Chưa có nội dung ghi chú',
    placeholder: 'Ghi chú điều gì đó...',
    params: {
        action : 'edit'
    },
    name: 'content',
    url: link_my_note_list
};

$note_list.on('click', 'span.remove-note', function(event){
    var note_div = $(this).parents('.saved-item');
    var id = note_div.attr('data-id');
    bootbox.confirm('Xóa ghi chú ?', function(result){
        if(result){
            $.post(link_my_note_list, {action: 'delete', id: id}, function(data){
                if(data.success){
                    note_div.remove();
                    bootbox.alert(data.message);
                }else{
                    bootbox.alert(data.message);
                }
            }).error(default_error_handle);
        }
    });

});

var change_autonext_status = function(a_auto){
    a_auto = a_auto || true;

}