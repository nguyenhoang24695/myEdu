<?php
/**
 * Created by PhpStorm.
 * User: hocvt
 * Date: 9/11/15
 * Time: 10:55
 */

Route::match(['post', 'get'], 'upload_file', ['as' => 'api.upload_file', 'uses' => 'Api\V1\UploadFileController@upload']);
Route::match(['post', 'get'], 'upload_video', ['as' => 'api.upload_video', 'uses' => 'Api\V1\UploadFileController@uploadVideo']);
Route::match(['post', 'get'], 'upload_document', ['as' => 'api.upload_document', 'uses' => 'Api\V1\UploadFileController@uploadDocument']);
Route::match(['post', 'get'], 'upload_audio', ['as' => 'api.upload_audio', 'uses' => 'Api\V1\UploadFileController@uploadAudio']);