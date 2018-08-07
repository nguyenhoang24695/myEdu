<?php
/**
 * Created by PhpStorm.
 * User: MyPC
 * Date: 21/04/2016
 * Time: 2:42 CH
 */

namespace App\Listeners\Frontend;

use App\Events\Frontend\NotifyWhenDiscussions;
use App\Events\Frontend\SendEmailNotificationEvent;
use App\Events\Frontend\SendNotificationEvent;
use App\Models\Course;
use App\Models\Discussion;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class NotifyWhenDiscussionsHandler implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * @param NotifyWhenDiscussions $event
     */
    public function handle(NotifyWhenDiscussions $event)
    {
        $discussions  = $event->discussions;
        $obj_sender   = User::find($event->discussions->user_id);
        $course       = Course::find($event->discussions->cou_id);
        $obj_related  = $course;
        $data['type'] = "message";

        if($event->type == 'comment') {

            $obj_user     = User::find($course->cou_user_id);
            $data['subject'] = $obj_sender->name . " đã đặt câu hỏi thảo luận tại khóa học <strong>" . $course->cou_title . "</strong> của bạn ";

            $tem_type = config('notification.template.discussions.question.key');
            $data['body'] = view('emails.notification.template', compact('tem_type', 'discussions', 'course'))->render();
            $data['bodyMail'] = view('emails.notification.email', compact('tem_type', 'discussions', 'obj_user', 'course'))->render();
            $data = json_decode(json_encode($data), FALSE);
            event(new SendNotificationEvent($obj_user, $obj_sender, $obj_related, $data));
            event(new SendEmailNotificationEvent($obj_user, $data));

        } elseif ($event->type == 'reply') {

            $parent           = Discussion::find($event->discussions->parent_id);
            $obj_user         = User::find($parent->user_id);
            $data['subject']  = $obj_sender->name . " đã trả lời bình luận của bạn trong khóa học <strong>" . $course->cou_title . "</strong> ";

            $tem_type         = config('notification.template.discussions.reply.key');
            $data['body']     = view('emails.notification.template', compact('tem_type', 'discussions', 'course','obj_sender'))->render();
            $data['bodyMail'] = view('emails.notification.email', compact('tem_type', 'discussions', 'obj_user', 'course','obj_sender'))->render();
            $data = json_decode(json_encode($data), FALSE);
            event(new SendNotificationEvent($obj_user, $obj_sender, $obj_related, $data));
            event(new SendEmailNotificationEvent($obj_user, $data));

        }
    }
}