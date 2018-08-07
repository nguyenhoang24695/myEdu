<?php
/**
 * Created by PhpStorm.
 * User: hocvt
 * Date: 11/11/15
 * Time: 14:23
 */

namespace App\Events\Frontend;


use App\Events\Event;
use App\Models\CourseContent;
use App\Models\CourseContentViewLog;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Queue\SerializesModels;

class OpenCourseContent extends Event
{
    use SerializesModels;
    public $user;
    public $course_content;
    public $fire_time;
    public $token;

    /**
     * OpenCourseContent constructor.
     * @param User $user
     * @param CourseContent $courseContent
     */
    public function __construct(User $user, CourseContent $courseContent, $token)
    {
        $this->user = $user;
        $this->course_content = $courseContent;
        $this->token = $token;
        $this->fire_time = Carbon::now();

        return $this->handle();
    }

    public function handle(){
        // check user role
        $my_role = myRole($this->course_content->course, $this->user);
        if(!in_array($my_role, ['user', 'register'])){
            return true;
        }

        // get all log
        $logs = $this->course_content
            ->view_logs()
            ->where('user_id', $this->user->id)
            ->where('course_content_id', $this->course_content->id)
            ->first();
        // finished this content
        /*if(count($logs) > 0 && $logs[0]->status == config('course.content_view_status.viewed')){
            return true;
        }*/

        if($logs && $logs->status == config('course.content_view_status.viewed')){
            return true;
        }
        // created new
        $new_log = new CourseContentViewLog();
        $new_log->user_id = $this->user->id;
        $new_log->course_content_id = $this->course_content->id;
        $new_log->token = $this->token;
        $new_log->created_at = $this->fire_time;
        $new_log->updated_at = $this->fire_time;
        return $new_log->save();

    }
}