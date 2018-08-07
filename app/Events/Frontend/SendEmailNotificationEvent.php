<?php

namespace App\Events\Frontend;

use App\Events\Event;
use App\Models\User;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class SendEmailNotificationEvent extends Event
{
    use SerializesModels;
    public $user; // Người nhận email
    public $data; //obj chưa nội dung gửi ['subject','body','type']

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($user,$data)
    {
        if($user instanceof User){
            $this->user     =   $user;
        } else {
            throw new \Exception('Kiểm tra lại object user');
        }
        $this->data         =  $data;
    }
}
