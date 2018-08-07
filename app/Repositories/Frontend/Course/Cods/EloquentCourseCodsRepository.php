<?php
/**
 * Created by PhpStorm.
 * User: theiceman
 * Date: 15/03/2017
 * Time: 4:16 CH
 */

namespace App\Repositories\Frontend\Course\Cods;

use app\Core\BaseRepository;
use App\Models\CourseCod;
use App\Models\User;

class EloquentCourseCodsRepository extends BaseRepository implements CourseCodsContract
{
    public function __construct(CourseCod $coursecod)
    {
        $this->model = $coursecod;
    }

    public function create($input)
    {
        $course_cod_order = new $this->model;
        $course_cod_order->course_id = $input['course_id'];
        $course_cod_order->user_id = $input['user_id'];
        $course_cod_order->contact_name = $input['contact_name'];
        $course_cod_order->contact_email = $input['contact_email'];
        $course_cod_order->contact_phone = $input['contact_phone'];
        $course_cod_order->contact_address = $input['contact_address'];
        $course_cod_order->active = 0;

        return $course_cod_order->save();
    }

    public function check_duplicate_order($course_id, $user_id)
    {
        $check = $this->model->where([
            'course_id' => $course_id,
            'user_id' => $user_id
        ])->first();
        if($check) return true;
        else return false;
    }

    public function check_activated($course_id, $user_id)
    {
        $check = $this->model->where([
            'course_id' => $course_id,
            'user_id' => $user_id,
            'active' => 1
        ])->first();
        if($check) return true;
        else return false;
    }

    public function activate_course($course_id, $user_id, $code)
    {
        $check = $this->model->where([
            'course_id' => $course_id,
            'user_id' => $user_id,
            'code' => $code
        ])->first();
        if($check)
        {
            $check->active = 1;
            User::registerCourseCod($user_id, $course_id, $check->id);
            $check->save();
            return true;
        }
        else return false;
    }
}