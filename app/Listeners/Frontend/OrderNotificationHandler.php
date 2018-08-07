<?php
/**
 * Created by PhpStorm.
 * User: MyPC
 * Date: 18/04/2016
 * Time: 5:01 CH
 */

namespace App\Listeners\Frontend;

use App\Core\PromoCode\PromoCodeManager;
use App\Events\Frontend\OrderNotification;
use App\Events\Frontend\SendEmailNotificationEvent;
use App\Events\Frontend\SendNotificationEvent;
use App\Models\BankPayment;
use App\Models\Course;
use App\Models\MobileCard;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class OrderNotificationHandler implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * @param OrderNotification $event
     */
    public function handle(OrderNotification $event)
    {
        $item_type    = $event->order->item_type;
        $item         = $item_type::find($event->order->item_id);
        $seller       = $event->order->sellingUser;
        $obj_sender   = User::find(config('notification.obj_send.id'));


        if($item instanceof Course){
            $owner        = $item->user;
            $buyer        = $event->order->buyingUser;
            $course       = $item;
            $obj_related  =  $course;
            if($event->order->promote_code != "")
            {
                $promo_code      =   new PromoCodeManager();
                $promote_code    =   $promo_code->processPromoCode($event->order->promote_code,$course);
                $partner         =   User::where('id',$promote_code['partner']['id'])->first();

                if(!empty($promote_code) && $promote_code['success'] == true){

                    //Thực hiện gửi cho Buyer
                    if($promote_code['buyer']['enjoy'] > 0){
                        $price              =   number_format($course->cou_price - ($course->cou_price*$promote_code['buyer']['enjoy'])/100);
                        $obj_user           =  $buyer;
                        $data['type']       =  "message";
                        $data['subject']    =  "Bạn đã đăng ký nhập học thành công khóa học <strong>".$course->cou_title."</strong>";

                        $tem_type           =  config('notification.template.course.buy.key');
                        $data['body']       =  view('emails.notification.template',compact('tem_type','price','course'))->render();
                        $data['bodyMail']   =  view('emails.notification.email',compact('tem_type','price','obj_user','course'))->render();
                        $data               =  json_decode(json_encode ($data), FALSE);
                        event(new SendNotificationEvent($obj_user,$obj_sender,$obj_related,$data));
                        event(new SendEmailNotificationEvent($obj_user,$data));
                    }

                    //Thực hiện gửi cho partner
                    if($promote_code['partner']['enjoy'] > 0){

                        $price              =  number_format(($course->cou_price*$promote_code['partner']['enjoy'])/100);
                        $obj_user           =  $partner;
                        $pro_code           =  $event->order->promote_code;
                        $data_p['type']     =  "message";
                        $data_p['subject']  =  "<strong>".$buyer->name."</strong> đã sử dụng mã code khuyến mại của bạn";
                        $tem_type           =  config('notification.template.course.invite.key');
                        $data_p['body']     =  view('emails.notification.template',compact('tem_type','price','course','pro_code','buyer'))->render();
                        $data_p['bodyMail'] =  view('emails.notification.email',compact('tem_type','price','obj_user','course','pro_code','buyer'))->render();
                        $data_p             =  json_decode(json_encode ($data_p), FALSE);
                        event(new SendNotificationEvent($obj_user,$obj_sender,$obj_related,$data_p));
                        event(new SendEmailNotificationEvent($obj_user,$data_p));

                    }

                    //Thực hiện gửi cho giáo viên
                    if($promote_code['seller']['enjoy'] > 0){

                        $price                 =  number_format(($course->cou_price*$promote_code['seller']['enjoy'])/100);
                        $obj_user              =  $owner;
                        $data_gv['type']       =  "message";
                        $data_gv['subject']    =  "Học viên: ".$buyer->name." vừa đăng ký tham gia khóa học: <strong>".$course->cou_title."</strong> của bạn.";
                        $tem_type              =  config('notification.template.course.register.key');
                        $data_gv['body']       =  view('emails.notification.template',compact('tem_type','price','course','buyer'))->render();
                        $data_gv['bodyMail']   =  view('emails.notification.email',compact('tem_type','price','obj_user','course','buyer'))->render();
                        $data_gv               =  json_decode(json_encode ($data_gv), FALSE);

                        event(new SendNotificationEvent($obj_user,$obj_sender,$obj_related,$data_gv));
                        event(new SendEmailNotificationEvent($obj_user,$data_gv));

                    }

                }

            } else {

                //Trường hợp không dùng mã giảm giá

                //Gửi notify cho học viên
                $price              =  number_format($course->cou_price);
                $obj_user           =  $buyer;
                $data['type']       =  "message";
                $data['subject']    =  "Bạn đã đăng ký nhập học thành công khóa học <strong>".$course->cou_title."</strong>";

                $tem_type           =  config('notification.template.course.buy.key');
                $data['body']       =  view('emails.notification.template',compact('tem_type','price','course'))->render();
                $data['bodyMail']   =  view('emails.notification.email',compact('tem_type','price','obj_user','course'))->render();
                $data               =  json_decode(json_encode ($data), FALSE);
                event(new SendNotificationEvent($obj_user,$obj_sender,$obj_related,$data));
                event(new SendEmailNotificationEvent($obj_user,$data));


                //Gửi notify cho giáo viên
                $price                 =  number_format($event->order->item_price * config('money.'.config("app.id").'.course_price_percent_for_owner') / 100);
                $obj_user              =  $owner;
                $data_gv['type']       =  "message";
                $data_gv['subject']    =  "Học viên: ".$buyer->name." vừa đăng ký tham gia khóa học: <strong>".$course->cou_title."</strong> của bạn.";
                $tem_type              =  config('notification.template.course.register.key');
                $data_gv['body']       =  view('emails.notification.template',compact('tem_type','price','course','buyer'))->render();
                $data_gv['bodyMail']   =  view('emails.notification.email',compact('tem_type','price','obj_user','course','buyer'))->render();
                $data_gv               =  json_decode(json_encode ($data_gv), FALSE);

                event(new SendNotificationEvent($obj_user,$obj_sender,$obj_related,$data_gv));
                event(new SendEmailNotificationEvent($obj_user,$data_gv));

            }

        } else {

            if($item instanceof MobileCard){
                $price   =  number_format($item->real_price);
            } elseif ($item instanceof BankPayment){
                $price   =  number_format($item->price);
            } else {
                $price   =  0;
            }

            $obj_related        =  "";
            $obj_user           =  $seller;
            $data['type']       =  "message";
            $data['subject']    =  "Bạn đã nạp tiền thành công, Số tiền nạp: ".$price." VND
                                    Nếu chưa thấy số dư, bạn vui lòng Refesh (F5) để cập nhập.";

            $tem_type           =  config('notification.template.money.recharge.successful.key');
            $data['body']       =  view('emails.notification.template',compact('tem_type','price'))->render();
            $data['bodyMail']   =  view('emails.notification.email',compact('tem_type','price','obj_user'))->render();
            $data               =  json_decode(json_encode ($data), FALSE);
            event(new SendNotificationEvent($obj_user,$obj_sender,$obj_related,$data));
            event(new SendEmailNotificationEvent($obj_user,$data));
        }

    }
}