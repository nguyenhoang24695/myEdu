<?php namespace App\Providers;

use Illuminate\Routing\Router;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider {

	/**
	 * This namespace is applied to the controller routes in your routes file.
	 *
	 * In addition, it is set as the URL generator's root namespace.
	 *
	 * @var string
	 */
	protected $namespace = 'App\Http\Controllers';

	/**
	 * Define your route model bindings, pattern filters, etc.
	 *
	 * @param  \Illuminate\Routing\Router  $router
	 * @return void
	 */
	public function boot(Router $router)
	{
		//

		parent::boot($router);
	}

	/**
	 * Define the routes for the application.
	 *
	 * @param  \Illuminate\Routing\Router  $router
	 * @return void
	 */
	public function map(Router $router)
	{
		$router->group(['namespace' => $this->namespace], function($router)
		{
			require app_path('Http/routes.php');
		});

		javascript()->put([
			'recharge_link' => route('user.financial.recharge'),
			'recharge_by_mobile_card_link' => route('user.financial.recharge_by_card'),
			'recharge_by_bank_card_link' => route('user.financial.recharge_by_bank_card'),
			'recharge_by_bank_exchange_link' => route('user.financial.recharge_by_bank_exchange'),
            'recharge_by_COD_link' => route('user.financial.recharge_by_COD'),
			'recharge_post_link' => route('user.financial.recharge'),
		]);
	}
}