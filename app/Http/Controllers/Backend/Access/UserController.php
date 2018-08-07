<?php namespace App\Http\Controllers\Backend\Access;

use App\Events\Frontend\SendEmailNotificationEvent;
use App\Events\Frontend\SendNotificationEvent;
use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use App\Repositories\Backend\User\UserContract;
use App\Repositories\Backend\Role\RoleRepositoryContract;
use App\Repositories\Backend\Permission\PermissionRepositoryContract;
use App\Http\Requests\Backend\Access\User\CreateUserRequest;
use App\Http\Requests\Backend\Access\User\UpdateUserRequest;
use App\Http\Requests\Backend\Access\User\UpdateUserPasswordRequest;
use App\Repositories\Frontend\Auth\AuthenticationContract;
use Illuminate\Http\Request;
use App\Http\Requests;

/**
 * Class UserController
 */
class UserController extends Controller {

	/**
	 * @var UserContract
	 */
	protected $users;
	/**
	 * @var RoleRepositoryContract
	 */
	protected $roles;

	/**
	 * @var PermissionRepositoryContract
	 */
	protected $permissions;

	/**
	 * @param UserContract $users
	 * @param RoleRepositoryContract $roles
	 * @param PermissionRepositoryContract $permissions
	 */
	public function __construct(
		UserContract $users,
		RoleRepositoryContract $roles,
		PermissionRepositoryContract $permissions) {
		$this->users = $users;
		$this->roles = $roles;
		$this->permissions = $permissions;
	}

	/**
	 * @return mixed
	 */
	public function index(Request $request) {
		if($keyword = $request->query('keyword')){
			$users = User::search($keyword)->paginate(config('access.users.default_per_page'));
		}else{
			$users = $this->users->getUsersPaginated(config('access.users.default_per_page'), 1);
		}

		return view('backend.access.index')
			->withUsers($users);
	}

	public function become_teacher(){
		$users = $this->users->getUsersBecometeacherPaginated(config('access.users.default_per_page'),1);
		return view('backend.access.become-teacher',compact('users'));
	}

	//Trường hợp được duyệt
	public function activeTeacher($user_id){
		$user 	=	$this->users->findOrThrowException($user_id, true);
		$role	=	Role::where('name', "Teacher")->first();
		$user->attachRole($role);

		//Gửi tin nhắn
		$obj_related		=  0;
		$obj_sender         =  $this->users->findOrThrowException(config('notification.obj_send.id'));
		$obj_user			=  $user;
		$data['type']       =  "message";
		$data['subject']    =  "Xin chúc mừng bạn đã trở thành giảng viên tại Quochoc.vn";
		$tem_type           =  config('notification.template.user.register_teacher.active.key');
		$data['body']       =  view('emails.notification.template',compact('tem_type'))->render();
		$data['bodyMail']   =  view('emails.notification.email',compact('tem_type','obj_user'))->render();
		$data               =  json_decode(json_encode ($data), FALSE);
		event(new SendNotificationEvent($user,$obj_sender,$obj_related,$data));
		event(new SendEmailNotificationEvent($obj_user,$data));

		return redirect()->route('admin.access.users.index')->withFlashSuccess('Duyệt thông tin thành công.');
	}

	//Trường hợp không được duyệt
	public function deActiveTeacher($user_id,Request $request){
		$reason	=	$request->get('message');
		$user 	= 	$this->users->findOrThrowException($user_id, true);
		$user->become_teacher = 0;
		if($user->save()){

			//Gửi tin nhắn
			$obj_related		=  0;
			$obj_sender         =  $this->users->findOrThrowException(config('notification.obj_send.id'));
			$obj_user			=  $user;
			$data['type']       =  "message";
			$data['subject']    =  "Chúng tôi rất tiếc khi thông báo yêu cầu trở thành giảng viên tại Quochoc.vn không được duyệt";
			$tem_type           =  config('notification.template.user.register_teacher.deactive.key');
			$data['body']       =  view('emails.notification.template',compact('tem_type', 'reason'))->render();
			$data['bodyMail']   =  view('emails.notification.email',compact('tem_type', 'obj_user', 'reason'))->render();
			$data               =  json_decode(json_encode ($data), FALSE);
			event(new SendNotificationEvent($user,$obj_sender,$obj_related,$data));
			event(new SendEmailNotificationEvent($obj_user,$data));

			return redirect()->route('admin.access.users.index')->withFlashSuccess('Gửi thông tin thành công.');
		}
	}

