<?php
/**
 * Created by PhpStorm.
 * User: hocvt
 * Date: 9/9/15
 * Time: 17:52
 */

namespace App\Http\Controllers\Frontend\Teacher;

use App\Http\Requests\Frontend\Teacher\NewCourseRequest;
use App\Models\Category;
use App\Models\Course;
use App\Models\CourseContent;
use App\Models\Lecture;
use App\Models\Section;
use App\Models\Video;
use App\Repositories\Frontend\Course\CourseContract;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;

class CourseController extends TeacherController
{
    private $courseContract;
    /**
     * CourseController constructor.
     */
    public function __construct(CourseContract $courseContract)
    {
        $this->courseContract = $courseContract;
    }

    public function index(Request $request){
        $data = [];
        $my_courses = Course::whereCouUserId(auth()->user()->id);

        \SEOMeta::setTitle(trans('meta.course.my_courses'));

        // filter
        $free       = $request->get('free');
        $editing    = $request->get('editing');
        $public     = $request->get('public');

        if($free == 'on'){
            $my_courses->where('cou_price', '=', 0);
        }

        if($free == 'off'){
            $my_courses->where('cou_price', '>', 0);
        }

        if($editing == 'editing'){
            $my_courses->where('edit_status', 0);
        }

        if($public == 'on'){
            $my_courses->where('public_status', 1);
        }

        $my_courses = $my_courses->orderBy('created_at', 'desc')->simplePaginate();
        $data['my_courses'] = $my_courses;
        $data['teacher_name'] = auth()->user()->name;
        return view('frontend.teacher.course.index', $data);
    }

    /**
     * Thêm course với những thông tin cơ bản
     * @return \Illuminate\View\View
     *
     */
    public function add(){
        \SEOMeta::setTitle(trans('meta.course.create'));

        javascript()->put([
            'unibee_tags_link' => route('api.tags.search'),
        ]);

        $data = [];
        $data['languages'] = [trans('common.language_select')] + config('course.languages');
        $this->appendCategoryList($data);
        return view('frontend.teacher.course.add', $data);
    }

    /**
     * Lưu các thông tin cơ bản của khóa học sau khi nhâp một số thông tin cơ bản
     * @param NewCourseRequest $newCourseRequest
     * @return mixed
     */
    public function save(NewCourseRequest $newCourseRequest){

        $saved = Course::create(
        	['cou_user_id' => auth()->user()->id]
	        + $newCourseRequest->only(['cou_title', 'cou_summary', 'cou_cate_id', 'course_tags', 'language']));

        $saved->course_tags = $newCourseRequest->get('course_tags');
        $saved->save();

        if($saved->id > 0){
            return redirect()->route('teacher.my_courses')->withFlashSuccess(trans('common.saved'));
        }else{
            return redirect()->route('teacher.my_courses')->withFlashError(trans('common.unsaved'));
        }

    }

    /**
     *
     * Hiển thị trang xây dựng khóa học
     */
    public function building($id, $action = false){
        // check action support
        $all_action = config('course.build_actions');
        $action = array_keys($all_action, $action);
        $callBack = $action ? $action[0] : config('course.default_build_action');
        try{
            $course = Course::find($id);

            if(!$course){
                abort(404);
            }

            $this->canDo($action, $course);

            $data['course'] = $course;
            $data['course_name'] = $course->cou_title;

            return $this->callAction($callBack, ['id' => $id, 'data' => $data, 'action' => $action]);
        }catch (\BadMethodCallException $ex){
            abort(404, $ex->getMessage());
        }
    }

