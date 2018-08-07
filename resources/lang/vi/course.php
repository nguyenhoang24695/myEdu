<?php
/**
 * Created by PhpStorm.
 * User: hocvt
 * Date: 9/19/15
 * Time: 08:44
 */
return [
    'course' => 'Khóa học',
    'info' => 'Thông tin khóa học',
    'external_source' => 'Tư liệu ngoài',
    'requirement' => [
        'title' => 'Học sinh nào nên học khóa học này',
        'description' => 'Để nâng cao chất lượng đào tạo bạn hay cho biết đối tượng nào phù hợp với khóa học này.',
        'alert_save' => ''
    ],
    'alert' => [
        'no_content' => 'Không có nội dung',
        'info_saved' => 'Đã lưu thông tin khóa học',
        'error_save' => 'Lỗi lưu thông tin',

    ],
    'status' => [
        'drafting_title' => 'Khóa học do bạn tạo chưa được đăng',
        'public_title' => 'Khóa học do bạn đã được đăng',
        'free_title' => 'Khóa học do bạn đã được đăng miễn phí',
    ],
    'introduction' => 'Giới thiệu về khóa học',
    'learning_capacity' => [
        'new'       => 'Học mới',
        'good'      => 'Khá giỏi',
        'middle'    => 'Trung bình',
        'bad'       => 'Kém',
        'all'       => 'Tất cả',
    ],

    'section_number' => 'Chương :number :',
    'section_title' => 'Tiêu đề chương',
    'section_description' => 'Mô tả ngắn',
    'lecture_number' => 'Bài học :number :',
    'lecture_title' => 'Tiêu đề bài học',
    'add_new' => 'Tạo khóa học mới',
    'add_content'   => 'Thêm nội dung',
    'add_video' => 'Video',
    'add_audio' => 'Audio',
    'add_document' => 'Document',
    'add_text'  => 'Text',
    'add_mix'   => 'Trộn',

    // title
    'name' => 'Tên khóa học',
    'title' => 'Tiêu đề khóa học',
    'description' => 'Mô tả khóa học',
    'category' => 'Danh mục',

    // status/privacy
    'edit_status' => [
        'editing' => 'Đang sửa',
        'public' => 'Đã xuất bản',
    ],
    'course_privacy' => [
        'student' => 'Đã tham gia khóa học', // only student can lean
        'free'  => 'Miễn phí', // all user can learn
        'company' => 'Công ty', // only company user can learn
    ],

    // error message
    'building' => [
        'error' => [
            'section' => [
                'title' => 'Tiêu đề quá ngắn',
                'sub_title' => 'Mô tả ngắn không được quá 300 ký tự'
            ],
            'lecture' => [
                'title' => 'Tiều đề bài quá ngắn',
                'sub_title' => 'Mô tả chương không được quá 100 ký tự',
                'delete' => 'Không xóa được bài học',
            ],
            'quizzes' => [
                'title'     => 'Tiều đề bài quá ngắn',
                'sub_title' => 'Mô tả chương không được quá 100 ký tự',
                'delete'    => 'Không xóa được bài kiểm tra',
            ],
        ],
        'alert' => [
            'section' => [
                'saved' => 'Lưu chương mới thành công',
                'deleted' => 'Đã xóa chương',
            ],
            'lecture' => [
                'saved' => 'Lưu thông tin bài học thành công',
                'deleted' => 'Đã xóa bài học',
                'changed' => 'Thay đổi thông tin bài học thành công',
            ],
            'quizzes'   => [
                'saved' => 'Lưu thông tin bài kiểm tra thành công',
                'deleted' => 'Đã xóa bài kiểm tra',
                'changed' => 'Thay đổi thông tin bài kiểm tra thành công'
            ]
        ],
    ],
    'student' => [
        'course_studying' => 'Các khóa học đang theo học.',
    ],


    // money
    'buy_course' => 'Mua khóa học',

];