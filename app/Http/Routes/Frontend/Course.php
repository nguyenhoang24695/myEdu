<?php
/**
 * Routes with CourseController
 */
Route::group(['middleware' => 'access.routeNeedsRoleOrPermission',
                'role' => [config('access.role_list.teacher'), config('access.role_list.administrator')],
                'redirect'   => '/','with'       => ['flash_danger', trans('admin.not_permission_access')],
                'prefix' => 'teacher'], function(){

    /**
     * COURSES(TEACHER)
     */

    Route::get('my_courses', ['as' => 'teacher.my_courses', 'uses' => 'Teacher\CourseController@index']);// list my course
    Route::get('add_new_course', ['as' => 'teacher.add_new_course', 'uses' => 'Teacher\CourseController@add']);// add a course
    Route::post('save_new_course', ['as' => 'teacher.save_new_course', 'uses' => 'Teacher\CourseController@save']);// add a course
    Route::match(['post', 'get'],'build_course/{id}/{action?}', ['as' => 'teacher.build_course', 'uses' => 'Teacher\CourseController@building'])
        ->where(['id' => '[0-9]+', 'action' => implode('|',config('course.build_actions'))]);// add a course
    Route::match(['post', 'get'], 'build_course/get_course_content_view',
        ['as' => 'teacher.get_course_content_view', 'uses' => 'Teacher\CourseController@get_course_content_view']);
    Route::match(['post', 'get'], 'build_course/get_lecture_media_form',
        ['as' => 'teacher.get_lecture_media_form', 'uses' => 'Teacher\CourseController@get_lecture_media_form']);
    Route::match(['post', 'get'], 'build_course/get_lecture_media_view',
        ['as' => 'teacher.get_lecture_media_view', 'uses' => 'Teacher\CourseController@get_lecture_media_view']);

    //Xuất bản khóa học
    Route::post('build_course/{id}/public', [
        'uses' => 'Teacher\CourseController@PublicCourse',
        'as'   => 'build_course.public'
    ]);

    /**
     * QUIZZES
     */

    Route::group(['prefix' => 'quizzes'], function(){
        Route::post('addContent',
            ['as' => 'teacher.quizzes.addContent', 'uses' => 'Teacher\QuizzesController@create']);
        Route::post('editContent',
            ['as' => 'teacher.quizzes.editContent', 'uses' => 'Teacher\QuizzesController@edit']);
        Route::post('storeQuestion',
            ['as' => 'teacher.quizzes.storeQuestion', 'uses' => 'Teacher\QuizzesController@store']);
        Route::post('updateQuestion',
            ['as' => 'teacher.quizzes.updateQuestion', 'uses' => 'Teacher\QuizzesController@update']);
        Route::post('deleteQuestion',
            ['as' => 'teacher.quizzes.deleteQuestion', 'uses' => 'Teacher\QuizzesController@destroy']);
        Route::post('updateOrder',
            ['as' => 'teacher.quizzes.updateOrder', 'uses' => 'Teacher\QuizzesController@reorder']);
    });



    /**
     * LIBRARY
     */
    Route::get('my_library/{media_type?}', ['as' => 'teacher.my_library', 'uses' => 'Teacher\LibraryController@index'])
    ->where(['media_type' => '[a-zA-Z0-9]*']);// my library
    Route::get('my_library/video/{id}', ['as' => 'teacher.my_library.video', 'uses' => 'Teacher\LibraryController@viewVideo'])
        ->where(['id' => '[0-9]+']);// view video detail
    Route::match(['post', 'put', 'get'],
        'my_library/add_video',
        ['as' => 'teacher.my_library.add_video', 'uses' => 'Teacher\LibraryController@addVideo']);// thêm video
    Route::post(
        'my_library/add_video_intro',
        ['as' => 'teacher.my_library.add_video_intro', 'uses' => 'Teacher\LibraryController@addVideoIntro']);// thêm video intro
    Route::match(['post', 'put', 'get'],
        'my_library/edit_video/{id}',
        ['as' => 'teacher.my_library.edit_video', 'uses' => 'Teacher\LibraryController@editVideo'])
        ->where(['id' => '[0-9]+']);// video editing
    Route::match(['post', 'put', 'get'],
        'my_library/add_document',
        ['as' => 'teacher.my_library.add_document', 'uses' => 'Teacher\LibraryController@addDocument']);// thêm document
    Route::match(['post', 'put', 'get'],
        'my_library/edit_document/{id}',
        ['as' => 'teacher.my_library.edit_document', 'uses' => 'Teacher\LibraryController@editDocument'])
        ->where(['id' => '[0-9]+']);// document editing
    Route::get('my_library/document/{id}', ['as' => 'teacher.my_library.document', 'uses' => 'Teacher\LibraryController@viewDocument'])
        ->where(['id' => '[0-9]+']);// view document
    Route::match(['post', 'put', 'get'],
        'my_library/add_audio',
        ['as' => 'teacher.my_library.add_audio', 'uses' => 'Teacher\LibraryController@addAudio']);// add audio
    Route::match(['post', 'put', 'get'],
        'my_library/add_audio',
        ['as' => 'teacher.my_library.add_audio', 'uses' => 'Teacher\LibraryController@addAudio']);//  audio editing
    Route::get('my_library/audio/{id}', ['as' => 'teacher.my_library.audio', 'uses' => 'Teacher\LibraryController@viewAudio'])
        ->where(['id' => '[0-9]+']);// view audio
    Route::get('my_library/edit_audio/{id}', ['as' => 'teacher.my_library.edit_audio', 'uses' => 'Teacher\LibraryController@viewAudio'])
        ->where(['id' => '[0-9]+']);// view audio
    Route::match(['post', 'put', 'get'],
        'my_library/add_text',
        ['as' => 'teacher.my_library.add_text', 'uses' => 'Teacher\LibraryController@add_text']);// add text
    Route::post('my_library/delete/{type}/{id}', ['as' => 'teacher.my_library.delete_media', 'uses' => 'Teacher\LibraryController@removeMedia'])
        ->where(['id' => '[0-9]+', 'type' => 'video|audio|document|text']);

});

