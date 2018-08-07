<?php

$api = app('Dingo\Api\Routing\Router');

$api->version('v1', ['middleware' => 'api.auth'], function ($api) {
    $api->get('/category', 'App\Http\Controllers\Api\V1\CategoryController@getCategory');
    $api->get('/courses', 'App\Http\Controllers\Api\V1\CourseController@getCourse');
    $api->get('/course/{id}', 'App\Http\Controllers\Api\V1\CourseController@getCourseDetail');
    $api->get('/course/{course_id}/{content_id}', 'App\Http\Controllers\Api\V1\CourseController@getContentDetail');
    $api->get('/coursesCertificate', 'App\Http\Controllers\Api\V1\CourseController@getCourseCertificate');
    //User exams results
    $api->post('/exams', 'App\Http\Controllers\Api\V1\ExamController@getResult');
});