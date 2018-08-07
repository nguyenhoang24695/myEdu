<?php

namespace App\Listeners\Frontend;

use App\Core\MailNotificationService;
use App\Events\Frontend\SendEmailNotificationEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendEmailNotificationHandler implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Handle the event.
     *
     * @param  SendEmailNotificationEvent  $event
     * @return void
     */
    public function handle(SendEmailNotificationEvent $event)
    {
        //Gửi Email
        $mailService           =   new MailNotificationService();
        //Chuẩn hóa dữ liệu gửi mail
        $arr_data['user_id']   =   config('notification.connections.send_mail.user_id');
        $arr_data['mail_to']   =   $event->user->email;
        $arr_data["subject"]   =   $event->data->subject;
        $arr_data["body"]      =   $event->data->bodyMail;
        $arr_data["type"]      =   $event->data->type;

        if($event->user->notificationSetting->isEmpty())
        {
            $mailService->sendMail($arr_data);
        } else {
            //Trương hợp thành viên có cài đặt nhận nội dung
            foreach($event->user->notificationSetting as $setting){
                if($setting->notify_type == $event->data->type){
                    if($setting->enable_email == 1){
                        $mailService->sendMail($arr_data);
                    }
                }
            }
        }
    }
}