    public function editObject($id, $data){

        \SEO::setTitle(trans('meta.course.edit_object'));

        /** @var Course $course */
        $course = $data['course'];
        $request = Request::capture();

        if($request->isMethod('post')){
            // get request
            $posted_audience = $request->input('cou_audience');
            $new_audience = "";
            if(is_array($posted_audience)){
                $new_audience = [];
                foreach($posted_audience as $k => &$v){
                    if(trim($v) != ""){
                        $new_audience[] = str_replace('|', ' ', $v);
                    }
                }
                $new_audience = implode('|', $new_audience);
            }

            $posted_goal = $request->input('cou_goal');
            $new_goal = "";
            if(is_array($posted_goal)){
                $new_goal = [];
                foreach($posted_goal as $k => &$v){
                    if(trim($v) != ""){
                        $new_goal[] = str_replace('|', ' ', $v);
                    }
                }
                $new_goal = implode('|', $new_goal);
            }

            $posted_requirement = $request->input('cou_requirement');
            $new_requirement = "";
            if(is_array($posted_requirement)){
                $new_requirement = [];
                foreach($posted_requirement as $k => &$v){
                    if(trim($v) != ""){
                        $new_requirement[] = str_replace('|', ' ', $v);
                    }
                }
                $new_requirement = implode('|', $new_requirement);
            }

            $cou_skill_level = $request->input('cou_skill_level', config('course.learning_capacity.all'));
            $cou_skill_level =
                in_array($cou_skill_level, config('course.learning_capacity')) ?
                    $cou_skill_level : config('course.learning_capacity.all');

            // save
            $check_save = $course->update(['cou_audience' => $new_audience,
                'cou_skill_level' => $cou_skill_level,
                'cou_requirements' => $new_requirement,
                'cou_goals' => $new_goal]);
            if($check_save){
                Session::flash('flash_success', trans('common.saved'));
            }else{
                Session::flash('flash_success', trans('common.unsaved'));
            }

        }

        if($course->cou_skill_level == 0){
            $data['course']->cou_skill_level = config('course.learning_capacity.all');
        }

        $data['audience'] = $course->cou_audience != '' ? explode('|', $course->cou_audience) : [];
        $data['goals'] = $course->cou_goals != '' ? explode('|', $course->cou_goals) : [];
        $data['requirements'] = $course->cou_requirements != '' ? explode('|', $course->cou_requirements) : [];

        return view('frontend.teacher.course.edit_object', $data);
    }

    public function editContent($id, $data){

        \SEO::setTitle(trans('meta.course.edit_content'));
        /** @var Course $course */
        $course = $data['course'];

        $data['course_contents'] = $course->course_contents()->orderBy('content_order')->get();

        javascript()->put([
            'build_course_content_link' => route('api.course.building',['id' => $id]),
            'get_course_content_view' => route('teacher.get_course_content_view'),
            'search_my_video_link' => route('api.video.search_my_library'),
            'search_my_document_link' => route('api.document.search_my_library'),
            'search_my_audio_link' => route('api.audio.search_my_library'),
            'link_lecture_media_form' => route('teacher.get_lecture_media_form'),
            'link_lecture_media_view' => route('teacher.get_lecture_media_view'),
            'upload_video_tmp_link' => route('api.upload_video'),
            'upload_video_max_size' => config('flysystem.max_size.video'),
            'upload_video_exts'  => config('flysystem.exts.video'),
            'upload_audio_tmp_link' => route('api.upload_audio'),
            'upload_audio_max_size' => config('flysystem.max_size.audio'),
            'upload_audio_exts'  => config('flysystem.exts.audio'),
            'upload_document_tmp_link' => route('api.upload_document'),
            'upload_document_max_size' => config('flysystem.max_size.document'),
            'upload_document_exts'  => config('flysystem.exts.document'),
            'course_id' => $course->id,
        ]);
        if(auth()->user()->hasPermission(config('access.perm_list.can_import_video_playlist'))){
            javascript()->put([
                'youtube_import_link' => route('api.youtube.import_playlist')
            ]);
        }

        return view('frontend.teacher.course.edit_content', $data);
    }

    /**
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function get_course_content_view(Request $request){
        $id = $request->get('id', 0);
        /** @var CourseContent $course_content */
        $course_content = CourseContent::find($id);
        if(!$course_content){
            abort(404);
        }
        $content = $course_content->getContent();
        $view_path = 'frontend/teacher/course/building/';
        if($request->has('custom_view')){
            $view_path = $request->get('custom_view');
        }elseif($request->has('view')){
            $view_path .= $request->get('view');
        }else{
            abort('404', 'Template not found');
        }
        return view($view_path, ['content' => $content, 'course_content' => $course_content]);
    }

    public function get_lecture_media_form(Request $request){
        $media_type = $request->get('media_type');
        if(in_array($media_type, ['video', 'audio', 'document'])){
            return view('frontend.teacher.course.building.a_lecture_media_new', ['media_type' => $media_type]);
        }
    }

    public function get_lecture_media_view(Request $request){
        $media_type = $request->get('media_type');
        $media_id = $request->get('media_id');
        $media_class = config('course.lecture_types.' . $media_type);
        $media_object = $media_class::find($media_id);
        if(in_array($media_type, ['video', 'audio', 'document'])){
            return view('frontend.teacher.course.building.a_lecture_media_view',
                ['media_type' => $media_type, 'media_object' => $media_object]);
        }
    }

    public function editSummary($id, $data){
        \SEO::setTitle(trans('meta.course.edit_summary'));
        $request = Request::capture();
        /** @var Course $course */
        $course = $data['course'];

        javascript()->put([
            'unibee_tags_link' => route('api.tags.search'),
        ]);

        if($request->isMethod('post')){
            // validate
            $this->validate($request,[
                'language' => 'required',
                'cou_cate_id' 	=> 'cat_exist|min:1',
                'cou_title' => 'required',
                'cou_summary' => 'required'
            ]);
            // assign guarded attribute
            $course->cou_summary = $request->get('cou_summary', '');
            $course->sub_summary = $request->get('sub_summary', '');

            // save
            $check_save = $course->update($request->only(['language',
                'cou_cate_id',
                'cou_title',
//                'cou_summary',
                'course_tags',
//                'sub_summary',
            ]));

            if($check_save){
                $data['course'] = $course->load('tagged');// reload tagged tags
                Session::flash('flash_success', trans('common.saved'));
            }else{
                Session::flash('flash_warning', trans('common.unsaved'));
            }
        }


        $data['languages'] = [trans('common.language_select')] + config('course.languages');
        $this->appendCategoryList($data);
        return view('frontend.teacher.course.edit_summary', $data);
    }

