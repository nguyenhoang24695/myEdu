<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Course;
use App\Models\CourseCod;
use App\Models\User;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class CodController extends Controller
{
    public function __construct()
    {

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('frontend.cod.active');
    }

    /**
     * Active khóa học
     *
     * @return \Illuminate\Http\Response
     */
    public function active(Request $request){
        $cod_code = CourseCod::where('code',$request->get('code'))->first();

        //Danh sách khóa học được thưởng sau khi mua khóa học đầu tiên
        $arr_meed = [26,22,19,17,14,16];

        if($cod_code && $cod_code->active == 1){

            $user   = User::find($cod_code->user_id);
            $course = Course::find($cod_code->course_id);
            $user->registerCourseCod($course, $cod_code);
            foreach($arr_meed as $meed){
                $course_meed = Course::find($meed);
                if($course_meed){
                    $user->registerCourseCod($course_meed, $cod_code);
                }
            }
            auth()->login($user,true);

            return view('frontend.cod.success',compact('user','course'));
        } else {
            return redirect()->back()->withFlashDanger("Code đã được kích hoạt");
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
        $this->validate($request,[
            'contact_name'      => 'required',
            'contact_email'     => 'required|email',
            'contact_phone'     => 'required',
            'contact_district'  => 'required',
            'contact_city'      => 'required',
            'contact_address'   => 'required'
        ],[
            'contact_name.required'     => 'Vui lòng nhập tên',
            'contact_email.required'    => 'Vui lòng nhập Email',
            'contact_email.email'       => 'Vui lòng nhập đúng định dạng email',
            'contact_phone.required'    => 'Vui lòng nhập số điện thoại',
            'contact_district.required' => 'Vui lòng nhập quận/huyện',
            'contact_city.required'     => 'Vui lòng nhập thành phố',
            'contact_address.required'  => 'Vui lòng nhập địa chỉ',
        ]);

        $check  =  CourseCod::where('contact_email',$request->get('contact_email'))
            ->orWhere('contact_phone',$request->get('contact_phone'))
            ->where('course_id',$request->get('course_id'))->first();

        if($check){
            return redirect()->route('cod.landing')->withFlashDanger("Thông tin đăng ký đã tồn tại");
        } else {
            $save = CourseCod::create($request->except(['_token']));
            if($save){
                $course     = Course::find($save->course_id);
                $teacher    = 'Phan Văn Sơn';

                try {

                    //Gửi email
                    \Mail::send('emails.cod_request', ['contact' => $save,'course' => $course, 'teacher' => $teacher], function ($message) use ($save) {
                        $cus_name = ($save->contact_name != '') ? $save->contact_name : 'Quý khách';
                        $message->to($save->contact_email, $cus_name)->subject('Thông báo đăng ký tư vấn thành công');
                    });

                    if (count(\Mail::failures()) > 0) {
                        \Log::error('Có một vấn đề gửi thư xác nhận e-mail đăng ký');
                    }

                } catch(\Exception $exception){
                    \Log::error($exception);
                }

                return redirect()->route('cod.landing')->withFlashSuccess("Gửi yêu cầu thành công");
            } else {
                return redirect()->route('cod.landing')->withFlashDanger("Lỗi gửi yêu cầu không thành công");
            }
        }

    }

    public function landing()
    {
        $course_id = 23;
        $time_out  = '2017/01/25';

        javascript()->put([
            'count_down' => $time_out
        ]);

        $num_count = CourseCod::where('course_id',$course_id)->count();
        $course    = Course::find($course_id);

        return view('frontend.cod.landing',compact('course_id','num_count','course'));
    }

    public function email(){
        return view('emails.cod_active');
    }
}
