<?php
/**
 * Created by PhpStorm.
 * User: hocvt
 * Date: 4/22/16
 * Time: 08:01
 */

namespace App\Models;


use Conner\Tagging\Model\Tag;
use Elasticquent\ElasticquentTrait;

class EsTag extends Tag
{
	use ElasticquentTrait;

	public $fillable = [
		'id','name', 'slug', 'suggest', 'count'
	];

	protected $mappingProperties = [
		'name'      => [
			'type' => 'string',
			'analyzer' => 'standard'
		],
//		'slug'      => [
//			'type' => 'string',
//			'analyzer' => 'standard'
//		],
		'suggested' => [
			'type' => 'integer',
			'analyzer' => 'standard'
		],
		'count'     => [
			'type' => 'long',
			'analyzer' => 'standard'
		],
	];

	function getIndexDocumentData()
	{
		return array(
			'id'      => $this->id,
			'name'   => $this->name,
			'slug'  => $this->slug,
			'suggested'  => $this->suggested,
			'count'  => $this->count,
		);
	}
}