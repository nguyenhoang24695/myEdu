/**
 * Created by hocvt on 10/2/15.
 */

var editor_array = [];

(function ( $ ) {

    $.fn.lecture_editor = function(options) {
        options = options || {};
        var $this = this;
        var lecture_contents = options.lecture_contents || {};
        var instance_time = $.now();
        var content_id = this.parents('.content_item').attr('data-id');
        var add_button = this.find('.btn_add_lecture_content');

        var attachment_list = this.find('.lecture_external_source_list');
        var attachment_form = attachment_list.find('.list-group-item:first');

        var add_content = function(content_type){

        }

        var remove_content = function($content_container){

        }

        var get_lecture_contents = function(){
            var contents = {};
            $this.find('div.media').each(function(i,j){
                var media_type = $(j).attr('data-type');
                if(media_type){
                    contents[media_type] = true;
                }
            });
            return contents;
        }

        var update_add_button = function(update){
            update = update || false;
            if(update){
                lecture_contents = get_lecture_contents();
            }
            add_button.find('ul li a').each(function(i,j){
                var data_type = $(j).attr('data-add');console.log(data_type);
                if(lecture_contents[data_type]
                    && (data_type == 'video' || data_type == 'document' || data_type == 'audio')){
                    $(j).addClass('disabled');
                }else{
                    $(j).removeClass('disabled');
                }
            });
        }

        var add_media = function(media_type){
            // valid input
            if(lecture_contents[media_type]){
                $.notify({message: "Bạn không thể thêm dữ liệu loại này"});
                return false;
            }
            //
            switch(media_type){
                case 'video':
                    var view_link = '';
                    var upload_link = '';
                    var search_link = '';
                    var save_link = '';
                    $.post(link_lecture_media_form, {media_type: media_type}, function(data){
                        var new_data = $(data);
                        new_data.find('input.insert_from_my_library').select2({
                            ajax: {
                                url: search_my_video_link,
                                dataType: 'json',
                                delay: 250,
                                type: 'post',
                                data: function (term) {
                                    return {
                                        keyword: term
                                    };
                                },
                                processResults: function (data, params) {
                                    var results = [];
                                    var raw_results = data.videos;
                                    for(var i = 0; i < raw_results.length; i++){
                                        results.push({
                                            id: raw_results[i].id,
                                            text: raw_results[i].vid_title + " (" + raw_results[i].created_at + ")",
                                            selected_text: raw_results[i].vid_title
                                        });
                                    }
                                    return {
                                        results: results
                                    };
                                }
                            },
                            minimumInputLength: 1,
                            formatSelection: function (data, container, escapeMarkup) {
                                return data ? escapeMarkup(data.selected_text) : undefined;
                            }
                        });
                        // save
                        new_data.find('.btn.assign_video_to_lecture').click(function(){
                            var selected_video = new_data.find('input.insert_from_my_library').val();
                            //console.log(selected_video);
                            if(!selected_video)return false;
                            if(!disable_control(this))return false;
                            var data = {
                                content_id: content_id,
                                action: 'update_lecture',
                                sub_action: 'update',
                                data: {
                                    add_media: true,
                                    media: {
                                        type: 'video',
                                        id: selected_video
                                    }

                                }
                            };
                            $.post(build_course_content_link, data, function(response){
                                if(response.success == false){
                                    $.notify({
                                        message: response.message
                                    }, {
                                        type: 'danger'
                                    });
                                }else{
                                    reload_media_content(new_data, response);
                                }
                            }, 'json').error(function(){
                                enable_control(new_data.find('.btn.assign_video_to_lecture'));
                                default_error_handle();
                            });
                        });
                        $this.find('.a_lecture_media').append(new_data);
                        update_add_button(true);
                    }).error(function(){
                        default_error_handle();
                    });
                    break;
                case 'document':
                    var view_link = '';
                    var upload_link = '';
                    var search_link = '';
                    var save_link = '';
                    $.post(link_lecture_media_form, {media_type: media_type}, function(data){
                        var new_data = $(data);
                        new_data.find('input.insert_from_my_library').select2({
                            ajax: {
                                url: search_my_document_link,
                                dataType: 'json',
                                delay: 250,
                                type: 'post',
                                data: function (term) {
                                    return {
                                        keyword: term
                                    };
                                },
                                processResults: function (data, params) {
                                    var results = [];
                                    var raw_results = data.documents;
                                    for(var i = 0; i < raw_results.length; i++){
                                        results.push({
                                            id: raw_results[i].id,
                                            text: raw_results[i].doc_title + " (" + raw_results[i].created_at + ")",
                                            selected_text: raw_results[i].doc_title
                                        });
                                    }
                                    return {
                                        results: results
                                    };
                                }
                            },
                            minimumInputLength: 1,
                            formatSelection: function (data, container, escapeMarkup) {
                                return data ? escapeMarkup(data.selected_text) : undefined;
                            }
                        });
                        // save
                        new_data.find('.btn.assign_document_to_lecture').click(function(){
                            var selected_document = new_data.find('input.insert_from_my_library').val();
                            console.log(selected_document);
                            if(!selected_document)return false;
                            disable_control(this);
                            var data = {
                                content_id: content_id,
                                action: 'update_lecture',
                                sub_action: 'update',
                                data: {
                                    add_media: true,
                                    media: {
                                        type: 'document',
                                        id: selected_document
                                    }

                                }
                            };
                            $.post(build_course_content_link, data, function(response){
                                if(response.success == false){
                                    $.notify({
                                        message: response.message
                                    }, {
                                        type: 'danger'
                                    });
                                }else{
                                    reload_media_content(new_data, response);
                                }
                            }, 'json').error(function(){
                                enable_control(new_data.find('.btn.assign_video_to_lecture'));
                                default_error_handle();
                            });
                        });
                        $this.find('.a_lecture_media').append(new_data);
                        update_add_button(true);
                    }).error(function(){
                        default_error_handle();
                    });
                    break;
                case 'other':
                    // attachment
                    attachment_list.append(attachment_form.clone());

                    break;
            }


        };

        var reload_media_content = function(form_html, new_data){
            form_html.remove();
            $.post(link_lecture_media_view ,{media_id: new_data.new_media_id, media_type: new_data.new_media_type}, function(data){
                $this.find('.a_lecture_media').append(data);
            }).error(default_error_handle);
        }

        var assign_delete_button = function(){
            $this.on('click', 'span.media-delete', function(){
                var removing = $(this).parent('.media');
                var media_type = $(this).parent('.media').attr('data-type');
                bootbox.confirm("Bạn muốn xóa nội dung này ?", function(result){
                    if(result){
                        // thực hiện xóa
                        var data = {content_id: content_id,
                            action: 'update_lecture',
                            sub_action: 'update',
                            data: {
                                remove_media: true,
                                media: {
                                    type: media_type
                                }

                            }};
                        $.post(build_course_content_link, data,
                            function(data){
                                if(data.success){
                                    removing.remove();
                                    update_add_button(true);
                                    $.notify({message: data.message});
                                }else{
                                    $.notify({message: data.message}, {type: 'danger'});
                                }
                            }, 'json').error(function(){
                                $.notify({message: 'ERROR'}, {type: 'danger'});
                            });
                    }
                });
            });
        }

        var assign_attachment_buttons = function(){
            // remove/cancel
            attachment_list.on('click', '.btn_delete_source,.btn_cancel', function(){
                var attachment_container = $(this).parents('.list-group-item');
                var attachment_id = attachment_container.data('id');

                if(!attachment_id){
                    attachment_container.remove();
                }else{
                    bootbox.confirm("Bạn muốn xóa tài liệu đính kèm này ?", function(result) {
                        if(!result)return true;
                        var data = {content_id: content_id,
                            action: 'update_lecture',
                            sub_action: 'update',
                            data: {
                                remove_attachment: true,
                                attachment_id: attachment_id
                            }};
                        $.post(build_course_content_link, data,
                            function(data){
                                if(data.success){
                                    if(data.removed){
                                        attachment_container.remove();
                                    }
                                    $.notify({message: data.message});
                                }else{
                                    $.notify({message: data.message}, {type: 'danger'});
                                }
                            }, 'json').error(function(){
                                $.notify({message: 'ERROR'}, {type: 'danger'});
                            });
                    });
                }
            }).on('click', '.btn_save', function(){
                var attachment_container = $(this).parents('.list-group-item');

                var data = {content_id: content_id,
                    action: 'update_lecture',
                    sub_action: 'update',
                    data: {
                        add_attachment: true,
                        attachment: {
                            title: attachment_container.find('.source_title').val(),
                            source_type: attachment_container.find('.source_type').val(),
                            content: attachment_container.find('.source_content').val()
                        }
                    }};
                $.post(build_course_content_link, data,
                    function(data){
                        if(data.success){
                            if(data.attachment_added && data.new_attachment){
                                var new_attachment = data.new_attachment;
                                // upadate attachment_container
                                attachment_container.data('id', new_attachment.id);
                                attachment_container.find('.view_attachment_title').html(new_attachment.title);
                                attachment_container.find('.view_attachment_content').html(new_attachment.content);

                            }
                            $.notify({message: data.message});
                        }else{
                            $.notify({message: data.message}, {type: 'danger'});
                        }
                    }, 'json').error(function(){
                        $.notify({message: 'ERROR'}, {type: 'danger'});
                    });
            });
        }

        function init(){
            // get lecture contents
            update_add_button(true);
            assign_delete_button();
            assign_attachment_buttons();
            console.log(1,lecture_contents);
            add_button.on('click', 'ul.dropdown-menu a', function(){
                var $this = $(this);
                if($this.hasClass('disabled')){
                    return false;
                }
                add_media($this.attr('data-add'));
            });
        }

        init();

        return false;
    };

}( jQuery ));
