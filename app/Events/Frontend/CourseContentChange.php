<?php
/**
 * Created by PhpStorm.
 * User: hocvt
 * Date: 10/17/15
 * Time: 10:09
 */

namespace App\Events\Frontend;


use App\Events\Event;
use App\Models\Course;
use Illuminate\Queue\SerializesModels;

class CourseContentChange extends Event
{
    use SerializesModels;

    /** @var Course */
    public $course;

    /**
     * CourseContentChange constructor.
     * @param $course
     */
    public function __construct($course)
    {
        if($course instanceof Course){
            $this->course = $course;
        }else{
            $this->course = Course::find(intval($course));
            if(!$this->course){
                throw new \InvalidArgumentException("Đầu vào phải là 1 id của khóa học hoặc một object khóa học");
            }
        }

    }


}