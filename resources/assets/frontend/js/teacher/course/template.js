/**
 * Created by hocvt on 9/22/15.
 */


var content_template = {

    empty_section :
        '<li class="a_section content_item" id="{class_id}" data-id="{id}">' +
        '<div class="a_section_view opened" data-id="{content_id}">' +
        '    <i class="fa fa-arrows move-handle"></i><span class="section_name"> {content_title} </span> <i class="fa fa-edit"></i>' +
            '<div class="pull-right">' +
            '    <span class="btn btn-xs btn-default btn_remove"><i class="fa fa-trash"></i></span> <span class="btn btn-xs btn-default btn_toggle_content"> ' +
                '<i class="fa fa-th-list"></i>' +
                '</span>' +
            '</div>' +
        '</div>' +
        '</li>',

    empty_lecture :
        '<li data-id="{id}" id="{class_id}" class="a_lecture content_item">' +
        '<div data-id="{content_id}" class="a_lecture_view opened {access_privacy}">' +
        '<span class="lecture_name"> {content_title} </span> ' +
        '<div class="pull-right">' +
        '   <span class="icon_buton">' +
        '       <i class="fa fa-edit" title="Sửa tiêu đề"></i>' +
        '   </span>' +
        '   <span class="icon_buton">' +
        '       <i class="fa fa-arrows move-handle" title="Sắp xếp"></i>' +
        '   </span>' +
        '   <span class="icon_buton btn_remove">' +
        '       <i class="fa fa-trash" title="Xóa"></i>' +
        '   </span>' +
        '   <span class="icon_buton btn_toggle_content">' +
        '       <i class="fa fa-th-list" title="Sửa nội dung"></i>' +
        '   </span>' +
        '</div>' +
        '</div>' +
        '</li>',
    empty_quizzes :
        '<li data-id="{id}" id="{class_id}" class="a_quizzes content_item">' +
        '<div data-id="{content_id}" class="a_quizzes_view opened {access_privacy}">' +
        '<span class="quizzes_name"> {content_title} </span> ' +
        '<div class="pull-right">' +
        '   <span class="icon_buton">' +
        '       <i class="fa fa-edit" title="Sửa tiêu đề"></i>' +
        '   </span>' +
        '   <span class="icon_buton">' +
        '       <i class="fa fa-arrows move-handle" title="Sắp xếp"></i>' +
        '   </span>' +
        '   <span class="icon_buton btn_remove">' +
        '       <i class="fa fa-trash" title="Xóa"></i>' +
        '   </span>' +
        '   <span class="icon_buton btn_toggle_content">' +
        '       <i class="fa fa-th-list" title="Sửa nội dung"></i>' +
        '   </span>' +
        '</div>' +
        '</div>' +
        '</li>',
    add_video_warning : '',
    add_video_type : '',
    add_video_max_size : '',
    add_audio_warning : '',
    add_audio_type : '',
    add_audio_max_size : '',
    add_document_warning : '',
    add_document_type : '',
    add_document_max_size : '',
    a_media_in_list: '<div><input type="radio" name="library_files[]" value="{id}"> <span> {media_title} - {media_time}</span></div>',
    simple_toolbar: [
        ['style', ['bold', 'italic', 'underline', 'clear']],
        ['para', ['ul', 'ol', 'paragraph']]
        ]


};

