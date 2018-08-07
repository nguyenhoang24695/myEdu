<?php
/**
 * Created by PhpStorm.
 * User: hocvt
 * Date: 9/9/15
 * Time: 17:29
 */

namespace App\Repositories\Frontend\Course;


interface CourseContract
{
    public function incrementView($id);
}