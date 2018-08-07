/**
 * Created by hocvt on 9/22/15.
 */
//$.fn.build_course = function(options){
//    var opts = $.extend( {
//        closeClass: 'closed',
//        openClass: 'open',
//    }, options );
//
//}

var builder = $('#build_course_content'),
    active_form = {};

// disable default submit for all form
builder.find('form').submit(function(){
    return false;
});
builder.find('.new_lecture_form textarea.input_lecture_description')
    .summernote({toolbar: default_summernote_toolbar, height: 100});

function add_section(){
    // hide other form
    hide_me(builder.find('.new_lecture_form'));
    hide_me(builder.find('.new_quizzes_form'));

    // show new section form
    show_me(builder.find('.new_section_form'));
}
function save_new_section(){
    var input_section_name = builder.find('.new_section_form .input_section_name');
    var input_section_description = builder.find('.new_section_form .input_section_description');
    var new_section_name = input_section_name.val();
    var new_section_description = input_section_description.val();

    if(new_section_name == ''){
        input_section_name.focus();
        return false;
    }

    // save
    $.post(build_course_content_link, {action: 'add_section',
            sub_action: 'add',
        data: {sec_title: new_section_name,
            sec_sub_title: new_section_description}},
        function(data){
            if(data.success == true){
                updateCourseContent(data.course_content);
                hide_me(builder.find('.new_section_form'));
                reset_form(builder.find('.new_section_form'));
                $.notify({message: data.message});
            }else{
                $.notify({message: data.message}, {type: 'danger'});
            }
        },
        'json'
    ).error(function(){
            $.notify({message: 'ERROR'}, {type: 'danger'});
        });

    return false;
}
function show_edit_section_form(content_id, course_content_view, btn){
    $.post(get_course_content_view, {id: content_id , view: 'a_section_form'}, function(response){
        var section_id = course_content_view.find('.a_section_view').attr('data-id');
        course_content_view.append(response);
        course_content_view.find('.btn.btn-cancel').click(function(){
            course_content_view.find('.a_section_form').remove();
            show_me(course_content_view.find('.a_section_view'));
        });
        course_content_view.find('.btn.btn-save').click(function(){
            var input_section_name = course_content_view.find('.input_section_name');
            var input_section_description = course_content_view.find('.input_section_description');
            var new_section_name = input_section_name.val();
            var new_section_description = input_section_description.val();

            if(new_section_name == ''){
                input_section_name.focus();
                return false;
            }

            // save
            $.post(build_course_content_link, {action: 'edit_section',
                    content_id: content_id,
                    sub_action: 'edit',
                    data: {id: section_id,
                        sec_title: new_section_name,
                        sec_sub_title: new_section_description}},
                function(data){
                    if(data.success == true){
                        updateCourseContent(data.course_content);
                        $.notify({message: data.message});
                    }else{
                        $.notify({message: data.message}, {type: 'danger'});
                    }
                },
                'json'
            ).error(function(){
                    $.notify({message: 'ERROR'}, {type: 'danger'});
                });

            return false;
        });
        // hide other
        hide_me(course_content_view.find('.a_section_view'));
        course_content_view.find('.a_section_content').remove();
        enable_control(btn);console.log(btn);
    }).error(function(){
        enable_control(btn);
        $.notify({message: 'ERROR'}, {type: 'danger'});
    });
}

function delete_section(id){
    bootbox.confirm("Bạn muốn xóa chương này ?", function(result){
        if(result){
            // thực hiện xóa
            $.post(build_course_content_link, {action: 'delete_section',
                sub_action: 'delete',
                content_id: id,
                data: {}},
                function(data){
                    if(data.success){
                        builder.find('#ct-' + id).remove();
                        $.notify({message: data.message});
                    }else{
                        $.notify({message: data.message}, {type: 'danger'});
                    }
            }, 'json').error(function(){
                    $.notify({message: 'ERROR'}, {type: 'danger'});
            });
        }
    });
}


function add_lecture(){
    // hide other form
    hide_me(builder.find('.new_section_form'));
    hide_me(builder.find('.new_quizzes_form'));

    //show new lecture form
    show_me(builder.find('.new_lecture_form'));
}

