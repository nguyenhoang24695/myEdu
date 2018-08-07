<?php
/**
 * Created by PhpStorm.
 * User: hocvt
 * Date: 10/5/15
 * Time: 08:57
 */

namespace App\Http\Controllers\Frontend\Student;


use App\Core\Money\Contracts\WalletContract;
use App\Core\Money\Utils\OrderManager;
use App\Core\Money\Utils\TransactionManager;
use App\Core\PromoCode\PromoCodeManager;
use App\Events\Frontend\OpenCourseContent;
use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseContent;
use App\Models\CourseContentViewLog;
use App\Models\CourseStudent;
use App\Models\Discussion;
use App\Models\TrackingLink;
use App\Models\UserExams;
use App\Repositories\Frontend\Course\Cods\CourseCodsContract;
use App\Repositories\Frontend\Course\CourseContract;
use App\Repositories\Frontend\Discussion\DiscussionContract;
use App\Repositories\Frontend\Discussion\EloquentDiscussionRepository;
use App\Repositories\Frontend\Reviews\ReviewsContract;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    private $reviews;
    /** @var EloquentDiscussionRepository */
    private $discussions;
    private $course;
    public $course_cod;
    public function __construct(ReviewsContract $reviews,DiscussionContract $discussion,CourseContract $course, CourseCodsContract $courseCodsContract){
        $this->reviews = $reviews;
        $this->discussions = $discussion;
        $this->course  = $course;
        $this->course_cod = $courseCodsContract;
    }
    public function detail($slug){
        /** @var Course $course */
        $course = Course::findBySlugOrId($slug);
        $id = $course->id;
        if(!$course){
            abort(404);
        }

        //$course->updateReview();

        \SEOMeta::setTitle($course->cou_title);
        \SEOMeta::setDescription($course->description);
        \SEOMeta::addKeyword($course->course_tags);

        $my_role = myRole($course);
        if($my_role == 'guess' || $my_role == 'user'){
            return redirect($course->get_public_view_link());
        }

//        $course->updateReview();

        //Lấy danh sách bình luận
        $discussion_all = $this->discussions->getListDiscussionWidthSimplePaginate($id);

        $list_reviews    = $this->reviews->getListReviews($id);
//        $total_rate      = $list_reviews->total();
//        $avg_rate        = $this->reviews->getAvgRating($id,$total_rate);
//        $arr_per_rating  = $this->reviews->getPercentFollowRating($id,$total_rate);
//        $html_rating     = $this->reviews->getHtmlRating($avg_rate);

        $orderby         = ['created_at' => 'DESC'];
        $id_cat_in       = [$course->cou_cate_id];
        $where           = ['cou_active' => 1];
        $course_list     = $this->course->getCourseByCategoryId($where,$orderby,$id_cat_in,5);
        
//        $data['arr_per_rating']  = $arr_per_rating;
//        $data['html_rating']     = $html_rating;
//        $data['avg_rate']        = $avg_rate;
        $data['list_reviews']    = $list_reviews;
        $data['course']          = $course;
        $data['course_list']     = $course_list;
        $data['discussion_all']  = $discussion_all;
        $data['course_contents'] = $course->getCachedContents();

        $data['last_study'] = $this->getLastStudyContent($data['course_contents']);

        $data['results'] = UserExams::where('user_id', auth()->id())
            ->get();

        javascript()->put([
            'link_student_list' => route('api.course.students'),
            'course_id' => $course->id,
            'view_status_link' => route('api.course.view_status'),
            'current_course_id' => $course->id,
        ]);

        return view('frontend.student.course.registered_course_preview', $data);
    }

    /**
     *
     * @param $slug
     * @param int $content_id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Exception
     */
    public function study($slug, $content_id = 0){
        /** @var Course $course */
        $course = Course::findBySlugOrId($slug);
        if(!$course){
            abort(404);
        }
        $id = $course->id;
        $my_role = myRole($course);
        \SEOMeta::setTitle(trans('meta.course.study', ['course' => $course->cou_title]));
        \SEOMeta::setDescription($course->description);
        \SEOMeta::addKeyword($course->course_tags);

        if(config('seo.no_follow.default_study_lecture_link')){
            \SEOMeta::addMeta('robots', 'nofollow');
        }

        if(!in_array($my_role, ['register', 'teacher', 'admin', 'course_admod'])){
            return redirect($course->get_public_view_link());
        }

        //dd($course->course_contents);
        // get info
        $course_contents = $course->getCachedContents();
        //$course->course_contents()->orderBy('content_order','asc')->get();

        /** @var CourseContent $viewing_content */
        list($prev_content, $viewing_content, $next_content) = $this->getViewingContent($id, $content_id, $course_contents);
	
//	    dd($viewing_content);
	
	    if(!$viewing_content){
//	    	\Session::flash('flash_danger')
		    return redirect()->route('home')->withFlashDanger('Bài học chưa có nội dung');
	    }

        //Kiểm tra xem content prev có phải là dạng bài kiểm tra yêu cầu bắt buộc hay ko
        //nếu yêu cầu thì cần kiểm tra xem user đã vượt qua hay chưa.
        if($prev_content && $prev_content->get_type() == config('course.content_types.quizzes')){
            $check_quiz = $prev_content->getContent();
            if($check_quiz->require){
                //Nếu yêu câu bắt buộc thì kt tra xem hv đã vượt qua hay chưa
                $quiz_status_log = $prev_content->view_logs()
                    ->where('user_id', auth()->user()->id)
                    ->where('course_content_id', $prev_content->id)
                    ->first();
                if(empty($quiz_status_log) || $quiz_status_log && $quiz_status_log->status == config('course.content_view_status.open')){
                    return redirect($course->get_default_studying_link($prev_content->id))->withFlashDanger('Để học bài tiếp theo bạn cần vượt qua bài kiểm tra sau đây.');
                }
            }
        }

        $section = false;
        $lecture = false;
        $quizzes = false;
        $not_suppoted_type = false;
        $questionJson      = [];

        if($course_contents && $viewing_content){
            if($viewing_content->get_type() == config('course.content_types.section')){
                $section = $viewing_content->getContent();
            }elseif($viewing_content->get_type() == config('course.content_types.lecture')){
                $lecture = $viewing_content->getContent();
            }elseif($viewing_content->get_type() == config('course.content_types.quizzes')){
                $quizzes = $viewing_content->getContent();
                $questionJson['info'] = [
                    'name'     => $quizzes->get_title(),
                    'main'     => $quizzes->get_sub_title(),
                    'results'  => 'Chúc mừng bạn đã đã hoàn thành bài kiểm tra.',
                    'messTrue' => 'Bạn đang học rất tốt hãy giữ vững phong độ.', // Thông báo khi làm bài đúng
                    'messFalse'=> 'Bạn cần xem lại bài giảng để có kết quả tốt hơn.', // Thông báo khi làm bài chưa đúng
                    'urlNext'  => ($next_content) ? $course->get_default_studying_link($next_content->id) : '',
                    'urlBack'  => ($prev_content) ? $course->get_default_studying_link($prev_content->id) : ''
                ];

                if($quizzes->question){
                    $q_in   =   0;
                    foreach($quizzes->question as $question){
                        $questionJson['questions'][$q_in]['q'] = $question->title;
                        if($question->answer){
                            foreach($question->answer as $answer){
                                $questionJson['questions'][$q_in]['a'][]  =  [
                                    'option'  => $answer->content,
                                    'correct' => ($answer->is_true) ? true : false,
                                    'note'    => $answer->note];
                            }
                        }
                        $questionJson['questions'][$q_in]['correct']    = '<p><span>Chính xác.</span></p>';
                        $questionJson['questions'][$q_in]['incorrect']  = '<p><span>Chưa chính xác.</span> Bạn cần xem lại bài giảng để có câu trả lời chính xác!</p>';
                        $q_in++;
                    }
                }
            }else{
                $not_suppoted_type = "Chưa hỗ trợ loại bài giảng này";
            }
        }else{
            $not_suppoted_type = "Khóa học chưa có bài học nào.";
        }

        //Lấy danh sách bình luận
        $discussion_all = $this->discussions->getListDiscussionWidthSimplePaginate($id,$content_id);

        $link_course_portal = $course->get_registered_view_link();
        $link_course_building = $my_role == 'teacher' ? route('teacher.build_course', ['id' => $course->id]) : false;


        $view_log_token = makeViewToken(auth()->user()->id ."-". $viewing_content->id);
        event(new OpenCourseContent(auth()->user(), $viewing_content, $view_log_token));

        javascript()->put([
            'link_my_note_list' => route('frontend.course.note', ['id' => $id, 'content_id' => $viewing_content->id]),
            'link_to_lecture_info' => route('frontend.course.lecture_info', ['course_content_id' => $viewing_content->id]),
            'view_status_link' => route('api.course.view_status'),
            'view_status_log_link' => route('api.course.view_status_log'),
            'view_status_log_quiz' => route('api.course.view_status_log_quiz'),
            'require_quiz'  => ($quizzes) ? $quizzes->require : 0,
            'current_course_id' => $course->id,
            'current_content_id' => $viewing_content->id,
            'view_token' => $view_log_token,
            'step_log' => config('course.content_view_log.step_log'),
            'questionJson'  => json_encode($questionJson)
        ]);

        $data = compact('course',
            'course_contents',
            'my_role',
            'prev_content',
            'viewing_content',
            'next_content',
            'section',
            'lecture',
            'quizzes',
            'not_suppoted_type',
            'link_course_portal',
            'link_course_building',
            'discussion_all',
            'content_id');
        /** @todo tesitng video player moi */
        $force_player = \Request::query('player', null);
        if( $force_player != null){
            $data['video_player'] = $force_player;
        }

        return view('frontend.student.course.default_study',
            $data);
    }

    public function public_study($slug, $content_id = 0){
        /** @var Course $course */
        $course = Course::findBySlugOrId($slug);
        $id = $course->id;
        $my_role = myRole($course);
        if(!$course){
            abort(404);
        }

        if(!$course->isPublicStudy($content_id)){
            abort(404, 'Khóa học không được truy cập public');
        }elseif(auth()->guest() // chưa đăng nhập
                && config('course.guess_access_free_course', true) === false // không cho phép học miễn phí ko đăng nhập
                && $course->cou_price < 1){ // khóa học free
            return back()->withFlashDanger("Bạn cần đăng nhập để học thử khóa học này");
        }

        \SEOMeta::setTitle(trans('meta.course.study', ['course' => $course->cou_title]));
        \SEOMeta::setDescription($course->description);
        \SEOMeta::addKeyword($course->course_tags);

        if(in_array($my_role, ['register'])){
            // nếu là người đã đăng ký khóa học thì chuyển về trang học mặc định
            return redirect($course->get_public_view_link());
        }

        //dd($course->course_contents);
        // get info
        $course_contents = $course->getCachedContents();
        //$course->course_contents()->orderBy('content_order','asc')->get();

        /** @var CourseContent $viewing_content */
        list($prev_content, $viewing_content, $next_content) = $this->getViewingContent($id, $content_id, $course_contents);

        if($viewing_content){
            \SEOMeta::setTitle($viewing_content->get_title());
            \SEOMeta::setDescription(str_limit(strip_tags($viewing_content->get_sub_title())), 256);

        }

        if(config('seo.no_follow.public_study_lecture_link')){
            \SEOMeta::addMeta('robots', 'nofollow');
        }

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
                $questionJson['info'] = [
                    'name'     => $quizzes->get_title(),
                    'main'     => $quizzes->get_sub_title(),
                    'results'  => 'Chúc mừng bạn đã đã hoàn thành bài kiểm tra.',
                    'messTrue' => 'Bạn đang học rất tốt hãy giữ vững phong độ.', // Thông báo khi làm bài đúng
                    'messFalse'=> 'Bạn cần xem lại bài giảng để có kết quả tốt hơn.', // Thông báo khi làm bài chưa đúng
                    'urlNext'  => $course->get_default_studying_link($next_content->id),
                    'urlBack'  => $course->get_default_studying_link($prev_content->id)
                ];

                if($quizzes->question){
                    $q_in   =   0;
                    foreach($quizzes->question as $question){
                        $questionJson['questions'][$q_in]['q'] = $question->title;
                        if($question->answer){
                            foreach($question->answer as $answer){
                                $questionJson['questions'][$q_in]['a'][]  =  [
                                    'option'    => $answer->content,
                                    'correct'   => ($answer->is_true) ? true : false,
                                    'note'      => $answer->note];
                            }
                        }
                        $questionJson['questions'][$q_in]['correct']    = '<p><span>Chính xác.</span></p>';
                        $questionJson['questions'][$q_in]['incorrect']  = '<p><span>Chưa chính xác.</span> Bạn cần xem lại bài giảng để có câu trả lời chính xác!</p>';
                        $q_in++;
                    }
                }
            }else{
                $not_suppoted_type = "Chưa hỗ trợ loại bài giảng này";
            }
        }else{
            $not_suppoted_type = "Khóa học chưa có bài học nào.";
        }

        //Lấy danh sách bình luận
        //$discussion_all = $this->discussions->getListDiscussionWidthSimplePaginate($id,$content_id);

        $link_course_portal = $course->get_registered_view_link();
        $link_course_building = false;//$my_role == 'teacher' ? route('teacher.build_course', ['id' => $course->id]) : false;


        //$view_log_token = makeViewToken(auth()->user()->id ."-". $viewing_content->id);

        //event(new OpenCourseContent(auth()->user(), $viewing_content, $view_log_token));

        javascript()->put([
         //   'link_my_note_list' => route('frontend.course.note', ['id' => $id, 'content_id' => $viewing_content->id]),
         //   'link_to_lecture_info' => route('frontend.course.lecture_info', ['course_content_id' => $viewing_content->id]),
         //   'view_status_link' => route('api.course.view_status'),
            'view_status_log_link' => route('api.course.view_status_log'),
            'current_course_id' => $course->id,
            'current_content_id' => $viewing_content->id,
            //'view_token' => $view_log_token,
            'step_log' => config('course.content_view_log.step_log'),
            'questionJson'  => json_encode($questionJson)
        ]);

        $data = compact('course',
            'course_contents',
            'my_role',
            'prev_content',
            'viewing_content',
            'next_content',
            'section',
            'lecture',
            'quizzes',
            'not_suppoted_type',
            'link_course_portal',
            'link_course_building',
            'discussion_all',
            'content_id');

        /** @todo tesitng video player moi */
        $force_player = \Request::query('player', null);
        if( $force_player != null){
            $data['video_player'] = $force_player;
        }

        return view('frontend.student.course.public_study',
            $data
            );
    }

    public function studying(){

        \SEOMeta::setTitle(trans('meta.course.studying'));

        $my_id = auth()->user()->id;
        $courses = CourseStudent::with(['course', 'course.user'])->whereUserId($my_id)->simplePaginate();

        return view('frontend.student.course.studying', ['courses' => $courses]);
    }

    /**
     * Trang thanh toán
     * Hiển thị thông tin khóa học, giá và các thông tin khuyến mãi liên quan đã áp dụng vào khóa học của người dùng
     * @param Request $request
     * @param $course_id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function pre_register(Request $request, $course_id)
    {
        /** @var Course $course */
        $course = Course::findOrFail($course_id);

        if($this->course_cod->activate_course($course_id, auth()->user()->id, $request->get('promote_code', '')) == true)
        {
            return redirect($course->get_default_studying_link());
        }
        else
        {
        /** @todo Lưu lại khóa học cuối cùng muốn đăng ký, có thể dùng khi nạp tiền rồi đăng ký tiếp */
        \Session::set('last_wish_course', $course_id);

        /** @var WalletContract $wallet */
        $wallet = auth()->user();
        /** @todo Nên thử lấy promote code từ cookie xem có không nhưng ưu tiên promote code được cập nhật qua post(code mới nhất) */

        $current_promote_code = $request->get('promote_code', '');
        $discount  = 0;

        /** @todo xư lý mã giảm giá đưa ra thông báo */
        $promote_code_message = "Bạn đang không sử dụng mã giảm giá nào";
        // fake xu ly code
        if($current_promote_code != "" && !preg_match('/^[0-9a-zA-Z]{4,6}$/', $current_promote_code)){
            $promote_code_message = "Mã giảm giá <b class='text-danger'>" . $current_promote_code . "</b> không đúng.";
            $current_promote_code = "";
        } elseif ($current_promote_code != ""){

            $code           = new PromoCodeManager();
            $promote_code   = $code->processPromoCode($current_promote_code,$course);
            if(isset($promote_code['success']) && $promote_code['success'] == false) {
                return redirect()->route('frontend.course.pre_register_course', ['course_id' => $course->id])->withFlashDanger($promote_code['message']);
            }

            if(!empty($promote_code))
            {
                if($promote_code['buyer']['enjoy'] > 0){
                    $discount   =   $promote_code['buyer']['enjoy'];
                }
            }

            $promote_code_message = "Bạn đang sử dụng mã giảm giá <b class='text-danger'>"
                . $current_promote_code ."</b>"
                . " được giảm <b class='text-danger'>" . $discount . "%</b>";
        }


        //Lấy promocode từ cookie

        $cookie_course  = \Cookie::get('UUID-COURSE');
        if(isset($cookie_course) && !empty($cookie_course[$course->id]) && $current_promote_code == ""){
            //Lấy thông tin code để tính % chiết khấu
            $discount_link        = TrackingLink::findUUID($cookie_course[$course->id][0]);
            if($discount_link){
                foreach($discount_link as $track){
                    $current_promote_code = $track->id;
                    $discount             = $track->discount;
                    $promote_code_message = "";
                }
            }
        }

        // các giá trị sau khi tinh toán promote code ra % chiết khấu
        $course_price = $course->cou_price;
        $course_price_discounted = $course_price * (1 - $discount/100);
        $wallet_before = $wallet->secondaryAmount();
        $wallet_after = $wallet_before - $course_price_discounted;

        return view('frontend.student.course.pre_register', [
            'course'                    => $course,
            'course_price'              => $course_price,
            'discount'                  => $discount,
            'course_price_discounted'   => $course_price_discounted,
            'wallet_before'             => $wallet_before,
            'wallet_after'              => $wallet_after,
            'current_promote_code'      => $current_promote_code,
            'promote_code_message'      => $promote_code_message,
            'cookie_course'             => $cookie_course,
            'check'                     => 1
        ]);
        }
    }

    /**
     * Nhận xử lý giao dịch thanh toán và đăng ký khóa học cho học viên
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function register(Request $request){

        $course_id      = $request->input('course_id');
        $user_id        = $request->input('user_id');
        $promote_code   = $request->get('promote_code', '');

        /** @var Course $course */
        $course = Course::find($course_id);
        if($user_id != auth()->user()->id){
            abort(404, 'Lỗi dữ liệu post');
        }

        $my_role = myRole($course);
        if($my_role == 'teacher'){
            return redirect($course->get_default_studying_link());
        }
        if($my_role == 'register'){
            return redirect($course->get_default_studying_link());
        }

        //dd([$my_role,$course->cou_price]);
        if($my_role == 'user' && $course->cou_price == 0){
            if(auth()->user()->registerCourse($course)){
                return redirect($course->get_default_studying_link());
            }else{
                return back()->withFlashDanger("Không tham gia khóa học được.");
            }
        }else{
            //
            $transaction_manager = new TransactionManager();
            $res = $transaction_manager->buyCourse(auth()->user(), $course,$promote_code);
            if($res['success'] == false){
                return back()->withFlashDanger($res['message']);
            }
            else{
                return redirect($course->get_default_studying_link());
            }
        }
        return back()->withFlashDanger("Error");
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

    public function lecture_info($course_content_id){
        $course_content = CourseContent::find($course_content_id);
        if(!$course_content){
            abort(404, "Không tìm thấy bài học");
        }

        return view('frontend.student.course.content.lecture_info', ['course_content' => $course_content]);
    }

    private function getLastStudyContent($course_contents){
        $user_id = auth()->user()->id;
        /** @var CourseContent $content_studying */
        $content_studying = null;
        foreach($course_contents as $course_content){
            /** @var CourseContent $course_content */
            if($course_content->get_type() == config('course.content_types.lecture')){
                $view_status = CourseContentViewLog::whereUserId($user_id)->whereCourseContentId($course_content->id)
                    ->first(['status']);
                if(!$view_status){
                    $content_studying = $course_content;
                    break;
                }
                if($view_status->status == 0){
                    $content_studying = $course_content;
                    break;
                }
                if($view_status->status == 1){
                    $content_studying = $course_content;
                }
            }

        }
        return $content_studying;
    }

}