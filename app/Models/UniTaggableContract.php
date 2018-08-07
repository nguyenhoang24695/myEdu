<?php
/**
 * Created by PhpStorm.
 * User: hocvt
 * Date: 12/8/15
 * Time: 11:51
 */

namespace App\Models;


interface UniTaggableContract
{
    public function getTitle();
    public function getSubtitle();
    public function getThumbnail($template);
    public function getLink();
}