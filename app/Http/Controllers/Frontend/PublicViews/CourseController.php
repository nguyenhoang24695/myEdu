<?php
/**
 * Created by PhpStorm.
 * User: hocvt
 * Date: 10/5/15
 * Time: 08:56
 */

namespace App\Http\Controllers\Frontend\PublicViews;


use App\Core\PromoCode\ConfigCode;
use App\Core\PromoCode\InnerTrackingLink;
use App\Http\Controllers\Controller;
use App\Models\TrackingLink;
use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\Partner;
use App\Models\PromoCode;
use App\Repositories\Frontend\Course\CourseContract;
use App\Repositories\Frontend\Reviews\ReviewsContract;
use App\Repositories\Frontend\Blog\BlogContract;

class CourseController extends Controller
{
    private $reviews;
    private $blog;
    private $course;
    private $trackLink;
    public function __construct(ReviewsContract $reviews,BlogContract $blog,CourseContract $course,InnerTrackingLink $trackLink){
        $this->reviews      =   $reviews;
        $this->blog         =   $blog;
        $this->course       =   $course;
        $this->trackLink    =   $trackLink;
    }
    public function detail($slug, Request $request){
        /** @var Course $course */
        //if(\Access::hasRole("Administrator")){
        //    $course = Course::find($id);
        //} else {
            $course = Course::findBySlugOrId($slug);
            if(!$course){
                abort(404);
            }
            $id = $course->id;
            $this->course->incrementView($id);
        //}

        \SEOMeta::addKeyword($course->course_tags);
        \SEO::setTitle($course->cou_title);
        \SEO::setDescription($course->description);

        \OpenGraph::setDescription(htmlentities($course->description));
        \OpenGraph::setTitle(htmlentities($course->cou_title));
        \OpenGraph::setUrl(\Request::url());
        \OpenGraph::addProperty('type', 'product');
        \OpenGraph::addImage($course->get_cached_image('cc_large'));
        \OpenGraph::setSiteName(config('app.name'));


        $my_role = myRole($course);

        if($my_role != 'admin'
            && $my_role != 'teacher'
            && !$course->isActive()){
            abort(404, "Khóa học chưa được duyệt");
        }

        if($my_role == 'register'){
            // redirect to course preview for registered user
            return redirect($course->get_registered_view_link());
        }

        if(!$course){
            abort(404);
        }

        //Kiểm tra user login có phải partner hay ko
        //Phục vụ việc tạo link
        $partner             = "";
        $code_info           = "";
        $discount_owner      = 0;
        if(\Access::user()){
            $new_partner     = new Partner();
            $partner         = $new_partner->check(\Access::id());

            //Lấy thông tin mã code của partner
            $code_info		 =	PromoCode::where('user_id',\Access::id())->where('active',1)->first();
            $discount_owner  =  ConfigCode::DISCOUNT_DIAMOND + ConfigCode::DISCOUNT_OF_SELLER;
        }

        //Lấy thông tin mã code nếu là link share
        $uuid               =   $request->get('code', null);

        if($uuid !== null){
            //Kiểm tra mã code có tồn tại hay không
            $check_code =   TrackingLink::findUUID($uuid);
            if(!$check_code){
                return redirect($course->get_public_view_link());
            }
            //ADD nofollow
            \SEOMeta::addMeta('robots', 'NOINDEX, NOFOLLOW', 'content');
        }

        $new_price  =   0;
        $discountOfLink     =   $this->trackLink->processUUID($course,$uuid);
        if($discountOfLink && $discountOfLink->discount > 0){
            $new_price  = $course->cou_price - ($course->cou_price * $discountOfLink->discount)/100;
        }

        //Lấy khóa học liên quan cùng danh mục
        $orderby         = ['created_at' => 'DESC'];
        $id_cat_in       = [$course->cou_cate_id];
        $where           = ['cou_active' => 1];
        $course_list     = $this->course->getCourseByCategoryId($where,$orderby,$id_cat_in,5);

        $list_reviews    = $this->reviews->getListReviews($id);
        $total_course    = $this->course->getTotalCourse($course->cou_user_id);

        $data['list_reviews']    = $list_reviews;

        $data['course']          = $course;
        $data['course_list']     = $course_list;
        $data['teacher']         = $course->user;
        $data['total_course']    = $total_course;

        $data['course_video_demo'] = 'Not support';//$course->get_intro_video_stream_link();

        $data['course_preview_image'] = $course->get_cached_image('cc_large');

        $data['course_contents'] = $course->getCachedContents();
        $data['my_role']         = $my_role;
        $data['partner']         = $partner;
        $data['code_info']       = $code_info;
        $data['discount_owner']  = $discount_owner;
        $data['new_price']       = $new_price;

        if($request->query('partner', '') != '' ){
            $data['force_ca'] = true;
        }

        $cou_skill_level_name = trans('course.learning_capacity.all');
        foreach(config('course.learning_capacity') as $k => $v){
            if($v = $course->cou_skill_level){
                $cou_skill_level_name = trans('course.learning_capacity.'.$k);
                break;
            }
        }

        $data['cou_skill_level_name'] = $cou_skill_level_name;

        return view('frontend.student.course.public_course_preview', $data);
    }
}