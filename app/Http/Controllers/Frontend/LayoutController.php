<?php namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;

/**
* Class DashboardController
* @package App\Http\Controllers\Frontend
*/
class LayoutController extends Controller {

	/**
	 * @return mixed
	 */
	public function category()
	{
		return view('frontend.layout.category');
	}

	public function blank()
	{
		return view('frontend.layout.blank');
	}

	public function lecture(){
		return view('frontend.layout.lecture');
	}

	public function profile(){
		return view('frontend.layout.profile');
	}

	public function detail(){
		return view('frontend.layout.detail');
	}

	public function video(){
		return view('frontend.layout.video');
	}

	public function index(){
		return view('frontend.layout.index');
	}

	public function course(){
		return view('frontend.layout.course');
	}

}

?>