Route::group(['prefix' => 'course'], function(){
    /**
     * COURSE(PUBLIC AND STUDENT)
     */
    Route::get('preview/{slug}', ['as' => 'frontend.course.public_view', 'uses' => 'PublicViews\CourseController@detail'])->where(['slug' => '[a-zA-Z0-9\-]+']);

    Route::group(['middleware' => 'auth'], function(){
        Route::get('detail/{slug}', ['as' => 'frontend.course.registered_view', 'uses' => 'Student\CourseController@detail'])->where(['slug' => '[a-zA-Z0-9\-]+']);

        Route::match(['get','post'], 'mua_khoa_hoc/{course_id}',
            ['as' => 'frontend.course.pre_register_course',
                'uses' => 'Student\CourseController@pre_register'])
            ->where(['course_id' => '[0-9]*']);

        Route::post('course_register', ['as' => 'frontend.course.register', 'uses' => 'Student\CourseController@register']);

        Route::get('studying', ['as' => 'frontend.student.my_courses', 'uses' => 'Student\CourseController@studying']);
        ////DEFAULT STUDYING////
        Route::get('default_studying/{slug}/{content_id?}', ['as' => 'frontend.course.default_studying', 'uses' => 'Student\CourseController@study'])
            ->where(['slug' => '[a-zA-Z0-9\-]+', 'content_id' => '[0-9]+']);
    });

    ///// PUBLIC STUDYING ////
    Route::get('public_studying/{slug}/{content_id?}', ['as' => 'frontend.course.public_studying', 'uses' => 'Student\CourseController@public_study'])
        ->where(['slug' => '[a-zA-Z0-9\-]+', 'content_id' => '[0-9]+']);

    Route::group(['prefix' => 'note', 'middleware' => 'auth'], function(){
        Route::get('/{id}/{content_id}', ['as' => 'frontend.course.note', 'uses' => 'NoteController@getList'])
            ->where(['id' => '[0-9]+', 'content_id' => '[0-9]+']);// list
        Route::post('/{id}/{content_id}', ['as' => 'frontend.course.note', 'uses' => 'NoteController@getList'])
            ->where(['id' => '[0-9]+', 'content_id' => '[0-9]+']);// add, edit, remove
    });

    Route::get('lecture_info/{course_content}', ['as' => 'frontend.course.lecture_info', 'uses' => 'Student\CourseController@lecture_info'])
        ->where(['course_content_id' => '[0-9]+']);

});