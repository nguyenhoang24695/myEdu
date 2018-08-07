<?php
/**
 * Created by PhpStorm.
 * User: hocvt
 * Date: 10/17/15
 * Time: 10:10
 */

namespace App\Listeners\Frontend;


use App\Events\Frontend\CourseContentChange;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class CourseContentChangeHandler implements ShouldQueue
{
    use InteractsWithQueue;

    public function __construct()
    {
        //
    }

    /**
     * @param CourseContentChange $event
     */
    public function handle(CourseContentChange $event)
    {
        //$event->course->updateIndex();
        $cache_key = 'course_content_' . $event->course->id;
        if(\Cache::has($cache_key)){
            \Cache::forget($cache_key);
        }
    }



}