/**
 * Created by hocvt on 9/30/15.
 */
$(document).ready(function(){
    $('#build_course_content .my_sortable').sortable({
        onDrop: function ($item, container, _super, event) {
            $item.removeClass(container.group.options.draggedClass).removeAttr("style");
            $("body").removeClass(container.group.options.bodyClass);
            updateCourseContentOrder();
        },
        handle: '.move-handle'
    });
});

function updateCourseContentOrder(){
    var new_order = [];
    $('#build_course_content li.content_item').each(function(){
        var id = 0;
        if(id = $(this).attr('data-id')){
            new_order.push(id);
        }
    });
    $.post(build_course_content_link,{action: 'reorder', new_order: new_order},function(response){
        if(response.success == true){
            // do nothing
        }else{
            // error => refresh to see current order
            bootbox.alert(response.message);
        }

    }, 'json').error(default_error_handle);
    console.log(new_order);
}