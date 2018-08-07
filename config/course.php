<?php
/**
 * Created by PhpStorm.
 * User: hocvt
 * Date: 9/18/15
 * Time: 15:11
 */
return [
    'build_actions' => [
        'editObject' => 'doi_tuong',// action_name => name on link
        'editContent' => 'noi_dung_khoa_hoc',
        'editSummary' => 'thong_tin',
        //'editAbout' => 'gioi_thieu',
        'editAvatar' => 'anh_dai_dien',
        'editIntroVideo' => 'video_gioi_thieu',
        'editPrivacy'       => 'che_do_rieng_tu',
        'editPrice' => 'thong_tin_gia_ban',
        'editInfo'  => 'thong_tin_co_ban',
    ],
    'default_build_action' => 'editObject',
    'languages' => [ // reference http://stackoverflow.com/questions/3191664/list-of-all-locales-and-their-short-codes
        'vi' => 'Việt Nam',
        'en' => 'English',
    ],
    'learning_capacity' => [
        'new'       => 2,
        'good'      => 8,
        'middle'    => 6,
        'bad'       => 4,
        'all'       => 0,
    ],
    'title' => [
        ''
    ],

    'init' => [
        'chapter_name' => 'Tên chương :number',
        'lecture_name' => 'Tên bài giảng :number',
    ],


    'content_actions' => [
        //action_name => content_type
        ///////SECTION
        'add_section' => 'section',
        'new_section' => 'section',
        'update_section' => 'section',
        'edit_section' => 'section',
        'delete_section' => 'section',
        'remove_section' => 'section',
        ///////LECTURE
        'add_lecture' => 'lecture',
        'new_lecture' => 'lecture',
        'update_lecture' => 'lecture',
        'edit_lecture' => 'lecture',
        'delete_lecture' => 'lecture',
        'remove_lecture' => 'lecture',
        //////QUIZZES
        'add_quizzes'      => 'quizzes',
        'new_quizzes'      => 'quizzes',
        'update_quizzes'   => 'quizzes',
        'edit_quizzes'     => 'quizzes',
        'delete_quizzes'   => 'quizzes',
        'remove_quizzes'   => 'quizzes'
    ],
    // important, khi them loai noi dung can them vao day. xem link ... de them chi tiet ve kha nang mo rong
    // loai noi dung bai giang co the chen.
    'content_types' => [
        'section'       => 'App\Models\Section',
        'lecture'       => 'App\Models\Lecture',
        'quizzes'       => 'App\Models\Quizzes'
        //'examination' => 'App\Models\Examination',// can phat trien them
    ],
    'content_type_commands' => [
        'section'       => '\App\Commands\SectionActions',
        'lecture'       => '\App\Commands\LectureActions',
        'quizzes'       => '\App\Commands\QuizzesActions'
        //'examination' => 'App\Models\Examination',// can phat trien them
    ],
    // rieng loai lecture cung se duoc phan chia thanh cac loai noi dung trong do : video, document, mix, hay audio
    // de co the tao cac cach hien thi cho phu hop voi loai noi dung do
    // chu y khong duoc thay doi cac gia tri cua cac cai dat nay tru khi ban da lam cac buoc de chuyen doi trong database
    'lecture_types' => [
        'video' => 'App\Models\Video',
        'audio' => 'App\Models\Audio',
        'document' => 'App\Models\Document',
    ],
    'content_edit_status' => [
        'public' => 1,
        'editing' => 0,
    ],
    'content_privacy' => [
        'student' => 'student', // only student can lean
        'free'  => 'free', // all user can learn
        'company' => 'company', // only company user can learn
    ],
    'content_view_status' => [
        'open' => 0,
        'viewed' => 1,
    ],
    'content_view_log' => [
        'step_log' => 5,
        'time_out' => 5,
        'min_time' => 10, // 30s
    ],

    'release' => [
        'min_description' => 500,
        'min_tags' => 10,
        'min_lecture' => 3,
        'min_seo_description' => 0,
        'require_introvideo' => false,
        'require_cover' => true,
        'require_goals' => true,
    ],

    'guess_access_free_course' => env('GUESS_ACCESS_FREE_COURSE', true),

];