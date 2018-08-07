<?php
/**
 * Created by PhpStorm.
 * User: hocvt
 * Date: 4/28/16
 * Time: 10:02
 */

namespace App\Models\Traits;


use Carbon\Carbon;

trait CustomAsDateTimeFunction {
	protected function asDateTime($value){
		if(is_array($value)){
			$value = new Carbon($value['date'], $value['timezone']);
		}
		return parent::asDateTime($value);
	}
}