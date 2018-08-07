<?php

/**
 * Frontend Routes
 * Namespaces indicate folder structure
 */
Route::group(['namespace' => 'Frontend'], function () {
    require(__DIR__ . "/Routes/Frontend/Frontend.php");
    require(__DIR__ . "/Routes/Frontend/Access.php");

    require(__DIR__ . "/Routes/Frontend/Course.php");
});

if(env('APP_DEBUG', false)){
    require(__DIR__ . "/Routes/test.php");
}


Route::get('delete_index', function(){
    try{
        $response = \App\Core\MyIndexer::getClient()->deleteIndex('taggable.course');
        dd($response);
    }catch (Exception $ex){
        dd($ex->getMessage());
    }

});

Route::get('email/test', function(){
    $tem_type   =   "REGISTER_TEACHER_ACTIVE";
    $obj_user   =   \App\Models\User::find(1);
    return view('emails.notification.test', compact('tem_type','obj_user'));
});

/**
 * Backend Routes
 * Namespaces indicate folder structure
 */
require(__DIR__ . "/Routes/Backend/Backend.php");




Route::group(['prefix' => 'api'], function () {

    require(__DIR__ . "/Routes/Api/ApiV1.php");
    require(__DIR__ . "/Routes/Api/Upload.php");
    require(__DIR__ . "/Routes/Api/Video.php");
});


//Theo Dingo
require (__DIR__ . '/Routes/Api/V1.php');


/**
 * Blog
 **/

Route::group(['namespace' => 'Frontend'], function () {
    //Blog

    Route::group(['prefix' => 'blog'], function () {

        Route::get('/', [
            'uses' => 'BlogController@index'
        ]);

        Route::post('saveimage', [
            'as' => 'blog.saveimage',
            'uses' => 'BlogController@saveimage'
        ]);

        Route::get('{id}-{title}.html', [
            'as' => 'blog.show',
            'uses' => 'BlogController@show'
        ])->where(['id' => '[0-9]+', 'title' => '([^/]*)']);

        Route::get('categories/{id}-{title}.html', [
            'as' => 'blog.categories',
            'uses' => 'BlogController@categories'
        ])->where(['id' => '[0-9]+', 'title' => '([^/]*)']);

    });

    Route::group(['prefix' => 'blog', 'middleware' => 'auth'], function () {

        Route::get('create', [
            'as' => 'blog.create',
            'uses' => 'BlogController@create'
        ]);

        Route::post('create', [
            'as' => 'blog.store',
            'uses' => 'BlogController@store'
        ]);

        Route::get('listing', [
            'as' => 'blog.listing',
            'uses' => 'BlogController@listing'
        ]);

        Route::get('{id}/edit', [
            'as' => 'blog.edit',
            'uses' => 'BlogController@edit',
        ])->where('id', '[0-9]+');

        Route::post('{id}/edit', [
            'as' => 'blog.update',
            'uses' => 'BlogController@update'
        ])->where('id', '[0-9]+');

        Route::get('{id}/delete', [
            'as' => 'blog.destroy',
            'uses' => 'BlogController@destroy'
        ])->where('id', '[0-9]+');

    });

    /**=========================================**/

    //Profile
    Route::group(['prefix' => 'u'], function () {

        Route::get('{id}-{title}.html', [
            'as' => 'profile.show',
            'uses' => 'ProfileController@index'
        ])->where(['id' => '[0-9]+', 'title' => '([^/]*)']);

        Route::get('{username}/blog/{id}', [
            'as' => 'blog.show.protected',
            'uses' => 'BlogController@showProtected'
        ])->where(['id' => '[0-9]+']);

    });

    Route::group(['prefix' => 'u','middleware' => 'auth'], function () {
        Route::post('update', [
            'as' => 'frontend.profile.update',
            'uses' => 'ProfileController@updateInfoProfile'
        ]);
    });

    //Course
    Route::get('category/{id}-{title}.html', [
        'as' => 'category.show',
        'uses' => 'CategoryController@show'
    ])->where(['id' => '[0-9]+', 'title' => '([^/]*)']);

    //Đánh giá (reviews)
    Route::group(['prefix' => 'reviews', 'middleware' => 'auth'], function () {

        Route::post('create', [
            'as' => 'reviews.store',
            'uses' => 'ReviewsController@store'
        ]);

    });

    // Bình luận khóa học (Discussion)
    Route::group(['prefix' => 'discussion', 'middleware' => 'auth'], function () {
        Route::post('create', [
            'as' => 'frontend.discussion.store',
            'uses' => 'DiscussionController@store'
        ]);
        Route::post('reply', [
            'as' => 'frontend.discussion.reply',
            'uses' => 'DiscussionController@storeReply'
        ]);
        Route::post('vote', [
            'as' => 'frontend.discussion.vote',
            'uses' => 'DiscussionController@storeVoteUp'
        ]);
        Route::post('report', [
            'as' => 'frontend.discussion.report',
            'uses' => 'DiscussionController@storeReport'
        ]);
    });

    //Landingpage
    Route::group(['prefix' => 'Landingpage'], function () {
        Route::get('revenue', [
            'as' => 'frontend.landingpage.revenue',
            'uses' => 'LandingpageController@revenue',
        ]);
    });

    //Tìm kiếm
    Route::group(['prefix' => 'search'], function () {

        Route::get('/api', [
            'as' => 'show.json',
            'uses' => 'SearchController@apiSearch'
        ]);

        Route::get('/kwd', [
            'as' => 'kwd.search',
            'uses' => 'SearchController@index'
        ]);

    });

    //COD Active khóa học
    Route::group(['prefix' => 'cod'], function () {
        Route::get('active', [
            'as' => 'cod.active',
            'uses' => 'CodController@index'
        ]);
        Route::post('active', [
            'as' => 'cod.active.store',
            'uses' => 'CodController@active'
        ]);
        Route::get('landing', [
            'as' => 'cod.landing',
            'uses' => 'CodController@landing'
        ]);
        Route::get('email', [
            'as' => 'cod.email',
            'uses' => 'CodController@email'
        ]);
        Route::post('landing', [
            'as' => 'cod.landing.store',
            'uses' => 'CodController@store'
        ]);
    });

});

Route::get('test_vod_quochoc', function(){
    $disk = \App\Core\MyStorage::getDisk('vod_quochoc');
//    $path = 'video/2016/03_30/3b4002030be8d7f1181a98d60e7ffb85.mp4';
    $path = 'video/2016/03_30/3b4002030be8d7f1181a98d60e7ffb85.mp4';
//    $files = $disk->listContents('video/2016/03_30/3b4002030be8d7f1181a98d60e7ffb85.mp4', true);
//    /** @var \League\Flysystem\Adapter\Local $adapter */
//    $adapter = $disk->getAdapter();
//
//    return response()->download($adapter->applyPathPrefix($path));
    dd($disk->has($path));
});

Route::get('video/test/play', function(){
    return view('frontend.cod.test');
});

Route::get('php-info', function(){
    return phpinfo();
});