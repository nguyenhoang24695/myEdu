<?php
/**
 * Created by PhpStorm.
 * User: hocvt
 * Date: 10/21/15
 * Time: 11:14
 */

namespace App\Listeners\Frontend;


use App\Events\Frontend\StudentRegisterCourse;
use App\Models\CourseStudent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class StudentRegisterCourseHandler implements ShouldQueue
{
    use InteractsWithQueue;

    public function __construct()
    {
        //
    }

    /**
     * @param StudentRegisterCourse $event
     */
    public function handle(StudentRegisterCourse $event)
    {
        \Log::info('User ' . $event->student . ' đăng ký Course ' . $event->course);
    }
}