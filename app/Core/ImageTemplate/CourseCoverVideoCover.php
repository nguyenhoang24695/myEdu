<?php
/**
 * Created by PhpStorm.
 * User: hocvt
 * Date: 10/5/15
 * Time: 11:19
 */

namespace App\Core\ImageTemplate;


use Intervention\Image\Filters\FilterInterface;

class CourseCoverVideoCover implements  FilterInterface
{

    /**
     * Applies filter to given image
     *
     * @param  \Intervention\Image\Image $image
     * @return \Intervention\Image\Image
     */
    public function applyFilter(\Intervention\Image\Image $image)
    {
        return $image->fit(800, 450);
    }
}