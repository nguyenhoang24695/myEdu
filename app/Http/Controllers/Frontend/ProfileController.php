<?php namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Repositories\Frontend\Blog\BlogContract;
use App\Repositories\Frontend\Course\CourseContract;
use App\Repositories\Frontend\User\UserContract;
use App\Http\Requests\Frontend\User\UpdateProfileRequest;
use App\Http\Controllers\Api\V1\Resource\ResourceController;
use Artesaos\SEOTools\Facades\SEOMeta;
use Artesaos\SEOTools\Facades\SEOTools;
use Illuminate\Http\Request;
use App\Core\MyStorage;
use Image;
use Input;

/**
 * Class ProfileController
 * @package App\Http\Controllers\Frontend
 */
class ProfileController extends Controller {

	private $user;
	protected $course;
	protected $blog;
	public function __construct(UserContract $user, CourseContract $course, BlogContract $blog)
	{
		$this->user	  =	$user;
		$this->course = $course;
		$this->blog	  = $blog;
	}
	/**
	 * @param $id
	 * @return mixed
	 */
	public function edit($id) {
		return view('frontend.user.profile.edit')
			->withUser(auth()->user($id));
	}

	/**
	 * @param $id
	 * @param UserContract $user
	 * @param UpdateProfileRequest $request
	 * @return mixed
	 */
	public function update($id, UserContract $user, UpdateProfileRequest $request) {
		$user->updateProfile($id, $request->all());
		return redirect()->route('frontend.dashboard')->withFlashSuccess("Profile successfully updated.");
	}

	public function index($id,Request $request){
		$param   = ["status"=>1];
		/** @var User $profile */
		$profile = $this->user->getById($id,$param);
		if(!$profile){
			abort(404);
		}
		
		if($profile->confirmed == false){
			\Session::flash('flash_warning', 'Người dùng chưa xác thực email');
		}

		\SEO::setTitle(trans('meta.profile_title', ['name' => $profile->name]));
		if($profile->status_text != ""){
			\SEO::setDescription($profile->status_text);
		}

		//Lấy danh sách khóa học tạo
		$filter 	 = ['cou_user_id'=>$id,'cou_active'=>1];
		$filter_blog = ['blo_user_id'=>$id,'public'=>['operator' => '!=', 'value' => 0]];
		$pageSize1= config('view.page_size.user.my_blog');
		$pageSize2= config('view.page_size.user.my_course');
		$orderby = ['created_at' => 'DESC'];
		$with	 = "user";
		$loc     =  $request->get('loc');

		$data_blog 	 = $this->blog->getAllWithPaginate($filter_blog,$pageSize1,$orderby,$with);
		$data_course = $this->course->getAllWithPaginate($filter,$pageSize2,$orderby,$with);
		$appended	 = [];
		if($loc  == "blog"){
			$data_page	 = $data_blog;
			$appended	 = ['loc'=>$loc];
		} else {
			$data_page	 = $data_course;
		}

		return view('frontend.user.profile',compact('profile','data_course','loc','data_blog','data_page','appended'));
	}

	public function updateInfoProfile(Request $request,ResourceController $valid){

		\SEO::setTitle(trans('meta.profile_edit_title'));

		$param   	= ["confirmed"=>1,"status"=>1];
		$id 	 	= $request->get('id');
		$profile 	= $this->user->getById($id,$param);
		$avatar_pic = "";
		$cover_pic  = "";

		//Update ảnh cover
		if ($request->hasFile('cover')) {
			$file_uploaded = $request->file('cover');

			//valid upload ảnh
			$valid_result = $valid->validUploadedFile($file_uploaded, 'image');
			if($valid_result['valid'] == false){
                return \Response::json($valid_result['message'],500);
            }

            list($width, $height) = getimagesize($file_uploaded);

            if($width < 1600){
            	return response()->json(['cover' => 'Bạn đang đăng ảnh có kích thước nhỏ hơn kích thước đề nghị (1600x340)'], 500);
            }

            if($height < 340){
            	return response()->json(['cover' => 'Bạn đang đăng ảnh có kích thước nhỏ hơn kích thước đề nghị (1600x340)'], 500);
            }

            $disk 		= MyStorage::getDisk('public');
    		$filename   = time() . '.' . $file_uploaded->getClientOriginalExtension();
    		$full_path  = 'users/'.$filename;

    		$saved = $disk->writeStream($full_path,
            Image::make($request->file('cover'))
                ->resize(2048, 1152, function ($constraint) {
                    $constraint->aspectRatio();
                })
                ->stream()
                ->detach());

    		if($saved){
    			try{
	                $old_disk = MyStorage::getDisk($profile->avatar_disk);
	                if($old_disk && $profile->cover_path != '' &&$old_disk->has($profile->cover_path)){
	                    $old_disk->delete($profile->cover_path);
	                }
	            }catch (FileNotFoundException $e){
	                // do nothing
	            }

	            $saved = $profile->update(['cover_path' => $full_path, 'avatar_disk' => 'public']);
	            if($saved){
	            	$cover_pic = MyStorage::get_image_link('public',$full_path,'uc_medium');
	            }
    		}
		}

		//Cập nhật ảnh đại diện
		if ($request->hasFile('avatar')) {
			$file_uploaded = $request->file('avatar');
	        $valid_result = $valid->validUploadedFile($file_uploaded, 'image');

	        if($valid_result['valid'] == false){
                return \Response::json($valid_result['message'],500);
            }

            $disk 		= MyStorage::getDisk('public');
    		$filename   = time() . '.' . $file_uploaded->getClientOriginalExtension();
    		$full_path  = 'users/'.$filename;

    		$saved = $disk->writeStream($full_path,
        	Image::make($request->file('avatar'))
            ->resize(1080, 1080, function ($constraint) {
                $constraint->aspectRatio();
            })
            ->stream()
            ->detach());

            if($saved){
	            try{
	                $old_disk = MyStorage::getDisk($profile->avatar_disk);
	                if($old_disk && $profile->avatar_path != '' &&$old_disk->has($profile->avatar_path)){
	                    $old_disk->delete($profile->avatar_path);
	                }
	            }catch (FileNotFoundException $e){
	                // do nothing
	            }

	            $saved = $profile->update(['avatar_path' => $full_path, 'avatar_disk' => 'public']);
	            if($saved){
	                $avatar_pic = MyStorage::get_image_link('public',$full_path,'ua_medium');
	            }

    		}
		}

		if ($request->hasFile('cover') || $request->hasFile('avatar')) {
			return \Response::json(['cover'=>$cover_pic,'avatar'=>$avatar_pic]);
		}

		//Cập nhật thông tin cá nhân.
		$update = $profile->update(['name' => $request->get('name'), 'status_text' => $request->get('status_text')]);
		if($update){
			return \Response::json(['name' => $request->get('name'), 'status_text' => $request->get('status_text')]);
		} else {
			return response()->json(['message' => 'Lỗi rồi'], 500);
		}
		
	}
}