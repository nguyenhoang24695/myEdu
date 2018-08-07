<?php

namespace App\Http\Controllers\Frontend;

use App\Core\PromoCode\ConfigCode;
use App\Core\PromoCode\InnerPromoCode;
use App\Models\Partner;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class PromoCodeController extends Controller
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
    public function detail($code)
    {
        $code_info  =   $this->code->getByCode($code);
        if(!$code_info){
            abort(404);
        } else {
            if($code_info->user_id == auth()->user()->id){

                $discount_owner     =  ConfigCode::DISCOUNT_DIAMOND + ConfigCode::DISCOUNT_OF_SELLER;
                $discount_of_owner_after_discount_1 = $code_info->discount_max - $code_info->discount_1;
                $discount_of_owner_after_discount_2 = $discount_owner - $code_info->discount_2;
                $partner    =   new Partner();
                $is_partner =   $partner->check($code_info->user_id);
                return view('frontend.user.promo_code',
                    compact('code_info',
                            'discount_owner',
                            'discount_of_owner_after_discount_1',
                            'discount_of_owner_after_discount_2',
                            'is_partner'));
            } else {
                abort(404);
            }
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $code   =   $request->get('code');
        $id     =   $request->get('id');
        if(strlen($code) >= 4 && strlen($code) <= 6){
            if($this->code->checkCodeUnique($code)){
                return redirect()->route('frontend.code.detail',['code'=>$code])->withFlashDanger('Mã code đã tồn tại');
            } else {
                $codeByid   =   $this->code->getById($id);
                if($codeByid){
                    $edit_code  =   $codeByid->total_edit_code;
                    if($edit_code == 0){
                        $codeByid->code = $code;
                        $codeByid->total_edit_code = $edit_code+1;
                        if($codeByid->save()){
                            return redirect()->route('frontend.code.detail',['code'=>$code])->withFlashSuccess('Cập nhật mã code thành công');
                        }
                    } else {
                        return redirect()->route('frontend.code.detail',['code'=>$codeByid->code])->withFlashDanger('Bạn đã hết quyền sửa mã code');
                    }
                }
            }
        } else {
            return redirect()->route('frontend.code.detail',['code'=>$code])->withFlashDanger('Mã code chỉ bao gồm 4-6 ký tự');
        }
    }

    public function updateDiscount(Request $request)
    {
        $code       =   $request->get('code');
        $type       =   $request->get('type');
        $value      =   $request->get('value');

        $code_info  =   $this->code->getByCode($code);
        if($code_info){
            if($code_info->user_id == auth()->user()->id){
                if($type == 2){
                    //sửa chiết khấu cho khóa học của bạn
                    $discount_owner     =  ConfigCode::DISCOUNT_DIAMOND + ConfigCode::DISCOUNT_OF_SELLER;
                    $field              =  'discount_2';
                } else {
                    //sửa chiết khấu cho khóa học người khác
                    $discount_owner     =   $code_info->discount_max;
                    $field              =  'discount_1';
                }

                if($value > $discount_owner){
                    return response()->json(['message' => '% sử dụng mã không được vượt quá % bạn được hưởng'],401);
                } else {
                    $code_info->$field  =   $value;
                    if($code_info->save()){
                        return response()->json(['message' => 'Thay đổi chiết khấu thành công'],200);
                    }
                }

            } else {
                return response()->json(['message' => 'Lỗi'],500);
            }
        } else {
            return response()->json(['message' => 'Không tìm thấy thông tin mã code'],401);
        }
    }
}
