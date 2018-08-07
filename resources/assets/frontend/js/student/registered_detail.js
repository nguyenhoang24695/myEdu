/**
 * Created by hocvt on 10/23/15.
 */

// link link_student_list 

$(document).ready(function(){
    $('a#tab_course_members').on('show.bs.tab', function(){
        var $container = $('div#course_members');
        if(!$container.hasClass('loaded')){
            $.post(link_student_list, {course_id: course_id}, function(data){
                if(data.success){
                    $container.addClass('loaded');
                    var $ul = $('<ul class="media-list discussion-list"></ul>');
                    var htmlappend = '';
                    $.each(data.students, function(item, info){
                        htmlappend += '<li class="media">';
                        htmlappend += '<div class="media-left">';
                        htmlappend += '<a class="avata" href="'+info.link+'" title="'+info.name+'">';
                        htmlappend += '<img class="media-object img-circle img-responsive" src="'+info.pic+'" alt="'+info.name+'"></a>';
                        htmlappend += '</div>';
                        htmlappend += '<div class="media-body">';
                        htmlappend += '<h4 class="media-heading" title="'+info.name+'">';
                        htmlappend += '<a href="'+info.link+'">'+info.name+'</a>';
                        htmlappend += '<span class="time">'+info.registered+'</span>';
                        htmlappend += '<p class="connect"><i class="fa fa-circle"></i> online</p></h4>';
                        htmlappend += '</div>';
                        htmlappend += '</li>';
                    });
                    $container.html('<ul class="media-list discussion-list">'+htmlappend+'</ul>');
                }else{
                    bootbox.alert(__("error", 'Lỗi!'));
                }
            }, 'json').error(default_error_handle);
        }
    });

});