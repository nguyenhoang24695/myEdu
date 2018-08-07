<?php
/**
 * Created by PhpStorm.
 * User: hocvt
 * Date: 9/14/15
 * Time: 18:30
 */

Route::get('stream_video/{id}/{version?}', ['as' => 'api.video.stream', 'uses' => 'Api\V1\VideoController@stream'])
    ->where(['id' => '[0-9]+', 'version' => '(origin)|(hd)|(sd)']);

Route::post('search_my_video', ['as' => 'api.video.search_my_library', 'uses' => 'Api\V1\VideoController@searchMyLib']);



/** Api\Resource\Document routes  */
Route::post('search_my_document', ['as' => 'api.document.search_my_library', 'uses' => 'Api\V1\Resource\DocumentController@searchMyLib']);

/** Api\Resource\Audio routes  */
Route::post('search_my_audio', ['as' => 'api.audio.search_my_library', 'uses' => 'Api\V1\Resource\DocumentController@searchMyLib']);