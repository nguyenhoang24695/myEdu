<?php namespace App\Repositories\Frontend\User;

use App\Models\User;
use App\UserProvider;
use App\Core\BaseRepository;
use App\Exceptions\GeneralException;
use App\Repositories\Backend\Role\RoleRepositoryContract;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;

/**
 * Class EloquentUserRepository
 * @package App\Repositories\User
 */
class EloquentUserRepository extends BaseRepository implements UserContract {

	/**
	 * @var RoleRepositoryContract
	 */
	protected $role;
	protected $model;

	/**
	 * @param RoleRepositoryContract $role
	 */
	public function __construct(RoleRepositoryContract $role, User $model) {
		$this->role  = $role;
		$this->model = $model;
	}

	/**
	 * @param $id
	 * @return \Illuminate\Support\Collection|null|static
	 * @throws GeneralException
	 */
	public function findOrThrowException($id) {
		$user = User::find($id);
		if (! is_null($user)) return $user;
		throw new GeneralException('That user does not exist.');
	}

	/**
	 * @param $data
	 * @param bool $provider
	 * @return static
	 */
	public function create($data, $provider = false, $type_obj = false) {

		if($type_obj){
			$name		=	$data->name;
			$email 		=	$data->email;
			$password	=	isset($data->password) ? $data->password : "";
		} else {
			$name		=	$data['name'];
			$email		=	$data['email'];
			$password	=	$data['password'];
		}

		$user = new User();
		$user = User::create([
			'name' => $name,
			'email' => $email,
			'password' => $provider ? null : $password,
			'confirmation_code' => md5(uniqid(mt_rand(), true)),
//			'confirmed' => config('access.users.confirm_email') && 1,
            'confirmed' => 1,
		]);

		$roles = $this->role->getDefaultUserRole();
		foreach($roles as $role){
			$user->attachRole($role);
		}

//		if (config('access.users.confirm_email') && !$provider)
//
//			$this->sendConfirmationEmail($user);

		return $user;
	}

	/**
	 * @param $data
	 * @param $provider
	 * @return static
	 */
	public function findByUserNameOrCreate($data, $provider) {
		$user = User::where('email', $data->email)->first();
		$providerData = [
			'avatar' => $data->avatar,
			'provider' => $provider,
			'provider_id' => $data->id,
		];

		if(! $user) {
			$user = $this->create([
				'name' => $data->name,
				'email' => $data->email,
			], true);
		}

		if ($this->hasProvider($user, $provider))
			$this->checkIfUserNeedsUpdating($provider, $data, $user);
		else
		{
			$user->providers()->save(new UserProvider($providerData));
		}

		return $user;
	}

	/**
	 * @param $user
	 * @param $provider
	 * @return bool
	 */
	public function hasProvider($user, $provider) {
		foreach ($user->providers as $p) {
			if ($p->provider == $provider)
				return true;
		}

		return false;
	}

	/**
	 * @param $provider
	 * @param $providerData
	 * @param $user
	 */
	public function checkIfUserNeedsUpdating($provider, $providerData, $user) {
		//Have to first check to see if name and email have to be updated
		$userData = [
			'email' => $providerData->email,
			'name' => $providerData->name,
		];
		$dbData = [
			'email' => $user->email,
			'name' => $user->name,
		];
		$differences = array_diff($userData, $dbData);
		if (! empty($differences)) {
			$user->email = $providerData->email;
			$user->name = $providerData->name;
			$user->save();
		}

		//Then have to check to see if avatar for specific provider has changed
		$p = $user->providers()->where('provider', $provider)->first();
		if ($p->avatar != $providerData->avatar) {
			$p->avatar = $providerData->avatar;
			$p->save();
		}
	}

	/**
	 * @param $id
	 * @param $input
	 * @return mixed
	 * @throws GeneralException
	 */
	public function updateProfile($id, $input) {
		$user = $this->findOrThrowException($id);
		$user->name = $input['name'];

		if ($user->canChangeEmail()) {
			//Address is not current address
			if ($user->email != $input['email'])
			{
				//Emails have to be unique
				if (User::where('email', $input['email'])->first())
					throw new GeneralException("That e-mail address is already taken.");

				$user->email = $input['email'];
			}
		}

		return $user->save();
	}

	/**
	 * @param $input
	 * @return mixed
	 * @throws GeneralException
	 */
	public function changePassword($input) {
		$user = $this->findOrThrowException(auth()->id());

		if (Hash::check($input['old_password'], $user->password)) {
			//Passwords are hashed on the model
			$user->password = $input['password'];
			return $user->save();
		}

		throw new GeneralException("That is not your old password.");
	}

	/**
	 * @param $token
	 * @throws GeneralException
	 */
	public function confirmAccount($token) {
		$user = User::where('confirmation_code', $token)->first();

		if ($user) {
			if ($user->confirmed == 1)
				throw new GeneralException("Your account is already confirmed.");

			if ($user->confirmation_code == $token) {
				$user->confirmed = 1;
				$user->save();
				return $user;
			}

			throw new GeneralException("Your confirmation code does not match.");
		}

		throw new GeneralException("That confirmation code does not exist.");
	}

	/**
	 * @param $user
	 * @return mixed
	 */
	public function sendConfirmationEmail($user) {
		//$user can be user instance or id
		if (! $user instanceof User)
			$user = User::findOrFail($user);

		return Mail::send('emails.confirm', ['token' => $user->confirmation_code], function($message) use ($user)
		{

			$message->to($user->email, $user->name)->subject(app_name().': Xác nhận tài khoản của bạn!');
		});
	}

	/**
	 * @param $user
	 * @return mixed
	 */

	public function UpdateInfo($id, $input){
		$user = $this->findOrThrowException($id);
		return $user->update($input);
	}

	/**
	 * @param $user
	 * @return mixed
	 */

	public function UpdateAvatar($id, $file_name){
		$user = $this->findOrThrowException($id);
		$user->avatar_path = $file_name;
		return $user->save();
	}

	/**
	 * @param $user
	 * @return mixed
	 */

	public function UpdateCover($id, $file_name){
		$user = $this->findOrThrowException($id);
		$user->cover_path = $file_name;
		return $user->save();
	}
}