function save_new_lecture(){
    var input_lecture_name = builder.find('.new_lecture_form .input_lecture_name');
    var new_lecture_name = input_lecture_name.val();
    var input_lecture_sub_title = builder.find('.new_lecture_form .input_lecture_description');
    var new_lec_sub_title = input_lecture_sub_title.val();

    if(new_lecture_name == ''){
        input_lecture_name.focus();
        return false;
    }

    // save
    $.post(build_course_content_link, {action: 'add_lecture',
            sub_action: 'add',
            data: {lec_title: new_lecture_name, lec_sub_title: new_lec_sub_title}},
        function(data){
            if(data.success == true){
                updateCourseContent(data.course_content);
                hide_me(builder.find('.new_lecture_form'));
                reset_form(builder.find('.new_lecture_form'));
                $.notify({message: data.message});
            }else{
                $.notify({message: data.message}, {type: 'danger'});
            }
        },
        'json'
    ).error(function(){
            $.notify({message: 'ERROR'}, {type: 'danger'});
        });

    return false;
}

function show_edit_lecture_form(content_id, course_content_view, btn){
    $.post(get_course_content_view, {id: content_id , view: 'a_lecture_form'}, function(response){
        var lecture_id = course_content_view.find('.a_lecture_view').attr('data-id');
        course_content_view.append(response);
        course_content_view.find('.btn.btn-cancel').click(function(){
            course_content_view.find('.a_lecture_form').remove();
            show_me(course_content_view.find('.a_lecture_view'));
        });

        var input_lecture_name = course_content_view.find('.input_lecture_name');
        var input_lecture_description = course_content_view.find('.input_lecture_description');
        var input_lecture_privacy = course_content_view.find('.input_lecture_privacy');

        // show simple editor
        input_lecture_description.summernote({toolbar: default_summernote_toolbar, height: 100});

        course_content_view.find('.btn.btn-save').click(function(){

            var new_lecture_name = input_lecture_name.val();
            var new_lecture_description = input_lecture_description.val();
            var new_lecture_privacy = input_lecture_privacy.prop('checked') ? 'public' : 'student';

            if(new_lecture_name == ''){
                input_lecture_name.focus();
                return false;
            }

            // save
            $.post(build_course_content_link, {action: 'edit_lecture',
                    content_id: content_id,
                    sub_action: 'edit',
                    data: {id: lecture_id,
                        lec_title: new_lecture_name,
                        lec_sub_title: new_lecture_description,
                        access_privacy: new_lecture_privacy}},
                function(data){
                    if(data.success == true){
                        updateCourseContent(data.course_content);
                        $.notify({message: data.message});
                    }else{
                        $.notify({message: data.message}, {type: 'danger'});
                    }
                },
                'json'
            ).error(function(){
                    $.notify({message: 'ERROR'}, {type: 'danger'});
                });

            return false;
        });
        // hide other
        hide_me(course_content_view.find('.a_lecture_view'));
        course_content_view.find('.a_lecture_content').remove();
        enable_control(btn);console.log(btn);
    }).error(function(){
        enable_control(btn);
        $.notify({message: 'ERROR'}, {type: 'danger'});
    });
}

// remove a lecture

function delete_lecture(id){
    bootbox.confirm(__("delete_lecture_confirm","Bạn muốn xóa bài học này ?"), function(result){
        if(result){
            // thực hiện xóa
            $.post(build_course_content_link, {action: 'delete_lecture',
                    sub_action: 'delete',
                    content_id: id,
                    data: {}},
                function(data){
                    if(data.success){
                        builder.find('#ct-' + id).remove();
                        $.notify({message: data.message});
                    }else{
                        $.notify({message: data.message}, {type: 'danger'});
                    }
                }, 'json').error(function(){
                    $.notify({message: 'ERROR'}, {type: 'danger'});
                });
        }
    });
}

