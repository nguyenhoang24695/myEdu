<?php
/**
 * Created by PhpStorm.
 * User: MyPC
 * Date: 08/01/2017
 * Time: 5:59 CH
 */

namespace App\Transformer;

use App\Models\Course;
use League\Fractal\TransformerAbstract;


class CourseListTransformer extends TransformerAbstract
{
    /**
     * Turn this item object into a generic array
     *
     * @return array
     */
    public function transform(Course $course)
    {
        return [
            'id'                => (int) $course->id,
            'title'             => $course->cou_title,
            //'sub_title'         => $course->cou_sub_title,
            //'category'          => $course->cou_cate_id,
            'price'             => $course->cou_price,
            //'active'            => $course->cou_active,
            //'cover_disk'        => $course->cover_disk,
            'cover_path'        => $course->cover_path,
            'content_count'     => $course->course_contents()->where('course_id', $course->id)->where('content_type', config('course.content_types.lecture'))->count('id'),
            'teacher'           => $course->user
        ];
    }
}