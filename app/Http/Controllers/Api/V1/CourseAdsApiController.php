<?php
/**
 * Created by PhpStorm.
 * User: hocvt
 * Date: 5/5/16
 * Time: 07:45
 */

namespace App\Http\Controllers\Api\V1;


use App\Exceptions\ColomboException;
use App\Models\Course;
use App\Models\MarketingCourse;
use Illuminate\Cookie\CookieJar;
use Illuminate\Http\Request;

class CourseAdsApiController extends ApiController{
	public function suggestCourse( Request $request , CookieJar $cookieJar) {

//		$cookieJar->queued(cookie('a', 'b'));

		$keyword = $request->get('keyword');

		$search_query = $this->buildSearchQuery($keyword);

		$m_courses = MarketingCourse::searchByQuery($search_query, null, null, 2)->load('course');

//		dd($m_courses);

		$data = [
			'm_courses' =>  $m_courses
		];
		return view('api.ads.template1', $data);
	}

	private function buildSearchQuery($keywords){
		$query = [
			'bool' => [
				"should" => []
			]
		];
		if(is_string($keywords)){
			$keywords = [
				[
				'type' => 'keyword',
				'content' => $keywords,
				]
			];
		}else{
			throw new ColomboException("Can not build query from this keyword");
		}

		foreach($keywords as $keyword){
			if($keyword['type'] == 'keyword'){
				$query['bool']['should'][] = [
					"match" => [
						"title" => [
							"query" => $keyword['content'],
							"boost" => config('elasticquent.boost.course.title', 10),
						]
					]
				];
				$query['bool']['should'][] = [
					"match" => [
						"slug" => [
							"query" => $keyword['content'],
							"boost" => config('elasticquent.boost.course.slug', 10),
						]
					]
				];
				$query['bool']['should'][] = [
					"match" => [
						"description" => [
							"query" => $keyword['content'],
							"boost" => 1,
						]
					]
				];
			}
		}
		return $query;
	}

}