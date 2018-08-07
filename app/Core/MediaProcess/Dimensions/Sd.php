<?php
/**
 * Created by PhpStorm.
 * User: hocvt
 * Date: 3/31/16
 * Time: 10:44
 */

namespace App\Core\MediaProcess\Dimensions;


use FFMpeg\Coordinate\Dimension;

class Sd extends Dimension{
	
	/**
	 * Sd constructor.
	 *
	 * @param int $width
	 * @param int $height
	 */
	public function __construct($width = 640, $height = 360) {
		parent::__construct($width, $height);
	}
}