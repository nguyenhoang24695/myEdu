<?php
/**
 * Created by PhpStorm.
 * User: hocvt
 * Date: 12/8/15
 * Time: 08:51
 */
Route::group( [ 'namespace' => 'Backend' ], function () {
	Route::group( [ 'prefix' => 'admin', 'middleware' => 'auth' ], function () {
		/**
		 * These routes need the Administrator Role
		 * or the view-backend permission (good if you want to allow more than one group in the backend, then limit the backend features by different roles or permissions)
		 *
		 * If you wanted to do this in the controller it would be:
		 * $this->middleware('access.routeNeedsRoleOrPermission:{role:Administrator,permission:view_backend,redirect:/,with:flash_danger|You do not have access to do that.}');
		 *
		 * You could also do the above in the Route::group below and remove the other parameters, but I think this is easier to read here.
		 * Note: If you have both, the controller will take precedence.
		 */
		// ADMIN DASHBOARD

		Route::group( [
			'middleware' => 'access.routeNeedsRoleOrPermission',
			'role'       => [ 'Administrator' ],
			'permission' => [ 'view_backend' ],
			'redirect'   => '/',
			'with'       => [ 'flash_danger', trans( 'admin.not_permission_access' ) ]
		], function () {
			Route::get( 'dashboard', [ 'as' => 'backend.dashboard', 'uses' => 'DashboardController@index' ] );
//			require( __DIR__ . "/Access.php" );
		} );
		// USER MANAGE

		Route::group( [
			'middleware' => 'access.routeNeedsRoleOrPermission',
			'role'       => [ 'Administrator' ],
			'permission' => [ 'system_admin_user_manage' ],
			'redirect'   => '/admin/dashboard',
			'with'       => [ 'flash_danger', trans( 'admin.not_permission_access' ) ]
		], function () {
//			Route::get( 'dashboard', [ 'as' => 'backend.dashboard', 'uses' => 'DashboardController@index' ] );
			require( __DIR__ . "/Access.php" );
		} );

		// ADMIN COURSE MANAGE
		Route::group( [
			'middleware' => 'access.routeNeedsRoleOrPermission',
			'role'       => [ 'Administrator' ],
			'permission' => [ 'system_admin_course_manage' ],
			'redirect'   => '/admin/dashboard',
			'with'       => [ 'flash_danger', trans( 'admin.not_permission_access' ) ]
		], function () {
			//////////////// CATEGORY:: SHOW AND LISTING
			Route::get( 'category', [ 'as' => 'backend.category_index', 'uses' => 'CategoryController@index' ] );
			Route::post( 'category', [ 'as' => 'backend.category_index', 'uses' => 'CategoryController@index' ] );
			Route::get( 'category/detail/{id}', [
				'as'   => 'backend.category_detail',
				'uses' => 'CategoryController@detail'
			] )
			     ->where( 'id', '[0-9]+' );
			Route::get( 'category/{cat_id}/courses', [
				'as'   => 'backend.category.course_list',
				'uses' => 'CourseController@index'
			] )
			     ->where( 'cat_id', '[0-9]+' );
			Route::get('update_lecture_data_length', ['as' => 'backend.course.update_lecture_data_length',
			'uses' => 'CourseController@update_lecture_data_length']);

			//////////////// CATEGORY:: MANIPULATE
			Route::get( 'category/delete/{id}', [
				'as'   => 'backend.category.delete',
				'uses' => 'CategoryController@delete'
			] )
			     ->where( 'id', '[0-9]+' );
			/** @todo change to post */
			Route::get( 'category/check_moving/{id}/{direction}',
				[ 'as' => 'backend.category.check_moving', 'uses' => 'CategoryController@checkMoving' ] )
			     ->where( [ 'id' => '[0-9]+', 'direction' => '(up)|(left)|(right)|(down)' ] );// check tree action
			Route::get( 'category/moving/{id}/{direction}',
				[ 'as' => 'backend.category.moving', 'uses' => 'CategoryController@moving' ] )
			     ->where( [ 'id' => '[0-9]+', 'direction' => '(up)|(left)|(right)|(down)' ] );// Tree action
			//Route::get('category/create', ['as' => 'backend.category.create', 'uses' => 'CategoryController@create']);// add category
			Route::get( 'category/toggle_status/{id}',
				[ 'as' => 'backend.category.toggle_status', 'uses' => 'CategoryController@toggleStatus' ] )
			     ->where( [ 'id' => '[0-9]+' ] );
			Route::get( 'category/create/{cat_id?}',
				[ 'as' => 'backend.category.create', 'uses' => 'CategoryController@create' ] )
			     ->where( [ 'cat_id' => '[0-9]+' ] );// add category
			Route::post( 'category/create', [
				'as'   => 'backend.category.create_save',
				'uses' => 'CategoryController@create'
			] );// save new category
			Route::get( 'category/edit/{id}', [ 'as' => 'backend.category.edit', 'uses' => 'CategoryController@edit' ] )
			     ->where( [ 'id' => '[0-9]+' ] );// edit category
			Route::post( 'category/edit/{id}', [
				'as'   => 'backend.category.edit_save',
				'uses' => 'CategoryController@edit'
			] )
			     ->where( [ 'id' => '[0-9]+' ] );// edit category

			Route::get( 'category/rebuild_tree',
				[
					'as'   => 'backend.category_index.rebuild_tree',
					'uses' => 'CategoryController@rebuildTree'
				] );// rebuild tree

			/**
			 * Reviews
			 */

			Route::group( [ 'prefix' => 'reviews' ], function () {

				Route::get( '/', [
					'as'   => 'reviews.index',
					'uses' => 'ReviewsController@index'
				] );

				Route::get( 'list', [
					'as'   => 'reviews.list',
					'uses' => 'ReviewsController@index'
				] );

				Route::get( '{reviews}/active', [
					'as'   => 'reviews.active',
					'uses' => 'ReviewsController@active'
				] )->where( 'reviews', '[0-9]+' );

				Route::get( '{reviews}/delete', [
					'as'   => 'reviews.destroy',
					'uses' => 'ReviewsController@destroy'
				] )->where( 'reviews', '[0-9]+' );

			} );

			/**
			 * Course
			 */

			Route::group( [ 'prefix' => 'course' ], function () {

				Route::get( '/', [
					'as'   => 'backend.course.index',
					'uses' => 'CourseController@index'
				] );

				Route::get( '/{module}', [
					'as'   => 'backend.course.module',
					'uses' => 'CourseController@index'
				] );

				Route::get( '{course}/active', [
					'as'   => 'backend.course.active',
					'uses' => 'CourseController@active'
				] )->where( 'course', '[0-9]+' );

				Route::get( '{course}/delete', [
					'as'   => 'backend.course.destroy',
					'uses' => 'CourseController@destroy'
				] )->where( 'course', '[0-9]+' );

                Route::get( '{course}/edit', [
                    'as'   => 'backend.course.edit',
                    'uses' => 'CourseController@edit'
                ] )->where( 'course', '[0-9]+' );
				// Update counter
				Route::get( 'update_counter/{id}',
					[
						'as'   => 'backend.course.update_counter',
						'uses' => 'CourseController@updateCourseInfo'
					] )->where( [ 'id' => '[0-9]+' ] );

			} );

		} );
		// BLOG MANAGE
		Route::group( [
			'middleware' => 'access.routeNeedsRoleOrPermission',
			'role'       => [ 'Administrator' ],
			'permission' => [ 'system_admin_blog_manage' ],
			'redirect'   => '/admin/dashboard',
			'with'       => [ 'flash_danger', trans( 'admin.not_permission_access' ) ]
		], function () {


			/**
			 * Blog Categories Management
			 **/

			Route::group( [ 'prefix' => 'blogcate' ], function () {
				Route::get( '/', [
					'as'          => 'blogcate.index',
					'uses'        => 'BlogCategoriesController@index',
					'permissions' => 'blogcate.view',
				] );

				Route::get( 'list', [
					'as'          => 'blogcate.list',
					'uses'        => 'BlogCategoriesController@index',
					'permissions' => 'blogcate.view',
				] );

				Route::get( 'create', [
					'as'          => 'blogcate.create',
					'uses'        => 'BlogCategoriesController@create',
					'permissions' => 'blogcate.create',
				] );

				Route::post( '/', [
					'as'          => 'blogcate.store',
					'uses'        => 'BlogCategoriesController@store',
					'permissions' => 'blogcate.store',
				] );

				Route::get( '{blogcate}/edit', [
					'as'          => 'blogcate.edit',
					'uses'        => 'BlogCategoriesController@edit',
					'permissions' => 'blogcate.edit',
				] )->where( 'blogcate', '[0-9]+' );

				Route::post( '{blogcate}/edit', [
					'as'          => 'blogcate.update',
					'uses'        => 'BlogCategoriesController@update',
					'permissions' => 'blogcate.update',
				] )->where( 'blogcate', '[0-9]+' );

				Route::get( '{blogcate}/active', [
					'as'          => 'blogcate.active',
					'uses'        => 'BlogCategoriesController@active',
					'permissions' => 'blogcate.active',
				] )->where( 'blogcate', '[0-9]+' );

				Route::get( '{blogcate}/delete', [
					'as'          => 'blogcate.destroy',
					'uses'        => 'BlogCategoriesController@destroy',
					'permissions' => 'blogcate.destroy',
				] )->where( 'blogcate', '[0-9]+' );

			} );


			/**
			 * Blogs Management
			 **/

			Route::group( [ 'prefix' => 'blog' ], function () {

				Route::get( '/', [
					'as'          => 'blog.index',
					'uses'        => 'BlogController@index',
					'permissions' => 'blog.view',
				] );

				Route::get( '/{module}', [
					'as'   => 'blog.module',
					'uses' => 'BlogController@index'
				] );

				Route::get( '{blog}/active', [
					'as'          => 'blog.active',
					'uses'        => 'BlogController@active',
					'permissions' => 'blog.active',
				] )->where( 'blog', '[0-9]+' );

				Route::get( '{blog}/hot', [
					'as'   => 'blog.hot',
					'uses' => 'BlogController@hot'
				] )->where( 'blog', '[0-9]+' );

				Route::get( '{blog}/delete', [
					'as'          => 'backend.blog.destroy',
					'uses'        => 'BlogController@destroy',
					'permissions' => 'blog.destroy',
				] )->where( 'blog', '[0-9]+' );

			} );


		} );
		// LIB MANAGE
		Route::group( [
			'middleware' => 'access.routeNeedsRoleOrPermission',
			'role'       => [ 'Administrator' ],
			'permission' => [ 'system_admin_lib_manage' ],
			'redirect'   => '/admin/dashboard',
			'with'       => [ 'flash_danger', trans( 'admin.not_permission_access' ) ]
		], function () {
			Route::controller( 'course-content', 'CourseContentController' );
		});
		// SYSTEM CONFIG
		Route::group( [
			'middleware' => 'access.routeNeedsRoleOrPermission',
			'role'       => [ 'Administrator' ],
			'permission' => [ 'system_admin_system_manage' ],
			'redirect'   => '/admin/dashboard',
			'with'       => [ 'flash_danger', trans( 'admin.not_permission_access' ) ]
		], function () {
//			Route::controller( 'course-content', 'CourseContentController' );

			/**
			 * TAG
			 */
			Route::match( [ 'post', 'get' ], 'tags', [
				'as'   => 'backend.tags.index',
				'uses' => 'TagController@getIndex'
			] );
			Route::post( 'tags/reslug', [ 'as' => 'backend.tags.reslug', 'uses' => 'TagController@reslug' ] );
			Route::get( 'tags/detail/{id}', [ 'as' => 'backend.tags.detail', 'uses' => 'TagController@tagDetail' ] )
			     ->where( [ 'id' => '[0-9]+' ] );

			/**
			 * INDEX
			 */

			Route::get( 'searchindex', [ 'as' => 'backend.searchindex.index', 'uses' => 'IndexerController@index' ] );
			Route::post( 'searchindex/manipulate', [
				'as'   => 'backend.searchindex.manipulate',
				'uses' => 'IndexerController@manipulate'
			] );
			Route::match(['post', 'get'], 'searchindex/index_manipulate/{type}/{action?}', [
				'as'   => 'backend.searchindex.index_manipulate',
				'uses' => 'IndexerController@index_manipulate'
			] );
		} );
		// MONEY MANAGE
		Route::group( [
			'middleware' => 'access.routeNeedsRoleOrPermission',
			'role'       => [ 'Administrator' ],
			'permission' => [ 'system_admin_money_manage' ],
			'redirect'   => '/admin/dashboard',
			'with'       => [ 'flash_danger', trans( 'admin.not_permission_access' ) ]
		], function () {
			/**
			 * MONEY
			 */
			Route::get( 'revenue-report', [
				'as'   => 'backend.money.revenue_report',
				'uses' => 'MoneyController@revenueReport'
			] );
			Route::get( 'orders', [ 'as' => 'backend.money.orders.list', 'uses' => 'MoneyController@orderList' ] );
			Route::get( 'transactions', [
				'as'   => 'backend.money.orders.transactions_list',
				'uses' => 'MoneyController@innerTransactionList'
			] );
			Route::match( [ 'get', 'post' ], 'orders/{order_id}/{action?}',
				[ 'as' => 'backend.money.orders.detail', 'uses' => 'MoneyController@orderDetail' ] )
			     ->where( [ 'order_id' => '[0-9]+', 'action' => '^((view)|(approve)|(reject)|(revert))$' ] );

			Route::match( [ 'get' ], 'wallets', [
				'as'   => 'backend.wallet.index',
				'uses' => 'WalletsController@index'
			] );
			Route::match( [ 'get' , 'post'], 'wallets/validate/{user_id}', [
				'as'   => 'backend.wallet.validate',
				'uses' => 'WalletsController@validate_wallet'
			] );
		} );
		// AFFILIATE MANAGE
		Route::group( [
			'middleware' => 'access.routeNeedsRoleOrPermission',
			'role'       => [ 'Administrator' ],
			'permission' => [ 'system_admin_affiliate_manage' ],
			'redirect'   => '/admin/dashboard',
			'with'       => [ 'flash_danger', trans( 'admin.not_permission_access' ) ]
		], function () {

			/**
			 * CODE
			 **/

			Route::group( [ 'prefix' => 'code' ], function () {

				Route::get( '/', [
					'as'   => 'backend.code.index',
					'uses' => 'PromoCodeController@index'
				] );

				Route::get( '/{module}', [
					'as'   => 'backend.code.module',
					'uses' => 'PromoCodeController@index'
				] );

				Route::get( '/test/create', [
					'as'   => 'backend.code.test.create',
					'uses' => 'PromoCodeController@create'
				] );

				Route::get( '{id}/active', [
					'as'   => 'backend.code.active',
					'uses' => 'PromoCodeController@active'
				] );

				Route::get( '{id}/delete', [
					'as'   => 'backend.code.destroy',
					'uses' => 'PromoCodeController@destroy'
				] );

				Route::get( '{id}/restore', [
					'as'   => 'backend.code.restore',
					'uses' => 'PromoCodeController@restore'
				] );

			} );

			/**
			 * CODE
			 **/

			Route::group( [ 'prefix' => 'partner' ], function () {

				Route::get( '/', [
					'as'   => 'backend.partner.index',
					'uses' => 'PartnerController@index'
				] );

				Route::get( '/{module}', [
					'as'   => 'backend.partner.module',
					'uses' => 'PartnerController@index'
				] );

				Route::get( '{id}/active', [
					'as'   => 'backend.partner.active',
					'uses' => 'PartnerController@active'
				] );

				Route::get( '{id}/delete', [
					'as'   => 'backend.partner.destroy',
					'uses' => 'PartnerController@destroy'
				] );

			} );

			Route::get('marketing_course',
				['as' => 'backend.marketing_course.index',
				 'uses' => 'MarketingCourseController@index']);
			Route::match(['get', 'post'], 'marketing_course/add',
				['as' => 'backend.marketing_course.add',
				 'uses' => 'MarketingCourseController@add']);
			Route::match(['get', 'post'], 'marketing_course/edit/{ids}',
				['as' => 'backend.marketing_course.edit',
				 'uses' => 'MarketingCourseController@edit'])->where(['ids' => '\d+(\,\d+)*']);

		} );

		Route::group( [ 'prefix' => 'cod' ], function () {
			Route::get( 'listing', [
				'as'   => 'backend.cod.listing',
				'uses' => 'CodController@index'
			]);
			Route::get( 'active/{id}', [
				'as'   => 'backend.cod.active',
				'uses' => 'CodController@active'
			]);
            Route::get( 'activeCOD/{id}', [
                'as'   => 'backend.cod.active_cod',
                'uses' => 'CodController@activeCOD'
            ]);
			Route::get( 'destroy/{id}', [
				'as'   => 'backend.cod.destroy',
				'uses' => 'CodController@destroy'
			]);
		});

	} );
} );