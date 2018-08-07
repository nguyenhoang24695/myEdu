/**
 * Created by hocvt on 10/29/15.
 */
$(document).ready(function(){

    var root_cat_select = $('select.root_category');
    var sub_cat_select = $('select.sub_category');
    var find_button = $('.btn_find');
    root_cat_select.on("change", function(){
        var $this = $(this);
        var selected = $this.val();
        if(!selected){
            sub_cat_select.html("<option value=''> -- Chọn chủ đề học -- </option>").selectpicker('refresh');
            return false;
        }
        root_cat_select.val(selected).selectpicker('refresh');
        // show loading
        $('.child_loading').css('visibility', 'visible');
        // get child list
        $.post(get_child_cat_link.replace('root_id', selected), {}, function(data){
            var option_tmp = '<option value="{value}" data-link="{link}" >{title}</option>';
            $('.child_loading').css('visibility', 'hidden');

            sub_cat_select.html("<option value=''> -- Chọn chủ đề học -- </option>").selectpicker('refresh');
            var child_count = data.length;
            for(var i = 0; i < child_count; i++){
                sub_cat_select.append(option_tmp.replaceArray(
                    ["{value}", "{link}", "{title}"],
                    [data[i]['id'],data[i]['link'],data[i]['name']]
                )).selectpicker('refresh');;
            }
        }, 'json').error(default_error_handle);

    });
    sub_cat_select.on("change", function(){
        var $this = $(this);
        var selected = $this.val();
        sub_cat_select.val(selected).selectpicker('refresh');
    });
    find_button.click(function(){
        var link;
        if(link = sub_cat_select.find("option:selected").attr("data-link")){
            window.location = link;
        }else if(link = root_cat_select.find("option:selected").attr("data-link")){
            window.location = link;
        }
        return false;
    });

});