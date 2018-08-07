<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Partner;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class PartnerController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function info()
    {
        return view('frontend.'.config("app.id").'.partner.info');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function register()
    {
        if(\Auth::guest()){
            return redirect('auth/login');
        } else {
            return view('frontend.'.config("app.id").'.partner.register');
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //Kiểm tra đăng nhập
        if(\Auth::guest()){
            return redirect('auth/login');
        } else {
            \Validator::extendImplicit('required_if_include', function($attribute, $value, $parameters, $validator){
                $values = array_get($validator->getData(), $parameters[0], null);

                if($values != null && in_array($parameters[1], $values)){
                    if(empty($value)){
                        return false;
                    }
                }
                return true;
            });
            // validate
            $this->validate($request, [
                'marketing_method' => 'required',

                'address_website' => 'url|required_if_include:marketing_method,marketing_website',
                'views_website' => 'numeric|required_if_include:marketing_method,marketing_website',

                'address_social' => 'url|required_if_include:marketing_method,marketing_social',

                'marketing_other_detail' => 'required_if_include:marketing_method,marketing_other',
                'access' => 'required',
            ], [
                'marketing_method.required' => 'Bạn hãy chọn một phương thức',
                'access.required' => 'Bạn hãy chọn một kênh',

                'address_website.required_if_include' => 'Nhập liên hết website của bạn',
                'address_website.url' => 'Liên hết website của bạn không đúng',
                'views_website.required_if_include' => 'Nhập số lượt truy cập website của bạn',
                'views_website.numeric' => 'Số lượt truy cập website của bạn phải là số',

                'address_social.required_if_include' => 'Nhập liên kết mạng xã hội của bạn',
                'address_social.url' => 'Liên kết mạng xã hội của bạn không đúng',

                'marketing_other_detail.required_if_include' => 'Hãy viết chi tiết kênh khác',
            ]);
//            die("Pass");

            $datas = $request->only([
                'address_website',
                'views_website',
                'address_social',
                'marketing_other_detail',
                'access',
            ]);
            $methods = $request->get('marketing_method');
            foreach($methods as $method){
                $datas[$method] = trans('common.partner.methods.' . $method);
            }

            $check      = Partner::where('user_id',\Auth::user()->id)->first();
            if($check) {
                return redirect('partner/register')->withFlashDanger("Thông tin đăng ký của bạn đã được gửi");
            } else {
                $datas['user_id'] = \Auth::user()->id;
                $create     = Partner::create($datas);
                if($create) {
                    $module = "success";
                    return view('frontend.'.config("app.id").'.partner.register',compact('module'));
                }
            }
        }
    }
}