function show_edit_quizzes_form(content_id, course_content_view, btn){
    $.post(get_course_content_view, {id: content_id , view: 'a_quizzes_form'}, function(response){
        var quizzes_id = course_content_view.find('.a_quizzes_view').attr('data-id');
        course_content_view.append(response);
        course_content_view.find('.btn.btn-cancel').click(function(){
            course_content_view.find('.a_quizzes_form').remove();
            show_me(course_content_view.find('.a_quizzes_view'));
        });

        var input_quizzes_name = course_content_view.find('.input_quizzes_name');
        var input_quizzes_description = course_content_view.find('.input_quizzes_description');
        var input_quizzes_privacy = course_content_view.find('.input_quizzes_privacy');
        var input_quizzes_require   = course_content_view.find('.input_require');

        // show simple editor
        input_quizzes_description.summernote({toolbar: default_summernote_toolbar, height: 100});

        course_content_view.find('.btn.btn-save').click(function(){

            var new_quizzes_name        = input_quizzes_name.val();
            var new_quizzes_description = input_quizzes_description.val();
            var new_quizzes_privacy     = input_quizzes_privacy.prop('checked') ? 'public' : 'student';
            var new_quizzes_require;
            if(input_quizzes_require.is(":checked")){
                new_quizzes_require = 1;
            } else {
                new_quizzes_require = 0;
            }

            if(new_quizzes_name == ''){
                input_quizzes_name.focus();
                return false;
            }

            // save
            $.post(build_course_content_link, {action: 'edit_quizzes',
                    content_id: content_id,
                    sub_action: 'edit',
                    data: {id: quizzes_id,
                        quizzes_title: new_quizzes_name,
                        quizzes_sub_title: new_quizzes_description,
                        access_privacy: new_quizzes_privacy,
                        require: new_quizzes_require
                    }},
                function(data){
                    if(data.success == true){
                        updateCourseContent(data.course_content);
                        $.notify({message: data.message});
                    }else{
                        $.notify({message: data.message}, {type: 'danger'});
                    }
                },
                'json'
            ).error(function(){
                    $.notify({message: 'ERROR'}, {type: 'danger'});
                });

            return false;
        });
        // hide other
        hide_me(course_content_view.find('.a_quizzes_view'));
        course_content_view.find('.a_quizzes_content').remove();
        enable_control(btn);console.log(btn);
    }).error(function(){
        enable_control(btn);
        $.notify({message: 'ERROR'}, {type: 'danger'});
    });
}

function delete_quizzes(id){
    bootbox.confirm(__("delete_quizzes_confirm","Bạn muốn xóa bài kiểm tra này ?"), function(result){
        if(result){
            // thực hiện xóa
            $.post(build_course_content_link, {action: 'delete_quizzes',
                    sub_action: 'delete',
                    content_id: id,
                    data: {}},
                function(data){
                    if(data.success){
                        builder.find('#ct-' + id).remove();
                        $.notify({message: data.message});
                    }else{
                        $.notify({message: data.message}, {type: 'danger'});
                    }
                }, 'json').error(function(){
                    $.notify({message: 'ERROR'}, {type: 'danger'});
                });
        }
    });
}

function add_quizzes(){
    // hide other form
    hide_me(builder.find('.new_section_form'));
    hide_me(builder.find('.new_lecture_form'));

    // show new section form
    show_me(builder.find('.new_quizzes_form'));
}

function save_new_quizzes(){
    var input_quizzes_name      = builder.find('.new_quizzes_form .input_quizzes_name');
    var new_quizzes_name        = input_quizzes_name.val();
    var input_quizzes_sub_title = builder.find('.new_quizzes_form .input_quizzes_description');
    var new_quizzes_sub_title   = input_quizzes_sub_title.val();
    var input_quizzes_require   = builder.find('.new_quizzes_form .input_require');
    var new_quizzes_require     = input_quizzes_require.prop('checked') ? 1 : 0;

    if(new_quizzes_name == ''){
        input_quizzes_name.focus();
        return false;
    }

    // save
    $.post(build_course_content_link, {action: 'add_quizzes',
            sub_action: 'add',
            data: {qui_title: new_quizzes_name, qui_sub_title: new_quizzes_sub_title, require: new_quizzes_require}},
        function(data){
            if(data.success == true){
                updateCourseContent(data.course_content);
                hide_me(builder.find('.new_quizzes_form'));
                reset_form(builder.find('.new_quizzes_form'));
                $.notify({message: data.message});
            }else{
                $.notify({message: data.message}, {type: 'danger'});
            }
        },
        'json'
    ).error(function(){
            $.notify({message: 'ERROR'}, {type: 'danger'});
        });

    return false;
}


