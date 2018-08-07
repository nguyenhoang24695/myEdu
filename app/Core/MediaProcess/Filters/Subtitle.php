<?php
/**
 * Created by PhpStorm.
 * User: hocvt
 * Date: 4/4/16
 * Time: 11:19
 */

namespace App\Core\MediaProcess\Filters;


use FFMpeg\Exception\InvalidArgumentException;
use FFMpeg\Filters\Video\VideoFilterInterface;
use FFMpeg\Format\VideoInterface;
use FFMpeg\Media\Video;

class Subtitle implements  VideoFilterInterface{

	private $subtitle;
	private $priority;
	private $style = [
		'Name' => [
			'value' => 'Arial',
			'type' => 'string',
		],
		'Fontname' => [
			'value' => 'Arial',
			'type' => 'string',
		],
		'Fontsize' => [
			'value' => null,
			'type' => 'int',
		],
		//
		'PrimaryColour' => [
			'value' => null,
			'type' => 'string',
		],
		'SecondaryColour' => [
			'value' => null,
			'type' => 'string',
		],
		'OutlineColour' => [
			'value' => null,
			'type' => 'string',
		],
		'BackColour' => [
			'value' => null,
			'type' => 'string',
		],
		'Bold' => [
			'value' => null,
			'type' => 'boolean',
		],
		'Italic' => [
			'value' => null,
			'type' => 'boolean',
		],
		'Underline' => [
			'value' => null,
			'type' => 'boolean',
		],
		'StrikeOut' => [
			'value' => null,
			'type' => 'boolean',
		],
		'ScaleX' => [
			'value' => null,
			'type' => 'int',
		],
		'ScaleY' => [
			'value' => null,
			'type' => 'int',
		],
		'Spacing' => [
			'value' => null,
			'type' => 'float',
		],
		'Angle' => [
			'value' => null,
			'type' => 'float',
		],
		'BorderStyle' => [
			'value' => null,
			'type' => 'boolean',
		],
		'Outline' => [
			'value' => null,
			'type' => 'int',
		],
		'Shadow' => [
			'value' => null,
			'type' => 'int',
		],
		'Alignment' => [
			'value' => null,
			'type' => 'int',
		],
		'MarginL' => [
			'value' => null,
			'type' => 'int',
		],
		'MarginR' => [
			'value' => null,
			'type' => 'int',
		],
		'MarginV' => [
			'value' => null,
			'type' => 'int',
		],
		'Encoding' => [
			'value' => 'UTF-8',
			'type' => 'int',
		]
	];
	/**
	 * Subtitle constructor.
	 *
	 * @param $subtitle
	 * @param $priority
	 */
	public function __construct($subtitle, $priority = 0) {
		if (!file_exists($subtitle)) {
			throw new InvalidArgumentException(sprintf('File %s does not exist', $subtitle));
		}
		$this->subtitle = $subtitle;
		$this->priority = $priority;
	}

	/**
	 * @return mixed
	 */
	public function getSubtitle() {
		return $this->subtitle;
	}

	/**
	 * @param mixed $subtitle
	 */
	public function setSubtitle( $subtitle ) {
		$this->subtitle = $subtitle;
	}

	/**
	 * @param null $attribute
	 *
	 * @return array
	 */
	public function getStyle($attribute = null) {
		if($attribute === null){
			return $this->style;
		}
		return array_get($this->style, $attribute . '.value', null);
	}

	/**
	 * @param array $style
	 *
	 * @param $value
	 *
	 * @return bool
	 */
	public function setStyle( $style , $value) {
		if(isset($this->style[$style])){
			switch($this->style[$style]['type']){
				case 'int':
					$value = intval($value);
					break;
				case 'boolean':
					$value = $value ? 1 : 0;
					break;
				case 'string':
					$value = str_replace(',','',$value);
					break;
				default :
					return false;
			}

			$this->style[$style]['value'] = $value;
			return true;
		}
		return false;
	}

	public function fontSize($size = null){
		if($size == null){
			return $this->getStyle('Fontsize');
		}else{
			return $this->setStyle('Fontsize', $size);
		}
	}

	public function buildStyleOptions() {
		$font_options = [];
		foreach($this->style as $k => $style){
			if($style['value'] !== null){
				$font_options[] = $k . '=' . $style['value'];
			}
		}
		return implode(",", $font_options);
	}
	/**
	 * Applies the filter on the the Video media given an format.
	 *
	 * @param Video $video
	 * @param VideoInterface $format
	 *
	 * @return array An array of arguments
	 */
	public function apply( Video $video, VideoInterface $format ) {
		$command_value = "subtitles=" . $this->subtitle;
		$style = $this->buildStyleOptions();
		if($style != ''){
			$command_value .= ":force_style='" . $style . "'";
		}
		$commands = array('-vf', $command_value);

		return $commands;
	}

	/**
	 * Returns the priority of the filter.
	 *
	 * @return integer
	 */
	public function getPriority() {
		// TODO: Implement getPriority() method.
		return $this->priority;
	}
}