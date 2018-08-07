<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Category;
use App\Models\Course;
use App\Models\Lecture;
use App\Repositories\Frontend\Category\CategoryContract;
use App\Repositories\Frontend\Course\CourseContract;
use App\Transformer\CourseCertificateTransformer;
use App\Transformer\CourseListTransformer;
use App\Transformer\LectureTransformer;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Transformer\CourseTransformer;


class CourseController extends BaseController
{
    protected $course;
    private $categories;

    public function __construct(Course $course, CategoryContract $categories)
    {
        $this->course     = $course;
        $this->categories = $categories;
    }

    public function getCourse(Request $request)
    {
        $price    =  $request->get('price');
        $trend    =  $request->get('trend');
        $limit    =  $request->get('limit');
        $course   =  $request->get('course');
        $keyword  =  $request->get('keyword');

        $filter   = [];
        $take     = 20;

        if($request->get('category') != ''){
            $cate_id     = $request->get('category');
            $id_cat_in   = [$cate_id];
            $category    = Category::where('cat_active',1)->where('id',$cate_id)->first();
            if($category && $category->parent_id == 0){
                $children   = $category->children()->get();
                foreach ($children as $key => $value) {
                    $id_cat_in[] = $value->id;
                }
            }

            $category_id = explode(',',$request->get('category'));
            if(!empty($category_id) && count($category_id) > 1){
                $filter = array_add($filter,'cou_cate_id',['operator' => 'IN', 'value' => $category_id]);
            } else {
                $filter = array_add($filter,'cou_cate_id',['operator' => 'IN', 'value' => $id_cat_in]);
            }
        }

        if($course != ''){
            $course_id = explode(',',$course);
            if(!empty($course_id)){
                $filter = array_add($filter,'id',['operator' => 'IN', 'value' => $course_id]);
            }
        }

        if($keyword != ''){
            $filter = array_add($filter,'cou_title',['operator' => 'LIKE', 'value' => '%'.$keyword.'%']);
        }

        $filter = array_add($filter,'cou_active',1);

        $course  = $this->course;
        if(!empty($filter)){
            $course     = addFilter($course,$filter);
        }

        if($price == 'cdesc'){
            $course = $course->orderby('cou_price','DESC');
        } elseif ($price == "casc") {
            $course = $course->orderby('cou_price','ASC');
        } elseif ($price == "cfree") {
            $course = $course->where('cou_price',0)->orderby('id','DESC');
        } else {
            $course = $course->orderby('created_at','DESC');
        }

        if($trend == 'cviews'){
            $course = $course->orderby('cou_views','DESC');
        }

        if($limit != '' && (int) $limit > 0){
            $data_course = $course->limit($limit)->get();
            return $this->response->collection($data_course, new CourseListTransformer());
        }

        $course = $course->orderby('created_at','DESC');

        $data_course = $course->paginate($take);
        return $this->response->paginator($data_course, new CourseListTransformer());
    }

    public function getCourseDetail($id)
    {
        $course      = $this->course->find($id);
        if($course && $course->cou_active == 1){
            $course_contents = $course->getCachedContents();
            $data_content    = [];
            foreach($course_contents as $content){
                $data_content[] = [
                    'id'        => $content->id,
                    'title'     => $content->get_title(),
                    'sub_title' => $content->get_sub_title(),
                    'access'    => $content->accessPrivacy(),
                    'type'      => $content->get_type()
                ];
            }

            $course->content = $data_content;

            return $this->response->item($course, new CourseTransformer)->setStatusCode(200);
        } else {
            abort(404);
        }
    }

    public function getContentDetail($course_id,$content_id)
    {
        $course     = $this->course->find($course_id);
        $id         = $course->id;

        $course_contents = $course->getCachedContents();

        list($prev_content, $viewing_content, $next_content) = $this->getViewingContent($id, $content_id, $course_contents);

        $section = false;
        $lecture = false;
        $not_suppoted_type = false;
        $questionJson      = [];

        if($course_contents && $viewing_content){
            if($viewing_content->get_type() == config('course.content_types.section')){
                $section = $viewing_content->getContent();
            }elseif($viewing_content->get_type() == config('course.content_types.lecture')){
                $lecture = $viewing_content->getContent();
            }elseif($viewing_content->get_type() == config('course.content_types.quizzes')){
                $quizzes = $viewing_content->getContent();
            }
        }

        if($lecture->getPrimaryData()){
            if( $lecture->getPrimaryData()->get_media_type() == 'video' ){
                $video_html = view('frontend.student.course.content.api.video', ['video' => $lecture->getPrimaryData(), 'cover_image' => $course->get_cached_image('cc_video_cover'), 'has_secondary' => $lecture->hasSecondaryData()])->render();
            } else {
                $video_html = view('frontend.student.course.content.nothing')->render();
            }
        } elseif ($viewing_content->external_sources) {
            $video_html = view('frontend.student.course.content.external_source', ['external_sources' => $viewing_content->external_sources])->render();
        } else {
            $video_html = view('frontend.student.course.content.nothing')->render();
        }

        $lecture->id     = $content_id;
        $lecture->player = $video_html;
        if ($lecture->hasSecondaryData() && $lecture->getSecondaryData()->get_media_type() == 'document') {
            $lecture->download_link = $lecture->getSecondaryData()->get_download_link();
        }

        return $this->response->array($lecture, new LectureTransformer)->setStatusCode(200);
    }

    public function getCourseCertificate(Request $request)
    {
        $course   =  $request->get('course');

        $filter   = [];
        $take     = 20;

        $course_id = explode(',',$course);
        $filter = array_add($filter,'id',['operator' => 'IN', 'value' => $course_id]);
        $filter = array_add($filter,'cou_active',1);

        $course = $this->course;
        $course = addFilter($course,$filter);
        $course = $course->orderby('created_at','DESC');

        $data_course = $course->paginate($take);
        return $this->response->paginator($data_course, new CourseCertificateTransformer());
    }

    /**
     * Trả về bài học được chọn, tuy nhiên nếu khóa học ko cho phép học không theo thứ tự thì phải trả về bài giảng cuối
     * cùng đang hoc dở
     * @param $id
     * @param $content_id
     * @return mixed|static
     * @todo hoàn thiện hàm khi chốt phương án cho việc học theo thứ tự của khóa học
     */
    private function getViewingContent($id, $content_id, $course_contents){
        \Log::alert($content_id);
        $prev_content = null;
        $next_content = null;
        $view_content = null;

        $count_cc = count($course_contents);

        for($i = 0; $i < $count_cc; $i++){
            if($course_contents[$i]->id == $content_id || $content_id == 0){
                for(; $i < $count_cc; $i++){
                    if($course_contents[$i]->get_type() != config('course.content_types.section')){
                        $view_content = $course_contents[$i];
                        break;
                    }
                }
                for($i++ ; $i < $count_cc; $i++){
                    if($course_contents[$i]->get_type() != config('course.content_types.section')){
                        $next_content = $course_contents[$i];
                        break;
                    }
                }
                break;
            }
            if($course_contents[$i]->get_type() != config('course.content_types.section')){
                $prev_content = $course_contents[$i];
            }
        }

        if($view_content == null && $prev_content != null){
            $view_content = $prev_content;
            $prev_content = null;
        }

        return [$prev_content, $view_content, $next_content];
    }
}
