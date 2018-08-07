<?php namespace App\Providers;

use App\Events\Frontend\OrderNotification;
use App\Events\Frontend\SendEmailNotificationEvent;
use App\Events\Frontend\SendNotificationEvent;
use App\Listeners\Frontend\OrderNotificationHandler;
use App\Listeners\Frontend\SendEmailNotificationHandler;
use App\Listeners\Frontend\SendNotificationHandler;
use Illuminate\Contracts\Events\Dispatcher as DispatcherContract;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider {

	/**
	 * The event handler mappings for the application.
	 *
	 * @var array
	 */
	protected $listen = [
		/**
		 * Frontend Events
		 */
		'App\Events\Frontend\Auth\UserLoggedIn' => [
			'App\Listeners\Frontend\Auth\UserLoggedInHandler',
		],
		'App\Events\Frontend\Auth\UserLoggedOut' => [
			'App\Listeners\Frontend\Auth\UserLoggedOutHandler',
		],
		'App\Events\Frontend\CourseContentChange' => [
			'App\Listeners\Frontend\CourseContentChangeHandler',
		],
		'App\Events\Frontend\StudentRegisterCourse' => [
			'App\Listeners\Frontend\StudentRegisterCourseHandler',
		],
		'App\Events\Frontend\SendNotificationEvent' => [
			'App\Listeners\Frontend\SendNotificationHandler',
		],
		'App\Events\Frontend\SendEmailNotificationEvent' => [
			'App\Listeners\Frontend\SendEmailNotificationHandler',
		],
		'App\Events\Frontend\OrderNotification' => [
			'App\Listeners\Frontend\OrderNotificationHandler',
		],
		'App\Events\Frontend\NotifyWhenDiscussions' => [
			'App\Listeners\Frontend\NotifyWhenDiscussionsHandler',
		]
	];

	/**
	 * Register any other events for your application.
	 *
	 * @param  \Illuminate\Contracts\Events\Dispatcher  $events
	 * @return void
	 */
	public function boot(DispatcherContract $events)
	{
		parent::boot($events);

		//
	}
}