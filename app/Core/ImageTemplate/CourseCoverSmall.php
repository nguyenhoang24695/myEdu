<?php
/**
 * Created by PhpStorm.
 * User: hocvt
 * Date: 9/22/15
 * Time: 11:00
 */

namespace App\Core\ImageTemplate;

use Intervention\Image\Image;
use Intervention\Image\Filters\FilterInterface;

class CourseCoverSmall implements FilterInterface
{
    public function applyFilter(Image $image)
    {
        return $image->fit(240, 240);
    }
}