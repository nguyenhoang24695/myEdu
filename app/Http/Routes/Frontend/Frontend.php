<?php

/**
 * Frontend Controllers
 */
Route::get('/', ['as' => 'home', 'uses' => 'FrontendController@index']);

/**
 * Viewer
 */
//Route::get

/**
 * These frontend controllers require the user to be logged in
 */
Route::group(['middleware' => 'auth'], function () {
    //Route::get('dashboard', ['as' => 'frontend.dashboard', 'uses' => 'DashboardController@index']);

    Route::group(['prefix' => 'dashboard'], function () {

        Route::get('/', [
            'as' => 'profile.home',
            'uses' => 'DashboardController@index'
        ]);

        Route::get('{module}', [
            'uses' => 'DashboardController@module'
        ]);

        Route::post('{module}', [
            'uses' => 'DashboardController@update'
        ]);

        Route::get('financial/review', [
            'as' => 'user.financial.review',
            'uses' => 'DashboardController@financial',
        ]);

        Route::match(['post', 'get'], 'financial/recharge', [
            'as' => 'user.financial.recharge',
            'uses' => 'DashboardController@recharge',
        ]);

        Route::match(['get'], 'financial/recharge-by-card', [
            'as' => 'user.financial.recharge_by_card',
            'uses' => 'DashboardController@rechargeByMobileCard',
        ]);

        Route::match(['get'], 'financial/recharge-by-bank-card', [
            'as' => 'user.financial.recharge_by_bank_card',
            'uses' => 'DashboardController@rechargeByBankCard',
        ]);

        Route::match(['get'], 'financial/recharge-by-bank-exchange', [
            'as' => 'user.financial.recharge_by_bank_exchange',
            'uses' => 'DashboardController@rechargeByBankExchange',
        ]);

        Route::match(['get'], 'financial/recharge-by-COD', [
            'as' => 'user.financial.recharge_by_COD',
            'uses' => 'DashboardController@rechargeByCOD',
        ]);

        Route::post('financial/transaction-report', [
            'as' => 'user.financial.transaction_report',
            'uses' => 'DashboardController@transaction_report',
        ]);

        Route::get('financial/guide/{order_id}', [
            'as' => 'user.financial.payment_guide',
            'uses' => 'DashboardController@payment_guide'])->where(['order_id' => '[0-9]+']);

        Route::get('payment/by-bank', [
            'as' => 'user.financial.payment.bybank',
            'uses' => 'DashboardController@payment_bybank']);

    });

    Route::group(['prefix' => 'notification'], function () {

        //test
        Route::get('send', [
            'as' => 'notification.send',
            'uses' => 'NotificationController@test'
        ]);

        //Cập nhật
        Route::post('action', [
            'as' => 'notification.action',
            'uses' => 'NotificationController@action'
        ]);

        //Cài đặt
        Route::post('setting', [
            'as' => 'notification.setting',
            'uses' => 'DashboardController@notificationSetting'
        ]);

    });

    Route::group(['prefix' => 'code'], function () {
        Route::get('{code}', [
            'as' => 'frontend.code.detail',
            'uses' => 'PromoCodeController@detail'
        ]);
        Route::post('update', [
            'as' => 'frontend.code.update',
            'uses' => 'PromoCodeController@update'
        ]);
        Route::post('updateDiscount', [
            'as' => 'frontend.code.update_discount',
            'uses' => 'PromoCodeController@updateDiscount'
        ]);
    });

    //Tạo Link chia sẻ
    Route::group(['prefix' => 'link'], function () {
        Route::post('create', [
            'as' => 'frontend.link.create',
            'uses' => 'TrackingLinkController@create'
        ]);
        Route::get('listing', [
            'as' => 'frontend.link.listing',
            'uses' => 'TrackingLinkController@listing'
        ]);
        Route::get('{id}/delete', [
            'as' => 'frontend.link.delete',
            'uses' => 'TrackingLinkController@delete'
        ]);
    });

    Route::resource('profile', 'ProfileController', ['only' => ['edit', 'update']]);

});

Route::group(['prefix' => 'dashboard'], function () {
    Route::get('become/teacher', [
        'as' => 'become.teacher',
        'uses' => 'DashboardController@becomeTeacher'
    ]);

    Route::post('become/teacher', [
        'as' => 'become.success',
        'uses' => 'DashboardController@postBecomeTeacher'
    ]);
});

Route::group(['prefix' => 'static'], function () {

    Route::get('{module}', [
        'as' => 'payment.guide.module',
        'uses' => 'DashboardController@staticModule'
    ]);

});

Route::group(['prefix' => 'partner'], function () {
    Route::get('info', [
        'as' => 'partner.info',
        'uses' => 'PartnerController@info'
    ]);

    Route::get('register', [
        'as' => 'partner.register',
        'uses' => 'PartnerController@register'
    ]);

    Route::POST('register', [
        'as' => 'partner.register',
        'uses' => 'PartnerController@store'
    ]);
});

Route::get('sitemap.xml', function()
{
    // create sitemap
    $sitemap = App::make("sitemap");

    $sitemap->setCache('laravel.sitemap', 60);

    if (!$sitemap->isCached())
    {

        $courses = \App\Models\Course::where('cou_active',1)->where('public_status',1)->orderBy('created_at', 'DESC')->get();
        foreach ($courses as $course)
        {
            $sitemap->add($course->get_public_view_link(), $course->updated_at, '1.0', 'daily');
        }

        $categories = \App\Models\Category::where('cat_active',1)->get();
        foreach ($categories as $category)
        {
            $sitemap->add(route('category.show',['id'=>$category->id,'title'=>str_slug($category->cat_title,'-')]), $category->updated_at, '1.0', 'daily');
        }

        $sitemap->add(URL::to('/'), null, '1.0', 'daily');
        $sitemap->add(URL::to('/static/payment-guide'), null, '1.0', 'monthly');
        $sitemap->add(URL::to('/static/chinh-sach-hoan-hoc-phi'), null, '1.0', 'monthly');
        $sitemap->add(URL::to('/static/quy-che-hoat-dong'), null, '1.0', 'monthly');
        $sitemap->add(URL::to('/static/chinh-sach-bao-mat-thong-tin'), null, '1.0', 'monthly');
        $sitemap->add(URL::to('/static/dieu-khoan-su-dung'), null, '1.0', 'monthly');

    }

    // show sitemap
    return $sitemap->render('xml');
});


/**
 * Single Sign On - SSO
 */
Route::get('sso/set-cookie', 'SSOController@setCookie')->name('sso.set-cookie');
Route::get('sso/unset-cookie', 'SSOController@clearCookie')->name('sso.unset-cookie');
