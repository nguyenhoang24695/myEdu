<?php

namespace App\Http\Controllers\Backend;

use App\Models\Course;
use App\Models\CourseCod;
use App\Models\User;
use App\Repositories\Backend\Role\RoleRepositoryContract;
use App\Repositories\Frontend\User\UserContract;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class CodController extends Controller
{
    protected $user;
    protected $role;
    public function __construct(UserContract $user, RoleRepositoryContract $role)
    {
        $this->user = $user;
        $this->role = $role;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $listings = CourseCod::orderBy('id','DESC')->paginate(20);
        return view('backend.course_cod.index',compact('listings'));
    }

    /**
     * Active
     *
     * @return \Illuminate\Http\Response
     */
    public function active($id){
        $contact = CourseCod::find($id);
        if($contact){

            if($contact->active == 1 || $contact->code != ''){
                return redirect()->back()->withFlashDanger('Thông tin đăng ký đã được kích hoạt');
            }

            //Tạo mã code;
            $code = generateCodeCod();

            //Kiểm tra xem thông tin đk đã là user trên hệ thống hay chưa
            $user = User::where('email', '=', $contact->contact_email)->first();
            if($user){
                $customer_id = $user->id;
            } else {

                //Tạo mới user
                $user = User::create([
                    'name'      => $contact->contact_name,
                    'full_name' => $contact->contact_name,
                    'address'   => $contact->contact_address,
                    'email'     => $contact->contact_email,
                    'password'  => bcrypt($code),
                    'confirmed' => 1,
                    'confirmation_code' => md5(uniqid(mt_rand(), true))
                ]);

                $roles = $this->role->getDefaultUserRole();
                foreach($roles as $role){
                    $user->attachRole($role);
                }

                if($user){
                    $customer_id = $user->id;
                } else {
                    $customer_id = 0;
                }

            }

            /////
            if($customer_id > 0){
                $contact->code      = $code;
                $contact->active    = 1;
                $contact->user_id   = $customer_id;
                $contact->active_by = auth()->user()->id;
                $contact->save();

                $course     = Course::find($contact->course_id);
                $teacher    = 'Phan Văn Sơn';
                try {

                    //Gửi email
                    \Mail::send('emails.cod_active', ['contact' => $contact,'course' => $course, 'teacher' => $teacher], function ($message) use ($contact) {
                        $cus_name = 'Thông tin khóa học và mã kích hoạt';
                        $message->to('myedu@mywork.com.vn', $cus_name)->subject('Thông tin khóa học và mã kích hoạt');
                    });

                    if (count(\Mail::failures()) > 0) {
                        \Log::error('Có một vấn đề gửi thư xác nhận e-mail kích hoạt');
                    }

                } catch(\Exception $exception){
                    \Log::error($exception);
                }

                return redirect()->back()->withFlashSuccess('Tạo mã Code thành công');
            } else {
                return redirect()->back()->withFlashDanger('Lỗi không tìm thấy thông tin user');
            }

        } else {
            return redirect()->back()->withFlashDanger('Lỗi không tìm thấy thông tin đăng ký');
        }
    }

    public function activeCOD($id){
        $contact = CourseCod::find($id);
        if($contact){
            if($contact->active == 1 || $contact->code != ''){
                return redirect()->back()->withFlashDanger('Thông tin đăng ký đã được kích hoạt');
            }
            //Tạo mã code;
            $code = generateCodeCod();
            $contact->code      = $code;
            $contact->active    = 0;
            $contact->active_by = auth()->user()->id;
            $contact->save();
                return redirect()->back()->withFlashSuccess('Tạo mã Code thành công');
        }
        else
        {
            return redirect()->back()->withFlashDanger('Lỗi không tìm thấy thông tin order');
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
        $partner = CourseCod::find($id);
        $partner->delete();
        return redirect()->back()->withFlashSuccess('Xóa bản ghi thành công');
    }
}
