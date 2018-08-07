<?php

namespace App\Listeners\Frontend;


use App\Events\Frontend\SendNotificationEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendNotificationHandler implements ShouldQueue
{
    use InteractsWithQueue;

    public function handle(SendNotificationEvent $event)
    {
        //Mặc định sẽ gửi notify
        if($event->user->notificationSetting->isEmpty())
        {
            $event->user->newNotification()
                        ->from($event->obj_sender)
                        ->withType($event->data->type)
                        ->withSubject($event->data->subject)
                        ->withBody($event->data->body)
                        ->regarding($event->obj_related)
                        ->send();

        } else {
            //Trương hợp thành viên có cài đặt nhận nội dung
            foreach($event->user->notificationSetting as $setting){
                if($setting->notify_type == $event->data->type){
                    if($setting->enable_profile == 1){
                        $event->user->newNotification()
                                    ->from($event->obj_sender)
                                    ->withType($event->data->type)
                                    ->withSubject($event->data->subject)
                                    ->withBody($event->data->body)
                                    ->regarding($event->obj_related)
                                    ->send();
                    }
                }
            }
        }
    }
}
?>