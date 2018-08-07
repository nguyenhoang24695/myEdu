<?php namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel {

	/**
	 * The Artisan commands provided by your application.
	 *
	 * @var array
	 */
	protected $commands = [
		//'App\Console\Commands\Inspire',
		'App\Console\Commands\Access\UpdateRolePermission',
		'App\Console\Commands\IndexTags',
		'App\Console\Commands\IndexTaggable',
		'App\Console\Commands\ConvertVideo',
		'App\Console\Commands\DownloadVideoYoutube'
	];

	/**
	 * Define the application's command schedule.
	 *
	 * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
	 * @return void
	 */
	protected function schedule(Schedule $schedule)
	{
		//$schedule->command('inspire')->hourly();
		$schedule->command('youtube:download')->withoutOverlapping()->everyMinute();
	}
}