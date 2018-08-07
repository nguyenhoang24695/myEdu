<?php
/**
 * Created by PhpStorm.
 * User: hocvt
 * Date: 11/2/15
 * Time: 15:55
 */

namespace App\Http\Controllers\Api\V1\Resource;


use App\Models\Course;
use App\Models\CourseContent;
use App\Models\CourseContentViewLog;
use App\Models\CourseStudent;
use App\Models\User;
use Illuminate\Http\Request;

class CourseContentController
{
    /** @var  CourseContent */
    private $courseContent;
    /** @var  User */
    private $user;

    /**
     * CourseContentController constructor.
     */
    public function __construct()
    {
        if(auth()->guest()){
            abort('401', 'Chưa đăng nhập nhé!!!');
        }
        $this->user = auth()->user();
        $id = \Request::get('course_content_id', 0);
        //check registered ?
        $course_student = CourseStudent::whereCourseId($id)->whereUserId($this->user->id)->first();
        if(!$course_student){
            abort('401', 'Chưa đăng ký học nhé!!!');
        }
        $this->courseContent = CourseContent::find($id);
    }

    public function markAsViewed(){

    }

    public function markAsNotViewed(){

    }

    public function isViewed(){

    }

    public function loadNotes(){

    }

    public function addNotes(){

    }

    public function deleteNote(){

    }

    /**
     * Đánh dấu đang xem 1 nội dụng tại 1 thời điểm nào đó
     * @param Request $request
     */
    public function view_log(Request $request){

    }
}