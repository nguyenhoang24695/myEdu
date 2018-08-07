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


class CourseTransformer extends TransformerAbstract
{
    /**
     * Turn this item object into a generic array
     *
     * @return array
     */
    public function transform(Course $course)
    {
        return [
            'id'          => (int) $course->id,
            'title'       => $course->cou_title,
            'sub_title'   => $course->cou_sub_title,
            'summary'     => htmlentities($course->cou_summary),
            'knowledge'   => $course->cou_goals != "" ? htmlentities('<ul class="s_last"><li>' . implode("</li><li>", explode('|', $course->cou_goals)) . '</li></ul>') : "",
            'audience'    => $course->cou_audience != "" ? htmlentities('<ul class="no-margin"><li>' . implode("</li><li>", explode('|', $course->cou_audience)) . '</li></ul>') : "",
            'requirements'=> $course->cou_requirements != "" ? htmlentities('<ul class="no-margin"><li>' . implode("</li><li>", explode('|', $course->cou_requirements)) . '</li></ul>') : "",
            'category'    => $course->cou_cate_id,
            'price'       => $course->cou_price,
            'active'      => $course->cou_active,
            'cover_disk'  => $course->cover_disk,
            'cover_path'  => $course->cover_path,
            'content'     => $course->content,
            'teacher'     => $course->user
        ];
    }
}