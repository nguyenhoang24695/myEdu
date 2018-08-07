<?php
/**
 * Created by PhpStorm.
 * User: hocvt
 * Date: 12/2/15
 * Time: 14:44
 */

namespace App\Core\HtmlParser;


interface ParserContract
{
    public function load($string);
    public function stripTags($allowed_tags);
    public function removeAttributes(array $attributes);
    public function addNofollowToLink($external_only = true);
    public function toString();
}