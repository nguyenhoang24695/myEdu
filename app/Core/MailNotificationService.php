<?php

namespace App\Core;

/**
* 
*/
class MailNotificationService
{
    protected $auth_type;
    protected $url_post;
    protected $auth;


    public function __construct()
    {
        $this->auth         =  config('notification.connections.send_mail.auth');
        $this->auth_type    =  config('notification.connections.send_mail.auth_type');
        $this->url_post     =  config('notification.connections.send_mail.url_post');
    }

    public function sendMail($arr_data=[])
    {
        $auth       =   new HttpAuthData();
        $response   =   $auth->Post_Data($this->auth_type,$this->auth,$this->url_post,$arr_data);
        return $response;
    }
}
?>