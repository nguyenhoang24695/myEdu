<?php

namespace App\Http\Controllers\Backend;

use App\Core\MyIndexer;
use App\Events\Frontend\SendEmailNotificationEvent;
use App\Events\Frontend\SendNotificationEvent;
use App\Exceptions\GeneralException;
use App\Jobs\SendNotification;
use App\Models\Course;
use App\Models\Lecture;
use App\Repositories\Backend\User\UserContract;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Backend\Course\CourseContract;

class CourseController extends Controller
{
    protected $course;
    protected $indexSearch;
    protected $user;

    public function __construct(CourseContract $course,MyIndexer $indexSearch, UserContract $user)
    {
        $this->course       =   $course;
        $this->indexSearch  =   $indexSearch;
        $this->user         =   $user;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($module,Request $request)
    {
        $cou_title   = $request->get('search');
        $param       = $cou_title ? ['cou_title' => ['operator' => 'LIKE','value' => "%%$cou_title%%"]] : [];
        $orderby     = ['id' => 'DESC'];
        $page_size   = 20;

        if($module   == "list"){
            $course_list = $this->course->getAllWithPaginate($param,$page_size,$orderby,'user');

        } elseif ($module == "pending") {
            $param       = ['public_status' => ['operator' => '=','value' => 1], 'cou_active' => ['operator' => '=','value' => 0]];
            $course_list = $this->course->getAllWithPaginate($param,$page_size,$orderby,'user');

        } elseif ($module == "activated") {
            $param       = ['cou_active' => ['operator' => '=','value' => 1]];
            $course_list = $this->course->getAllWithPaginate($param,$page_size,$orderby,'user');

        } elseif ($module == "deleted") {
            $course_list = $this->course->getAllWithOnlyTrashedPaginate($param,$page_size,$orderby,'user');

        } else {
            $course_list = $this->course->getAllWithPaginate($param,$page_size,$orderby,'user');
        }

        if($course_list){
            $course_list->load('category');
        }
        
        return view('backend.course.index',compact('course_list','module'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $course = Course::find($id);
        if(!$course){
            abort(404);
        }
        $course_contents = $course->course_contents()->orderBy('content_order')->get();
        return view('frontend.teacher.course.edit_content', compact('course_contents', 'course'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateCourseInfo($id)
    {
        /** @var Course $course */
        $course = Course::find($id);
        if(!$course){
            return back();
        }
        else{
            $course->updateCounter();
            $course->updateReview();
            return back();
        }
    }

    public function active($id){
        $coursebyid   =  $this->course->getById($id);
        $value        =  abs($coursebyid->cou_active - 1);
        $course_name  =  $coursebyid->cou_title;
        $obj_user     =  $this->user->findOrThrowException($coursebyid->cou_user_id);
        $coursebyid->update(["cou_active" => $value]);

        //Gửi notify
        if($value == 1){
            $course             =  $coursebyid;
            $obj_sender         =  \Auth::user();//->id;
            $data['type']       =  "message";
            $data['subject']    =  "Khóa học: ".$course_name." của bạn đã được duyệt";
            $tem_type           =  config('notification.template.course.active.key');
            $data['body']       =  view('emails.notification.template',compact('tem_type','course'))->render();
            $data['bodyMail']   =  view('emails.notification.email',compact('tem_type','course','obj_user'))->render();
            $data               =  json_decode(json_encode ($data), FALSE);
            event(new SendNotificationEvent($obj_user,$obj_sender,$coursebyid,$data));
            event(new SendEmailNotificationEvent($obj_user,$data));
        }

        $this->indexSearch->indexCourse($coursebyid);
    }
    
    public function update_lecture_data_length() {
        Lecture::chunk(10, function(Collection $lectures){
            /** @var Lecture $lecture */
            foreach($lectures as $lecture){
                $lecture->updateDataLength();
            }
        });
        Course::chunk(10, function(Collection $courses){
            /** @var Course $course */
            foreach($courses as $course){
                if(\Cache::has('course_content_' . $course->id))
                    \Cache::forget('course_content_' . $course->id);
            }
        });
        return back()->withFlashSuccess("Cập nhật thành công");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        throw new GeneralException("Not supported yet!!!");
        $coursebyid   =  $this->course->getById($id);

        if($coursebyid->delete()){
            $course             =  $coursebyid;
            $obj_sender         =  \Auth::user()->id;
            $data['type']       =  "message";
            $data['subject']    =  "Khóa học: ".$coursebyid->cou_title." của bạn đã bị xóa";
            $tem_type           =  config('notification.template.course.delete.key');
            $data['body']       =  view('emails.notification.template',compact('tem_type','course'))->render();
            $data['bodyMail']   =  view('emails.notification.email',compact('tem_type','course','obj_user'))->render();
            $data               =  json_decode(json_encode ($data), FALSE);
            event(new SendNotificationEvent($obj_user,$obj_sender,$coursebyid,$data));
            event(new SendEmailNotificationEvent($obj_user,$data));
        }

        return redirect()->route('backend.course.list')->withFlashSuccess('Xóa bản ghi thành công');
    }
}
