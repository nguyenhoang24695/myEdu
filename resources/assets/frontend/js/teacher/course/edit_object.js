/**
 * Created by hocvt on 9/18/15.
 */
var add_an_audience = $('#add_an_audience');
var edit_object_form = $('#frm_edit_object');

add_an_audience.find('button').click(function(){
    var an_audience_template = edit_object_form.find('li.an_audience_tmp:last').clone(false),
        input = $(add_an_audience.find('input'));
    if(input.val() == ""){
        input.focus();
        console.log("DDD");
        return false;
    }
    an_audience_template.find('input').val(input.val()).removeAttr('disabled');
    an_audience_template.show().insertBefore(add_an_audience);
    input.val("").focus();
    return;
});

edit_object_form.on('click','i.fa-trash', function(){console.log($(this).parents('li.an_audience'));
    $(this).parents('li.an_audience').remove();
});

var add_an_goal = $('#add_an_goal');
var edit_object_form = $('#frm_edit_object');

add_an_goal.find('button').click(function(){
    var an_goal_template = edit_object_form.find('li.an_goal_tmp:last').clone(false),
        input = $(add_an_goal.find('input'));
    if(input.val() == ""){
        input.focus();
        console.log("DDD");
        return false;
    }
    an_goal_template.find('input').val(input.val()).removeAttr('disabled');
    an_goal_template.show().insertBefore(add_an_goal);
    input.val("").focus();
    return;
});

edit_object_form.on('click','i.fa-trash', function(){console.log($(this).parents('li.an_goal'));
    $(this).parents('li.an_goal').remove();
});

var add_an_requirement = $('#add_an_requirement');
var edit_object_form = $('#frm_edit_object');

add_an_requirement.find('button').click(function(){
    var an_requirement_template = edit_object_form.find('li.an_requirement_tmp:last').clone(false),
        input = $(add_an_requirement.find('input'));
    if(input.val() == ""){
        input.focus();
        console.log("DDD");
        return false;
    }
    an_requirement_template.find('input').val(input.val()).removeAttr('disabled');
    an_requirement_template.show().insertBefore(add_an_requirement);
    input.val("").focus();
    return;
});

edit_object_form.on('click','i.fa-trash', function(){console.log($(this).parents('li.an_requirement'));
    $(this).parents('li.an_requirement').remove();
});

// catch enter event
$("input.add_cou_audience,input.add_cou_requirement,input.add_cou_goal").on("keypress", function(e){
    /* ENTER PRESSED*/
    if (e.keyCode == 13) {
        /* FOCUS ELEMENT */
        $(this).next().find('.btn').trigger("click");
        return false;
    }
});

// show editor
$(document).ready(function(){
    var $editors = $('textarea.summernote_editor');
    if($editors.length > 0)
        $editors.summernote({
            height: 200,
            toolbar: default_summernote_toolbar
        });
    var $tinyeditor = $('textarea.summernote_tiny_editor');
    if($tinyeditor.length > 0)
        $tinyeditor.summernote({
            height: 200,
            toolbar: tiny_summernote_toolbar
        });
});