	//Trường hợp là giáo viên rùi, admin muốn hủy
	public function removeTeacher($user_id,Request $request){
		$reason	=	$request->get('message');
		$user 	=	$this->users->findOrThrowException($user_id, true);
		$user->become_teacher = 0;
		if($user->save()){
			$role	=	Role::where('name', "Teacher")->first();
			$user->detachRole($role);

			//Gửi tin nhắn
			$obj_related		=  0;
			$obj_sender         =  $this->users->findOrThrowException(config('notification.obj_send.id'));
			$obj_user			=  $user;
			$data['type']       =  "message";
			$data['subject']    =  "Chúng tôi rất tiếc khi thông báo chức danh giáo viên trên Quochoc của bạn đã bị hủy.";
			$tem_type           =  config('notification.template.user.register_teacher.delete.key');
			$data['body']       =  view('emails.notification.template',compact('tem_type', 'reason'))->render();
			$data['bodyMail']   =  view('emails.notification.email',compact('tem_type', 'obj_user', 'reason'))->render();
			$data               =  json_decode(json_encode ($data), FALSE);
			event(new SendNotificationEvent($user,$obj_sender,$obj_related,$data));
			event(new SendEmailNotificationEvent($obj_user,$data));

			return redirect()->route('admin.access.users.index')->withFlashSuccess('Hủy bỏ thành công.');
		}
	}

	/**
	 * @return mixed
	 */
	public function create() {
		return view('backend.access.create')
			->withRoles($this->roles->getAllRoles('id', 'asc', true))
			->withPermissions($this->permissions->getPermissionsNotAssociatedWithRole());
	}

	/**
	 * @param CreateUserRequest $request
	 * @return mixed
	 */
	public function store(CreateUserRequest $request) {
		$this->users->create(
			$request->except('assignees_roles', 'permission_user'),
			$request->only('assignees_roles'),
			$request->only('permission_user')
		);
		return redirect()->route('admin.access.users.index')->withFlashSuccess('The user was successfully created.');
	}

	/**
	 * @param $id
	 * @return mixed
	 */
	public function edit($id) {
		$user = $this->users->findOrThrowException($id, true);
		return view('backend.access.edit')
			->withUser($user)
			->withUserRoles($user->roles->lists('id')->all())
			->withRoles($this->roles->getAllRoles('id', 'asc', true))
			->withUserPermissions($user->permissions->lists('id')->all())
			->withPermissions($this->permissions->getPermissionsNotAssociatedWithRole());
	}

	/**
	 * @param $id
	 * @param UpdateUserRequest $request
	 * @return mixed
	 */
	public function update($id, UpdateUserRequest $request) {
		$this->users->update($id,
			$request->except('assignees_roles', 'permission_user'),
			$request->only('assignees_roles'),
			$request->only('permission_user')
		);
		return redirect()->route('admin.access.users.index')->withFlashSuccess('The user was successfully updated.');
	}

	/**
	 * @param $id
	 * @return mixed
	 */
	public function destroy($id) {
		$this->users->destroy($id);
		return redirect()->back()->withFlashSuccess('The user was successfully deleted.');
	}

	/**
	 * @param $id
	 * @return mixed
	 */
	public function delete($id) {
		$this->users->delete($id);
		return redirect()->back()->withFlashSuccess('The user was deleted permanently.');
	}

	/**
	 * @param $id
	 * @return mixed
	 */
	public function restore($id) {
		$this->users->restore($id);
		return redirect()->back()->withFlashSuccess('The user was successfully restored.');
	}

	/**
	 * @param $id
	 * @param $status
	 * @return mixed
	 */
	public function mark($id, $status) {
		$this->users->mark($id, $status);
		return redirect()->back()->withFlashSuccess('The user was successfully updated.');
	}

	/**
	 * @return mixed
	 */
	public function deactivated() {
		return view('backend.access.deactivated')
			->withUsers($this->users->getUsersPaginated(25, 0));
	}

	/**
	 * @return mixed
	 */
	public function deleted() {
		return view('backend.access.deleted')
			->withUsers($this->users->getDeletedUsersPaginated(25));
	}

	/**
	 * @return mixed
	 */
	public function banned() {
		return view('backend.access.banned')
			->withUsers($this->users->getUsersPaginated(25, 2));
	}

	/**
	 * @param $id
	 * @return mixed
	 */
	public function changePassword($id) {
		return view('backend.access.change-password')
			->withUser($this->users->findOrThrowException($id));
	}

	/**
	 * @param $id
	 * @param UpdateUserPasswordRequest $request
	 * @return mixed
	 */
	public function updatePassword($id, UpdateUserPasswordRequest $request) {
		$this->users->updatePassword($id, $request->all());
		return redirect()->route('admin.access.users.index')->withFlashSuccess("The user's password was successfully updated.");
	}

	/**
	 * @param $user_id
	 * @param AuthenticationContract $auth
	 * @return mixed
	 */
	public function resendConfirmationEmail($user_id, AuthenticationContract $auth) {
		$auth->resendConfirmationEmail($user_id);
		return redirect()->back()->withFlashSuccess("A new confirmation e-mail has been sent to the address on file.");
	}
}