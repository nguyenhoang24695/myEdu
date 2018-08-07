<?php
/**
 * Created by PhpStorm.
 * User: hocvt
 * Date: 11/20/15
 * Time: 14:57
 */
return [
    'binaries' => [
        'ffmpeg'  => env('FFMPEG_BIN','/usr/local/bin/ffmpeg'),
        'ffprobe' => env('FFPROBE_BIN','/usr/local/bin/ffprobe'),
        'thread' => 8,
        'c_timeout' => 10800, // 10800 = 3h
        'p_timeout' => 30,
    ]

];