function hide_my_form(me, form_bouder){
    hide_me($(me).parents(form_bouder));
}

function show_me(elm){
    if(typeof elm == 'jQuery'){
        elm.removeClass('closed').addClass('opened');
    }
    else{
        $(elm).removeClass('closed').addClass('opened');
    }
}
function hide_me(elm){
    if(typeof elm == 'jQuery'){
        elm.removeClass('opened').addClass('closed');
    }
    else{
        $(elm).removeClass('opened').addClass('closed');
    }
}

//////////////ADD NEW ACTIONS/////////////////

$('#add_lecture_button').click(add_lecture);

builder.find('.new_lecture_form .btn_save, .new_lecture_form .btn_cancel').each(function(){
    var $this = $(this);
    //console.log($this, $this.hasClass('btn-save'));
    if($this.hasClass('btn_save')){
        $this.click(save_new_lecture);
    }else{
        $this.click(function(){hide_my_form($this, '.new_lecture_form');});
    }
});

$('#add_section_button').click(add_section);

builder.find('.new_section_form .btn').each(function(){
    var $this = $(this);
    if($this.hasClass('btn-save')){
        $this.click(save_new_section);
    }else{
        $this.click(function(){hide_my_form($this, '.new_section_form');});
    }
});

$("#add_quizzes_button").click(add_quizzes);

builder.find('.new_quizzes_form .btn_save, .new_quizzes_form .btn_cancel').each(function(){
    var $this = $(this);
    //console.log($this, $this.hasClass('btn-save'));
    if($this.hasClass('btn_save')){
        $this.click(save_new_quizzes);
    }else{
        $this.click(function(){hide_my_form($this, '.new_quizzes_form');});
    }
});


///////////END ADD NEW ACTIONS////////////////

builder.on('click', '.a_section_view i.fa-edit', function(){
    if(!disable_control(this,0,true))return false;
    var course_content_view = $(this).parents('.content_item');
    show_edit_section_form(course_content_view.attr('data-id'), course_content_view, this);
    //showDiv($(this).parents('li'), '.a_section_form');
});

builder.on('click', '.a_section_form button.btn-save', function(){
    var _form = $(this).parents('.a_section_form');
    var _view = _form.prev();

    // update content
    var _new_name = _form.find('input:first').val();
    if(_new_name == ''){
        _form.find('input:first').focus();
        return false;
    }
    _view.find('span.section_name').text(_new_name);

    showDiv(_view.parents('li'));
});
// remove a section
builder.on('click', '.a_section_view .btn_remove', function(){
    var id = $(this).parents('.content_item').attr('data-id');
    delete_section(id);
});
// toggle section content
builder.on('click', '.a_section_view .btn_toggle_content', function(){
    var id = $(this).parents('.content_item').attr('data-id');
    toggleSectionContent(id, this);
});
// toggle lecture content
builder.on('click', '.a_lecture_view .btn_toggle_content', function(){
    var id = $(this).parents('.content_item').attr('data-id');
    toggleLectureContent(id, this);
});
// toggle quizzes content
builder.on('click', '.a_quizzes_view .btn_toggle_content', function(){
    var id = $(this).parents('.content_item').attr('data-id');
    toggleQuizzesContent(id, this);
});

//
//builder.on('click', '.a_section_form button.btn-cancel', function(){
//    showDiv($(this).parents('li'));
//});
//

// Lecture title editing
builder.on('click', '.a_lecture_view i.fa-edit', function(){
    if(!disable_control(this,0,true))return false;
    var course_content_view = $(this).parents('.content_item');
    show_edit_lecture_form(course_content_view.attr('data-id'), course_content_view, this);

});

