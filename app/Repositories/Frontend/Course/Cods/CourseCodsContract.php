<?php

namespace App\Repositories\Frontend\Course\Cods;

interface CourseCodsContract
{
    public function create($input);

    public function check_duplicate_order($course_id, $user_id);

    public function check_activated($course_id, $user_id);

    public function activate_course($course_id, $user_id, $code);
}