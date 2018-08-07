<?php
/**
 * Created by PhpStorm.
 * User: hocvt
 * Date: 9/15/15
 * Time: 16:38
 */

namespace App\Core;


use Illuminate\Support\Facades\Cache;

class TimeLimitAccess
{

    private static $limits = [
        'minute' => 1,
        '5minutes' => 5,
        '10minutes' => 10,
        'hour' => 60,
        '5hours' => 300,
        '12hours' => 720,
        'day' => 1440,
        '2days' => 2880,
        'week' => 10080,
        'month' => 43200,
        'year'  => 525600,
    ];

    /**
     * @param string|bool|true $request
     * @param string $time
     * @return string
     */
    public static function makeRequestToken($request = '', $time = '5minutes'){
        if(isset(self::$limits[$time])){
            $time = self::$limits[$time];
        }else{
            $time = intval($time);
        }
        $token = md5(guid(env('APP_KEY', "edus365")));
        Cache::add($token, $request, $time);
        return $token;
    }

    public static function checkRequestToken($token, $request = ''){
        $cached_value = Cache::get($token, false);
        if(!$cached_value || $cached_value != $request)return false;
        return true;
    }

}