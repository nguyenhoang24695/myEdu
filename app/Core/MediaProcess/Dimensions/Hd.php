<?php
/**
 * Created by PhpStorm.
 * User: hocvt
 * Date: 3/31/16
 * Time: 10:44
 */

namespace App\Core\MediaProcess\Dimensions;


use FFMpeg\Coordinate\Dimension;

class Hd extends Dimension{
	/**
	 * Hd constructor.
	 *
	 * @param int $width
	 * @param int $height
	 */
	public function __construct($width = 1280, $height = 720) {
		parent::__construct($width, $height);
	}
}