//    public function editAbout($id, $data){
//
//        $request = Request::capture();
//        /** @var Course $course */
//        $course = $data['course'];
//
//        if($request->isMethod('post')){
//            // validate
//            $this->validate($request,[
//                'introduction' => 'required',
//            ]);
//
//            // save
//            $check_save = $course->update($request->only(['introduction']));
//            if($check_save){
//                Session::flash('flash_success', trans('common.saved'));
//            }else{
//                Session::flash('flash_success', trans('common.unsaved'));
//            }
//
//        }
//        return view('frontend.teacher.course.edit_about', $data);
//    }

    public function editAvatar($id, $data){
        \SEO::setTitle(trans('meta.course.edit_cover'));
        javascript()->put([
            'upload_avatar_link' => route('api.course.change_avatar'),
            'upload_image_max_size' => config('flysystem.max_size.image'),
            'upload_image_exts' => config('flysystem.exts.image'),
        ]);

        return view('frontend.teacher.course.edit_avatar', $data);
    }

    public function editIntroVideo($id, $data){
        \SEO::setTitle(trans('meta.course.edit_promote_video'));
        javascript()->put([
            'upload_video_link' => route('teacher.my_library.add_video_intro'),
            'upload_video_max_size' => config('flysystem.max_size.video'),
            'upload_video_exts' => config('flysystem.exts.video'),
        ]);

        if($data['course']->intro_video_path != "")
        {
            $video = Video::where('video_file_path', $data['course']->intro_video_path)->first();
            if(isset($video))
            {
                $data['video'] = $video;
                $data['streams'] = $video->get_stream_link();
            }
        }
        return view('frontend.teacher.course.edit_intro_video', $data);
    }

    public function editPrivacy($id, $data){
        \SEO::setTitle(trans('meta.course.edit_privacy'));
        return view('frontend.teacher.course.edit_privacy', $data);
    }

    public function editPrice($id, $data){
        \SEO::setTitle(trans('meta.course.edit_price'));
        $request = \Request::capture();
        /** @var Course $course */
        $course = $data['course'];
        $new_course_price = $request->get('course_price', 0);
        if($request->isMethod('post')){
            if($new_course_price%1000 == 0){
                // save price
                $this->validate($request, [
                    'course_price' => 'integer',
                ]);

                $course->cou_price = $new_course_price;
                if($course->save()){
                    \Session::flash('flash_success', trans('common.saved'));
                }else{
                    \Session::flash('flash_warning', trans('common.unsaved'));
                }
            }else{
                \Session::flash('flash_warning', 'Giá khóa học phải là bội số của 1000');
            }

        }
        return view('frontend.teacher.course.edit_price', $data);
    }

    private function appendCategoryList(&$data){
        $category_list = Category::where('cat_active','>',0)->orderBy('lft', 'asc')->get(['id', 'cat_title', 'depth']);
        $category_options = [trans('common.select_one')];
        foreach($category_list as $k => $v){
            if($v->depth == 0){
                $last_group = $v->cat_title;
                $category_options[$last_group] = [];
            }else{
                $indent = "";
                $indent_char = "--";
                if($v->depth > 1){
                    $indent = str_repeat($indent_char, $v->depth - 1);
                }
                $category_options[$last_group][$v->id] = $indent . $v->cat_title;
            }

            //$category_options[$v->id] = $v->cat_title;
        }
        $data['category_list'] = $category_options;
    }

    /**
     * @param $action
     * @param Course $course
     * @return bool
     */
    private function canDo($action, $course){
        if(auth()->user()->roles->first()->id != 1)
        {
            if($course->cou_user_id != auth()->user()->id){
                abort(401, 'Unauthorized!');
            }
        }
        return true;
    }

    /**
     * @param Course $course
     * @return bool
     * @internal param $action
     * @internal param Course $id
     */
    public function checkPublicCourse(Course $course){
        /**
         *Mô tả >= 500
        Tags >= 6
        Ảnh đại diện hoặc video giới thiệu
        Mục tiêu phải điền
        Lecture >=3
         */

        $issues = [];

        $content = $course->getCachedContents();

        $return = true;

        /** Goals */
        if($course->cou_goals == "" && config('course.release.require_goals', true)){

            $_link = route('teacher.build_course', ['id' => $course->id, 'action' => config('course.build_actions.editObject')]);

            $issues[] = "Bạn phải nhập mục tiêu cho khóa học, hãy cập nhật <a href='". $_link ."'>tại đây</a>";

            $return = false;
        }

        /** Avatar */
        if($course->cover_path == "" && config('course.release.require_cover', true)){

            $_link = route('teacher.build_course', ['id' => $course->id, 'action' => config('course.build_actions.editAvatar')]);

            $issues[] = "Bạn phải cập nhật ảnh đại diện cho khóa học <a href='". $_link ."'>tại đây</a>";

            $return = false;
        }

        /** Description */
        $description_length = strlen(strip_tags($course->cou_summary));
        if($description_length < config('course.release.min_description', 500)){
            $_link = route('teacher.build_course', ['id' => $course->id, 'action' => config('course.build_actions.editSummary')]);

            $issues[] = "Mô tả quá ngắn (tối thiêu " . config('course.release.min_description', 500)
                                    . " ký tự), hãy cập nhật mô tả cho khóa học <a href='". $_link ."'>tại đây</a>";

            $return = false;
        }

        /** SEO Description */
        $seo_description_length = strlen(strip_tags($course->sub_summary));
        if($seo_description_length < config('course.release.min_seo_description', 1500)){
            $_link = route('teacher.build_course', ['id' => $course->id, 'action' => config('course.build_actions.editSummary')]);

            $issues[] = "Mô tả SEO quá ngắn (tối thiêu " . config('course.release.min_seo_description', 500)
                                    . " ký tự), hãy cập nhật mô tả SEO cho khóa học <a href='". $_link ."'>tại đây</a>";

            $return = false;
        }

        /** Tags */
        if($course->tagged->count() < config('course.release.min_tags', 6)){
            $_link = route('teacher.build_course', ['id' => $course->id, 'action' => config('course.build_actions.editSummary')]);

            $issues[] = "Bạn phải nhập tối thiểu " . config('course.release.min_tags', 6)
                . " thẻ cho khóa học, cập nhật <a href='". $_link ."'>tại đây</a>";

            $return = false;
        }

        /** Lectures */
        if($course->content_lecture_count < config('course.release.min_lecture', 3)){
            $_link = route('teacher.build_course', ['id' => $course->id, 'action' => config('course.build_actions.editContent')]);

            $issues[] = "Khóa học phải có tối thiểu " . config('course.release.min_lecture', 3)
                . " bài học, cập nhật <a href='". $_link ."'>tại đây</a>";

            $return = false;
        }


        if(!$return){
            \Session::flash('flash_danger', "<ul><li>" . implode("</li><li>", $issues) . "</li></ul>");
        }
        
        return $return;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @internal param $action
     * @internal param Course $id
     */
    public function PublicCourse(Request $request){
        $id     = $request->get('id');
        $action = $request->get('action');
        
        $course = $this->courseContract->getById($id,['cou_user_id'=>auth()->user()->id]);

        if($this->checkPublicCourse($course)){
             $check_save = $course->update(['public_status' => 1]);
             if($check_save){
                return redirect()->route("teacher.build_course",['id' => $id])->withFlashSuccess("Xuất bản khóa học thành công");
            } else {
                return redirect()->route("teacher.build_course",['id' => $id])->withFlashDanger("Lỗi! Xuất bản khóa không thành công");
            }
        } else {
            return redirect()->route("teacher.build_course",['id' => $id]);
        }
    }

}