// remove a lecture
builder.on('click', '.a_lecture_view .btn_remove', function(){
    var id = $(this).parents('.content_item').attr('data-id');
    delete_lecture(id);
});

// Lecture title editing
builder.on('click', '.a_quizzes_view i.fa-edit', function(){
    if(!disable_control(this,0,true))return false;
    var course_content_view = $(this).parents('.content_item');
    show_edit_quizzes_form(course_content_view.attr('data-id'), course_content_view, this);

});

// remove a Quizzes
builder.on('click', '.a_quizzes_view .btn_remove', function(){
    var id = $(this).parents('.content_item').attr('data-id');
    delete_quizzes(id);
});


//builder.on('click', '.a_lecture_form button.btn-save', function(){
//    var _form = $(this).parents('.a_lecture_form');
//    var _view = _form.prev();
//
//    // update content
//    var _new_name = _form.find('input:first').val();
//    if(_new_name == ''){
//        _form.find('input:first').focus();
//        return false;
//    }
//    _view.find('span.lecture_name').text(_new_name);
//
//    // show hide elements
//    _form.removeClass('opened').addClass('closed');
//    _view.removeClass('closed').addClass('opened');
//});
//
//builder.on('click', '.a_lecture_form button.btn-cancel', function(){
//    var this_parent = $(this).parents('.a_lecture_form');
//    this_parent.removeClass('opened').addClass('closed');
//    this_parent.prev().removeClass('closed').addClass('opened');
//});
//
//builder.on('click', '.a_lecture_view .btn-add-content', function () {
//    showDiv($(this).parents('li.content_item'), '.a_lecture_content_add_select');
//})





function showDiv(elm, div_elm, hide_header){
    hide_header = hide_header || false;
    div_elm = div_elm || 'div.a_lecture_view, div.a_section_view';
    var all_child = elm.children('div').removeClass('opened').addClass('closed');

    if(!hide_header){
        elm.find('div.a_lecture_view, div.a_section_view').addClass('opened');
    }

    elm.find(div_elm).addClass('opened');
}

function toggleSectionContent(id, btn){
    var container = $('#ct-' + id);
    if(container.length < 1){
        $.notify({message: 'JS FALSE'}, {type: 'danger'});
        return false;
    }
    // hide if showing
    var content_view = container.find('.a_section_content');
    if(content_view.length > 0){
        content_view.remove();
        return true;
    }
    // remove other form
    container.find('.a_section_form').remove();

    // show
    if(!disable_control(btn, 0, true))return false;
    get_data({
        id: id,
        view: 'a_section_content'
    }, function(data){
        container.append(data);
        enable_control(btn);
    });

}

var toggleLectureContent = function(id, btn){
    var container = $('#ct-' + id);
    if(container.length < 1){
        $.notify({message: 'JS FALSE'}, {type: 'danger'});
        return false;
    }
    // hide if showing
    var content_view = container.find('.a_lecture_content');
    if(content_view.length > 0){
        content_view.remove();
        return true;
    }
    // remove other form
    container.find('.a_lecture_form').remove();

    // show
    if(!disable_control(btn, 0, true))return false;
    get_data({
        id: id,
        view: 'a_lecture_content'
    }, function(data){
        container.append(data);
        var lecture_editor = container.find('.a_lecture_content').lecture_editor();
        enable_control(btn);
    });

}

function toggleQuizzesContent(id, btn){
    var container = $('#ct-' + id);
    if(container.length < 1){
        $.notify({message: 'JS FALSE'}, {type: 'danger'});
        return false;
    }
    // hide if showing
    var content_view = container.find('.a_quizzes_content');
    if(content_view.length > 0){
        content_view.remove();
        return true;
    }
    // remove other form
    container.find('.a_quizzes_form').remove();

    // show
    if(!disable_control(btn, 0, true))return false;
    get_data({
        id: id,
        view: 'a_quizzes_content'
    }, function(data){
        container.append(data);
        enable_control(btn);
        sortableQuestion();
    });

}

/**
 * Cập nhật course content hiển thị lên view
 * @param data
 */
