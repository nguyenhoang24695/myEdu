<?php
/**
 * Created by PhpStorm.
 * User: hocvt
 * Date: 9/4/15
 * Time: 08:25
 */

namespace App\Repositories\LectureContent;


interface LectureContentContract{

    public function getType();

    public function getTitle();

    public function getDescription();

    public function getMedias();

    public function getVideo();

    public function getDocument();

    public function getAudio();

    public function getAttachment();

    public function addMedias();



}