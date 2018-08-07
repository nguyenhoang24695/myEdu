<?php

namespace App\Events\Frontend;

use App\Events\Event;
use App\Models\User;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class SendNotificationEvent extends Event
{
    use SerializesModels;
    public $user; // Người nhận notify
    public $obj_sender; //Người gửi (tạo ra notify like,share)
    public $obj_related; // Đối tượng liên quan (khóa học,bài viết vv..)
    public $data; //obj chưa nội dung gửi ['subject','body','type']

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($user,$obj_sender,$obj_related,$data)
    {
        if($user instanceof User){
            $this->user     =   $user;
        } else {
            throw new \Exception('Kiểm tra lại object user');
        }

        if($obj_sender instanceof User ){
            $this->obj_sender   =   $obj_sender;
        } else {
            throw new \Exception('Kiểm tra lại config OBJ_SEND_NOTIFICATION_ID trong env');
        }

        $this->obj_related  =   $obj_related;
        $this->data         =   $data;
    }
}
