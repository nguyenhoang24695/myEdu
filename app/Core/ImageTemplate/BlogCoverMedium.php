<?php

namespace App\Core\ImageTemplate;

use Intervention\Image\Image;
use Intervention\Image\Filters\FilterInterface;

class BlogCoverMedium implements FilterInterface
{
    public function applyFilter(Image $image)
    {
        return $image->fit(450, 150);
    }
}

?>