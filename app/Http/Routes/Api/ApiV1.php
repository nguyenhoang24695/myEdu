<?php
/**
 * Created by PhpStorm.
 * User: hocvt
 * Date: 9/15/15
 * Time: 17:16
 */
Route::get('document/download/{id}/{token}', ['as' => 'api.document.download', 'uses' => 'Api\V1\Resource\DocumentController@download'])
    ->where(['id' => '[0-9]+', 'token' => '[a-zA-Z0-9]+']);
Route::get('audio/download/{id}/{token}', ['as' => 'api.audio.download', 'uses' => 'Api\V1\Resource\AudioController@download'])
    ->where(['id' => '[0-9]+', 'token' => '[a-zA-Z0-9]+']);
Route::get('audio/stream/{id}/{token}', ['as' => 'api.audio.stream', 'uses' => 'Api\V1\Resource\AudioController@stream'])
    ->where(['id' => '[0-9]+', 'token' => '[a-zA-Z0-9]+']);


Route::get('video_subtitle/{id}/{token}', ['as' => 'api.video.subtitle', 'uses' => 'Api\V1\VideoController@get_sub'])
    ->where(['id' => '[0-9]+', 'token' => '[a-zA-Z0-9]+']);

///////////CATEGORY//////////////
Route::post('category/get_child/{root}', ['as' => 'api.category.get_child', 'uses' => 'Api\V1\CategoryController@getChild'])
        ->where(['root' => '[0-9]+']);

//////////COURSE/////////////////
Route::post('course/change_avatar', ['as' => 'api.course.change_avatar', 'uses' => 'Api\V1\Resource\CourseController@updateAvatar']);
Route::post('course/change_intro_video', ['as' => 'api.course.change_intro_video', 'uses' => 'Api\V1\Resource\CourseController@updateIntroVideo']);
Route::post('course/build_content/{id}', ['as' => 'api.course.building', 'uses' => 'Api\V1\Resource\CourseController@buildContent'])
    ->where(['id' => '[0-9]+']);
Route::post('course/students', ['as' => 'api.course.students', 'uses' => 'Api\V1\Resource\CourseController@students']);
// Logging
Route::post('course/view_status', ['as' => 'api.course.view_status', 'uses' => 'Api\V1\Resource\CourseController@view_status']);
Route::post('course/view_status_log', ['as' => 'api.course.view_status_log', 'uses' => 'Api\V1\Resource\CourseController@view_status_log']);
Route::post('course/view_status_log_quiz', ['as' => 'api.course.view_status_log_quiz', 'uses' => 'Api\V1\Resource\CourseController@view_status_log_quizzes']);

// YOUTUBE CONTROLLER
Route::post('youtube/import_video', ['middleware' => 'auth',
    'as' => 'api.youtube.import_video',
    'uses' => 'Api\V1\Resource\YoutubeController@importVideo']);
Route::post('youtube/import_playlist', ['middleware' => 'auth',
    'as' => 'api.youtube.import_playlist',
    'uses' => 'Api\V1\Resource\YoutubeController@importPlaylist']);

//|||||||||||||||||||||||||| PUBLIC ACCESS ||||||||||||||||||||||||||||||||

///////////TAGS/////////
Route::post('tags/search_json', ['as' => 'api.tags.search', 'uses' => 'Api\V1\Resource\TagController@search']);

///////////BAOKIM PRO/////////
Route::match(['post'], 'bpn', ['as' => 'api.bpn.receiver', 'uses' => 'Api\V1\BaoKimController@bpn']);

//////////// VIDEO STREAM //////////

//Route::get('video_secure_stream/{window}/{view_key}', ['as' => 'defa.video_stream', function(\Illuminate\Http\Request $request, $window, $view_key){
//    $defa = new \App\Core\Defa();
//    $defa->streamVideo($window, $view_key);
//
//}]);
//
//Route::post('start_video_view_log', ['as' => 'defa.enable', function(\Illuminate\Http\Request $request){
//    $defa = new \App\Core\Defa();
//    $defa->enable($request);
//}]);


//////////// Flex Ad //////////////////
Route::group(['middleware' => 'cors'], function(){
    Route::match(['get', 'post'],
        'ad/v1',
        ['as' => 'api.ad.suggest',
         'uses' => 'Api\V1\CourseAdsApiController@suggestCourse']
    );
});

