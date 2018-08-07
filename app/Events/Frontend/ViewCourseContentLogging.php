<?php
/**
 * Created by PhpStorm.
 * User: hocvt
 * Date: 11/11/15
 * Time: 14:24
 */

namespace App\Events\Frontend;


use App\Events\Event;
use App\Models\CourseContent;
use App\Models\CourseContentViewLog;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Queue\SerializesModels;

class ViewCourseContentLogging extends Event
{
    use SerializesModels;

    public $user;
    public $course_content;
    public $fire_time;
    public $token;
    public $action_log;

    /**
     * @param User $user
     * @param CourseContent $course_content
     * @param $token
     */
    public function __construct(User $user, CourseContent $course_content, $token, $action_log = false)
    {
        $this->user             =   $user;
        $this->course_content   =   $course_content;
        $this->token            =   $token;
        $this->fire_time        =   Carbon::now();
        $this->action_log       =   $action_log;

        return $this->handle();
    }

    public function handle()
    {
        // search old
        /** @var CourseContentViewLog $cc_view_log */
        $cc_view_log = CourseContentViewLog::whereUserId($this->user->id)
            ->whereCourseContentId($this->course_content->id)
            ->whereToken($this->token)
            ->whereStatus(config('course.content_view_status.open'))
            ->orderBy('updated_at', 'desc')
            ->first();
        if(!$cc_view_log)return false;

        if($this->action_log){
            return CourseContentViewLog::whereUserId($this->user->id)
                ->whereCourseContentId($this->course_content->id)
                ->update(['status' => 1]);
        }

        // valid fire time
        $from_prev = $this->fire_time->diff($cc_view_log->updated_at)->s;
        if($from_prev < config('course.content_view_log.step_log')){
            return false; // may be hacked
        }
        if($from_prev > config('course.content_view_log.time_out') + config('course.content_view_log.step_log')){
            return false; // timeout or hacked
        }

        $all_logs = CourseContentViewLog::whereUserId($this->user->id)
            ->whereCourseContentId($this->course_content->id)
            ->whereToken($this->token)
            ->get();
        $from_first = 0;
        foreach($all_logs as $log){
            $from_first += $log->updated_at->diff($log->created_at)->s;
        }
        if($from_first >= config('course.content_view_log.min_time')){
            return CourseContentViewLog::whereUserId($this->user->id)
                ->whereCourseContentId($this->course_content->id)
                //->whereToken($this->token)
                ->update(['status' => 1]);
        }else{
            return $cc_view_log->touch();
        }

    }

}