<?php
/**
 * Created by PhpStorm.
 * User: hocvt
 * Date: 3/9/16
 * Time: 10:46
 */

namespace App\Events\Frontend;


use App\Events\Event;
use App\Models\Course;
use App\Models\Reviews;
use App\Models\User;
use Illuminate\Database\Query\Builder;
use Illuminate\Queue\SerializesModels;

class ReviewChangedEvent extends Event
{
    use SerializesModels;

    /** @var  Reviews */
    public $review;
    public $action;
    public $valid_actions = [
        'create',
        'activate',
        'deactivate',
    ];

    /**
     * ReviewCreatedEvent constructor.
     */
    public function __construct(Reviews $review, $action = 'create')
    {
        $this->review = $review;
        if(!in_array($action, $this->valid_actions)){
            $action = 'create';
        }
        $this->action = $action;
        $this->handle();
    }

    public function handle(){
        /** @var Builder $course */
        $course = $this->review->course();
        if($this->review->rating < 0 || $this->review->rating > 6){
            $this->review->rating = 1;
        }
        /** @var Course $course_info */
        $course_info = $course->first();
        \DB::beginTransaction();
        if($this->action == 'activate'){
            $new_rating =
                1 * $course_info->review_count_1
                    + 2 * $course_info->review_count_2
                    + 3 * $course_info->review_count_3
                    + 4 * $course_info->review_count_4
                    + 5 * $course_info->review_count_5
                    + $this->review->rating;
            $new_rating = $new_rating/($course_info->review_count + 1);
            $course->update(['rating' => $new_rating]);
            $course->increment('review_count', 1);
            $course->increment('review_count_' . $this->review->rating, 1);
        }elseif($this->action == 'deactivate'){
            if($course_info->review_count - 1 > 0){
                $new_rating =
                    1 * $course_info->review_count_1
                    + 2 * $course_info->review_count_2
                    + 3 * $course_info->review_count_3
                    + 4 * $course_info->review_count_4
                    + 5 * $course_info->review_count_5
                    - $this->review->rating;
                $new_rating = $new_rating/($course_info->review_count - 1);
            }else{
                $new_rating = 0;
            }
            $course->update(['rating' => $new_rating]);
            $course->decrement('review_count', 1);
            $course->decrement('review_count_' . $this->review->rating, 1);
        }
        \DB::commit();

        //Gửi notify

        if($this->action == 'activate') {
            $review = $this->review;
            $course = $course_info;
            $obj_related = $course_info;
            $obj_sender = User::find($review->rev_user_id);
            $obj_user = User::find($course_info->cou_user_id);

            $data_rv['type'] = "message";
            $data_rv['subject'] = $obj_sender->name . " đánh giá khóa học <strong>" . $course_info->cou_title . "</strong> của bạn";

            $tem_type = config('notification.template.course.review.key');
            $data_rv['body'] = view('emails.notification.template', compact('tem_type', 'course', 'review'))->render();
            $data_rv['bodyMail'] = view('emails.notification.email', compact('tem_type', 'obj_user', 'course', 'review'))->render();
            $data_rv = json_decode(json_encode($data_rv), FALSE);

            event(new SendNotificationEvent($obj_user, $obj_sender, $obj_related, $data_rv));
            event(new SendEmailNotificationEvent($obj_user, $data_rv));
        }

    }
}