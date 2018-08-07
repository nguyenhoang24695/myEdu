<?php
/**
 * Created by PhpStorm.
 * User: hocvt
 * Date: 9/29/15
 * Time: 11:56
 */

namespace App\Commands;


class Command extends \Illuminate\Console\Command
{

    private $data = [];

    public function set_data($data){
        $this->data = $data;
    }
    /**
     * @param $key
     * @param string $default
     * @return mixed
     */
    public function get_val($key, $default = ''){
        return array_get($this->data, $key, $default);
    }
    public function has_val($key){
        return array_has($this->data, $key);
    }
}