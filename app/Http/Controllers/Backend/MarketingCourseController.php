<?php
/**
 * Created by PhpStorm.
 * User: hocvt
 * Date: 4/26/16
 * Time: 11:47
 */

namespace App\Http\Controllers\Backend;


use App\Exceptions\GeneralException;
use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\MarketingCourse;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class MarketingCourseController extends Controller{

	protected $roles = [

	];
	protected $permissions = [

	];

	public function index() {
		$mcourses = MarketingCourse::with(['course', 'course.user'])->orderBy('created_at', 'desc')->simplePaginate(20);
		return view('backend.marketing_course.index', [
			'mcourses' => $mcourses,
		]);
	}

	public function add(Request $request) {
		$keyword = $request->get('keyword', null);
		if($keyword === null){
			$courses = Course::where('cou_active', 1)->orderBy('created_at', 'desc')->simplePaginate(20);
			$c_paginate = $courses->render();
		}else{
			$keyword = [
				'bool' => [
					'should' => [
						['match' => [
							'cou_title' => [
								"query" => $keyword,
								'boost' => config('elasticquent.boost.course.title', 2),
							]
						]],
						['match' => [
							'cou_sub_title' => [
								"query" => $keyword,
								'boost' => config('elasticquent.boost.course.description', 1),
							]
						]],
						['match' => [
							'slug' => [
								"query" => $keyword,
								'boost' => config('elasticquent.boost.course.slug', 2),
							]
						]],
					],
					'must' => [
						['match' => [
							'cou_active' => 1,
						]]
					]
				]
			];
			$courses = Course::searchByQuery($keyword)->load('user');
			$c_paginate = ' ';
		}
		if($request->isMethod('post')){
			// lưu khóa học
			$ids = $request->get('ids', []);
			if(empty($ids)){
				throw new GeneralException("Vui lòng chọn khóa học để thêm vào danh sách marketing.");
			}
			// add course
			$courses = Course::whereIn('id', $ids)->get();
			$mcourse = MarketingCourse::importCourse($courses);
			dd($mcourse);

		}
		return view('backend.marketing_course.add', [
			'courses' => $courses,
			'c_paginate' => $c_paginate
		]);
	}

	public function edit($ids, Request $request) {
		$ids = explode(',', $ids);
		/** @var Collection $m_courses */
		$m_courses = MarketingCourse::whereIn('id', $ids)->get();

		if($request->isMethod('post')){
			$posted = $request->all();
			$saved = 0;
			$m_courses->each(function($m_course) use($posted, &$saved){
				/** @var MarketingCourse $m_course */
				$save_check = $m_course->update([
					'title' => $posted['title'][$m_course->id],
					'exact_keyword' => $posted['exact_keyword'][$m_course->id],
					'description' => $posted['description'][$m_course->id],
					'similar_keyword' => $posted['similar_keyword'][$m_course->id],
				]);

				if($save_check){
					$saved++;
				}
			});
			if($saved != $m_courses->count()){
				throw new GeneralException("Lưu " . $saved . "/" . $m_courses->count());
			}else{
				return redirect()->route('backend.marketing_course.index')->withFlashSuccess("Đã lưu toàn bộ thay đổi");
			}
		}

		$data = [
			'm_courses' => $m_courses,
		];

		return view('backend.marketing_course.edit', $data);
	}

}