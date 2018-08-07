<?php

namespace App\Http\Controllers\Backend;

use App\Core\PromoCode\InnerPromoCode;
use App\Events\Frontend\SendEmailNotificationEvent;
use App\Events\Frontend\SendNotificationEvent;
use App\Models\Partner;
use App\Models\PromoCode;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\User;

class PartnerController extends Controller
{
    protected $code;

    public function __construct(InnerPromoCode $code)
    {
        $this->code = $code;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $partners = Partner::paginate(20);
        return view('backend.partner.index',compact('partners'));
    }

    /**
     * Duyệt trở thành partner
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function active($id)
    {
        $partner = Partner::find($id);
        if($partner) {

            //Kiểm tra xem user đã có mã code hay chưa
            //Chưa có thì tạo mới
            $check           = PromoCode::where('user_id',$partner->user_id)->first();
            if(!$check){
                $this->code->createCode($partner->user_id);
            }

            $obj_user        = User::find($partner->user_id);
            //Đánh dấu đã duyệt
            $partner->active = 1;
            if($partner->save()){

                //Notify
                $obj_related		=  0;
                $obj_sender         =  User::find(config('notification.obj_send.id'));

                $data['type']       =  "message";
                $data['subject']    =  "Bạn đã được xét duyệt trở thành Partner của " . config('app.name');

                $tem_type           =  config('notification.template.user.partner.successful.key');
                $data['body']       =  view('emails.notification.template',compact('tem_type'))->render();
                $data['bodyMail']   =  view('emails.notification.email',compact('tem_type','obj_user'))->render();
                $data               =  json_decode(json_encode ($data), FALSE);

                event(new SendNotificationEvent($obj_user,$obj_sender,$obj_related,$data));
                event(new SendEmailNotificationEvent($obj_user,$data));

                return redirect()->route('backend.partner.module',['module' => 'list'])->withFlashSuccess('Duyệt thông tin thành công');
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $partner = Partner::find($id);
        $partner->forceDelete();
        return redirect('admin/partner/list')->withFlashSuccess('Xóa bản ghi thành công');
    }
}