function updateCourseContent(data){

    var id = data.id,
        class_id = 'ct-' + data.id,
        ct_type = data.content_type,
        ct_title = data.title,
        ct_id = data.content_id,
        ct_sub_title = data.sub_title,
        ct_container = $('#' + class_id),
        ct_access_privacy = data.access_privacy == undefined ? 'ct-state-student' : 'ct-state-' + data.access_privacy;
    console.log(ct_access_privacy, id);

    if(ct_container.length == 0){
        // add new
        switch(ct_type) {
            case 'section':
                builder.find('.content_list').append(
                    content_template.empty_section.replaceArray([
                        '{id}',
                        '{class_id}',
                        '{content_id}',
                        '{content_title}',
                        '{content_sub_title}'
                    ], [
                        id,
                        class_id,
                        ct_id,
                        ct_title,
                        ct_sub_title
                    ])
                );
                break;

            case 'lecture':
                builder.find('.content_list').append(
                    content_template.empty_lecture.replaceArray([
                        '{id}',
                        '{class_id}',
                        '{content_id}',
                        '{content_title}',
                        '{content_sub_title}',
                        '{access_privacy}'
                    ], [
                        id,
                        class_id,
                        ct_id,
                        ct_title,
                        ct_sub_title,
                        ct_access_privacy
                    ])
                );
                break;

            case 'quizzes':
                builder.find('.content_list').append(
                    content_template.empty_quizzes.replaceArray([
                        '{id}',
                        '{class_id}',
                        '{content_id}',
                        '{content_title}',
                        '{content_sub_title}',
                        '{access_privacy}'
                    ], [
                        id,
                        class_id,
                        ct_id,
                        ct_title,
                        ct_sub_title,
                        ct_access_privacy
                    ])
                );
                break;
        }
    }else{
        // edit
        switch(ct_type){
            case 'section':
                ct_container.find('.a_section_view .section_name').text(ct_title);
                ct_container.children('div').each(function(){
                    if($(this).hasClass('a_section_view')){
                        show_me(this);
                        return;
                    }else{
                        $(this).remove();
                    }
                });
                break;
            case 'lecture':
                ct_container.find('.a_lecture_view .lecture_name').text(ct_title);
                ct_container.find('.a_lecture_view')
                    .removeClass('ct-state-free ct-state-student ct-state-public')
                    .addClass(ct_access_privacy);
                ct_container.children('div').each(function(){
                    if($(this).hasClass('a_lecture_view')){
                        show_me(this);
                        return;
                    }else{
                        $(this).remove();
                    }
                });
                break;

            case 'quizzes':
                ct_container.find('.a_quizzes_view .quizzes_name').text(ct_title);
                ct_container.find('.a_quizzes_view')
                    .removeClass('ct-state-free ct-state-student ct-state-public')
                    .addClass(ct_access_privacy);
                ct_container.children('div').each(function(){
                    if($(this).hasClass('a_quizzes_view')){
                        show_me(this);
                        return;
                    }else{
                        $(this).remove();
                    }
                });
                break;
        }

    }

}

var post_data = function(data, callback, btn){
    $.post(build_course_content_link,
        data,
        callback,
        'json'
    ).error(function(){
            if(btn)enable_control(btn);
            $.notify({message: 'ERROR'}, {type: 'danger'});
        });
}

var get_data = function(data, callback){
    $.post(get_course_content_view,
        data,
        callback
    ).error(function(){
            $.notify({message: 'ERROR'}, {type: 'danger'});
        });
}

function reset_form(elm){
    var $elm = elm instanceof jQuery ? elm : $(elm);
    $elm.find('input, textarea').val('');
    $elm.find('textarea').code('');

}
$(document).ready(function(){
    $('#import_youtube_playlist_button').click(function(){
        bootbox.prompt('Link playlist', function(_link){
            if(!_link)return;
            $.notify({message: 'Importing ...'}, {type: 'info'});
            $.post(youtube_import_link, {course_id: course_id, link: _link}, function(data){
                if(data.success){
                    window.location.reload();
                }else{
                    $.notify({message: data.message}, {type: 'danger'});
                }
            }, 'json').error(default_error_handle);

        });
    });
});
