<?php
/**
 * Created by PhpStorm.
 * User: hocvt
 * Date: 10/21/15
 * Time: 11:12
 */

namespace App\Events\Frontend;


use App\Events\Event;
use Illuminate\Queue\SerializesModels;

class StudentRegisterCourse extends Event
{
    use SerializesModels;

    public $student = 0;
    public $course = 0;

    /**
     * StudentRegisterCourse constructor.
     * @param int $student
     * @param int $course
     */
    public function __construct($student, $course)
    {
        $this->student = $student;
        $this->course = $course;
    }

}