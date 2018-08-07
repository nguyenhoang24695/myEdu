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


class CourseCertificateTransformer extends TransformerAbstract
{
    /**
     * Turn this item object into a generic array
     *
     * @return array
     */
    public function transform(Course $course)
    {
        return [
            'title'             => $course->cou_title,
        ];
    }
}