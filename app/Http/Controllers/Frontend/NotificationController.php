<?php

namespace App\Http\Controllers\Frontend;

use App\Core\HttpAuthData;
use App\Models\Course;
use App\Models\User;
use App\Repositories\Frontend\Notification\NotificationContract;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use GuzzleHttp;

class NotificationController extends Controller
{
    protected $notify;
    public function __construct(NotificationContract $notificationContract)
    {
        $this->notify = $notificationContract;
    }

    /**
     * Action
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function action(Request $request)
    {
        $type = $request->get('type');
        $pk   = $request->get('pk');
        if($type == 'remove'){
            $this->notify->deleteNotification($pk);
            return response()->json(['success'=>true,'message'=>'Xóa thông báo thành công.']);
        } else {
            $this->notify->isMarkReadNotification($pk);
            return response()->json(['success'=>true,'message'=>'Bạn đã đọc thông báo.']);
        }
    }

    public function test()
    {
        //Gửi thông báo
        $type    = "message";
        $subject = "Được duyệt trở thành giảng viên tại Unibee";
        $body    = "thân mến.";

        $user_id          = 4;
        $data["user_id"]  = $user_id;
        $data["mail_to"]  = "thaonguyenvan90@gmail.com";
        $data["subject"]  = $subject;
        $data["body"]     = $body;
        $data["type"]     = $type;

        $url              = "http://mail.123doc.org/api/event.php";
        $arr_auth         = ['name'=>'123doc','pass'=>'2015123doc'];

        $auth             = new HttpAuthData();
        $repon = $auth->Post_Data('basic',$arr_auth,$url,$data);
        dd($repon);

        /*$sender  = User::find(1);
        $user    = User::find(1);

        //$obj   = Course::find(5);//đối tượng liên quan
        $obj     = 0; //đối tượng liên quan

        $user->createNotification($type,$subject,$body,$sender,$obj);*/
    }
}
