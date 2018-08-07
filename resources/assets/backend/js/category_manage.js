/**
 * Created by hocvt on 9/5/15.
 */

String.prototype.repeat = function(times) {
    if(times == 0) return "";
    return (new Array(times + 1)).join(this);
};

$(document).ready(function(){

    // style by js
    $('#select_category').select2();

    var category_list = $('#category_list');

    // delete button
    category_list.on('click','a.category-delete-button',function(){
        var _this = $(this),
            del_link = _this.attr('data-link');
        bootbox.dialog({
            title: common_delete,
            message: admin_category_delete_confirm,
            buttons: {
                cancel: {
                    label: common_cancel,
                    callback: {} // do nothing
                },
                yes: {
                    label: common_yes,
                    callback: function(){
                        window.location = del_link;
                    }
                }
            }
        }).show();
        return false;
    });

    category_list.on('click','a.category-move',function(){
        var _this = $(this),
            move_link = _this.attr('data-link');
            //move_dialog = $('<div class="modal fade modal-primary"></div>');
        $('body').modalmanager('loading');

        $.get(move_link, '', function(data){
            $('body').modalmanager('loading', 'hide');
            if(data.movable){
                bootbox.dialog({
                    title: data.move_title,
                    message: data.message,
                    buttons: {
                        cancel: {
                            label: common_cancel,
                            callback: {} // do nothing
                        },
                        yes: {
                            label: common_yes,
                            callback: function(){
                                move_category(data.move_link);
                            }
                        }
                    }
                }).show();
            }else{
                bootbox.alert(data.message);
            }

        },'json').error(function(data){
            $('body').modalmanager('loading', 'hide');
            bootbox.alert('Error ' + data.status);
        });

        return false;
    });

    category_list.on('click', 'a.toggle_status', function(){
        var _this = $(this),
            _status = _this.attr('data-status'),
            _message_cf = _status == 0 ? "Active ?" : "Deactive ?";
        bootbox.dialog({
            title: _message_cf,
            message: _message_cf,
            buttons: {
                cancel: {
                    label: common_cancel,
                    callback: {} // do nothing
                },
                yes: {
                    label: common_yes,
                    callback: function(){
                        toggle_status(_this);
                    }
                }
            }
        }).show();
    });


});

function move_category(move_link){
    $.get(move_link, {}, function(data){
        if(data.moved){
            // remove old
            $.each(data.remove, function(i,v){
                $('#' + getDomId(v)).remove();
            });
            if(data.after > 0){
                // after
                var new_pos = $('#' + getDomId(data.after));

                // add new
                $(data.append).insertAfter(new_pos);
            }else{
                $('#category_list').find('tbody').prepend(data.append);
            }

        }
    }, 'json');
}

function toggle_status($a){
    $('body').modalmanager('loading');
    $.get($a.attr('data-link'), {}, function(data){
        $('body').modalmanager('loading');
        if(data.success == true){
            // change data
            $a.attr('data-status', data.status);
            // change icon
            if(data.status > 0){
                $a.find('span').css('color','green');
            }else{
                $a.find('span').css('color','red');
            }
        }
    }, 'json').error(function(data){
        $('body').modalmanager('loading');
        bootbox.alert('Error ' + data.status);
    });
}

function getDomId($id){
    return "row_" + $id;
}

