<?php
/**
 * Created by PhpStorm.
 * User: hocvt
 * Date: 4/26/16
 * Time: 09:15
 */

namespace App\Models;


use App\Exceptions\ColomboException;
use App\Models\Traits\CustomAsDateTimeFunction;
use Elasticquent\ElasticquentTrait;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class MarketingCourse extends Model{

	use ElasticquentTrait;
	use CustomAsDateTimeFunction;

	protected $table = 'marketing_courses';

	protected $fillable = [
		'title',
		'exact_keyword',
		'description',
		'similar_keyword',
	];

	protected $mappingProperties = [
		'title' => [
			'type' => 'string',
			'analyzer' => 'standard'
		],
		'slug' => [
			'type' => 'string'
		],
		'description' => [
			'type' => 'string',
			'analyzer' => 'stripHtml',
		],
		'course_id' => [
			'type' => 'integer',
		],
		'exact_keyword' => [
			'type' => 'string',
			'analyzer' => 'exactKeywordsAnalyzer',
		],
		'similar_keyword' => [
			'type' => 'string'
		],
		'show_count' => [
			'type' => 'integer',
		],
	];

	function getIndexDocumentData()
	{
		return array(
			'id'      => $this->id,
			'course_id' => $this->course_id,
			'course_audience' => $this->course->cou_audience,
			'course_goals' => $this->course->cou_goals,
			'course_url' => $this->course->get_public_view_link(),
			'title' => $this->title,
			'slug' => $this->slug,
			'description' => $this->description,
			'exact_keyword' => $this->exact_keyword,
			'similar_keyword' => $this->similar_keyword,
			'show_count' => $this->show_count,
			'created_at'  => $this->created_at,
			'updated_at'  => $this->updated_at,

		);
	}

	public function course() {
		return $this->belongsTo(Course::class);
	}

	public function save(Array $option = []){
		if(empty($this->course_id)){
			throw new ColomboException("Thiếu thông tin khóa học");
		}
		if(isset($option['update_course_info']) || empty($this->title)){
			$this->title = $this->course->cou_title;
		}
		if(isset($option['update_course_info']) || empty($this->slug)){
			$this->slug = $this->course->slug;
		}
		if(isset($option['update_course_info']) || empty($this->description)){
			$this->description = strip_tags($this->course->description);
		}
		if(empty($this->course_url)){
			$this->course_url = $this->course->get_public_view_link();
		}
		$return = parent::save($option);
		if($return){
			if($this->wasRecentlyCreated){
				$this->addToIndex();
			}else{
				$this->updateIndex();
			}
		}
		return $return;
	}

	/**
	 * @param Course|Collection $course
	 *
	 * @return static
	 * @throws ColomboException
	 */
	public static function importCourse($course){
		if($course instanceof Course){
			$mcourse = new MarketingCourse();
//			$mcourse->course_id = $course->id;
			$mcourse->course()->associate($course);
			$mcourse->save();
			return $mcourse;
		}elseif($course instanceof Collection){
			$return = new Collection();
			foreach($course as $_course){
				$return->add(self::importCourse($_course));
			}
			return $return;
		}else{
			throw new \InvalidArgumentException();
		}
	}

	public function delete(){
		$this->removeFromIndex();
		return parent::delete();
	}
}