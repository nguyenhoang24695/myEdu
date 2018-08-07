<?php
/**
 * Created by PhpStorm.
 * User: MyPC
 * Date: 15/02/2017
 * Time: 9:38 SA
 */

namespace App\Transformer;


use App\Models\Lecture;
use League\Fractal\TransformerAbstract;

class LectureTransformer extends TransformerAbstract
{
    /**
     * Turn this item object into a generic array
     *
     * @return array
     */
    public function transform(Lecture $lecture)
    {
        return [
            'id'          => (int) $lecture->id,
            'title'       => $lecture->lec_title,
            'sub_title'   => $lecture->lec_sub_title,
            'player'      => htmlentities($lecture->player)
        ];
    }
}