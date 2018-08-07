<?php namespace App\Models;

use App\Core\Money\Contracts\WalletContract;
use App\Models\Traits\CustomAsDateTimeFunction;
use Elasticquent\ElasticquentTrait;
use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use App\Services\Access\Traits\UserHasRole;
use App\Core\MyStorage;

/**
 * Class User
 *
 * @package App
 * @property integer $id
 * @property string $name
 * @property string $email
 * @property string $password
 * @property boolean $status
 * @property string $confirmation_code
 * @property boolean $confirmed
 * @property string $remember_token
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property \Carbon\Carbon $deleted_at
 * @property string $unit_name
 * @property integer $grade
 * @property string $avatar_disk
 * @property string $avatar_path
 * @property boolean $gender
 * @property string $birthday
 * @property string $position Địa chỉ trường học
 * @property string $achievement Tên lớp học
 * @property string $full_name Họ tên đầy đủ
 * @property string $address
 * @property string $status_text
 * @property string $user_type
 * @property string $social_facebook
 * @property string $social_google
 * @property string $social_twitter
 * @property string $social_linkedin
 * @property string $cover_path
 * @property integer $idvg_id
 * @property integer $facebook_id
 * @property string $phone
 * @property integer $become_teacher
 * @property integer $primary_wallet
 * @property integer $secondary_wallet
 * @property string $wallet_type
 * @property string $wallet_payment
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\UserProvider[] $providers
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Blog[] $blog
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Course[] $course
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Notification[] $notifications
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\NotificationSetting[] $notificationSetting
 * @property-read mixed $confirmed_label
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\CourseStudent[] $courseStudents
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Role[] $roles
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Permission[] $permissions
 * @property-read mixed $edit_button
 * @property-read mixed $change_password_button
 * @property-read mixed $status_button
 * @property-read mixed $confirmed_button
 * @property-read mixed $delete_button
 * @property-read mixed $action_buttons
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User whereEmail($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User wherePassword($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User whereStatus($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User whereConfirmationCode($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User whereConfirmed($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User whereRememberToken($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User whereDeletedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User whereUnitName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User whereGrade($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User whereAvatarDisk($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User whereAvatarPath($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User whereGender($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User whereBirthday($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User wherePosition($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User whereAchievement($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User whereFullName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User whereAddress($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User whereStatusText($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User whereUserType($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User whereSocialFacebook($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User whereSocialGoogle($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User whereSocialTwitter($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User whereSocialLinkedin($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User whereCoverPath($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User whereIdvgId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User whereFacebookId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User wherePhone($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User whereBecomeTeacher($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User wherePrimaryWallet($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User whereSecondaryWallet($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User whereWalletType($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User whereWalletPayment($value)
 * @mixin \Eloquent
 */
class User extends Model implements AuthenticatableContract, CanResetPasswordContract, WalletContract {

	use Authenticatable, CanResetPassword, SoftDeletes, UserHasRole, ElasticquentTrait, CustomAsDateTimeFunction;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'users';

	/**
	 * The attributes that are not mass assignable.
	 *
	 * @var array
	 */
	protected $guarded = ['id'];

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = ['password', 'remember_token'];

	/**
	 * For soft deletes
	 *
	 * @var array
	 */
	protected $dates = ['deleted_at'];//, 'birthday', 'created_at', 'deleted_at'];
	public $timestamps = true;

	protected $mappingProperties = [
		'name' => [
			'type' => 'string',
			'analyzer' => 'standard'
		],
		'slug' => [
			'type' => 'string',
		],
		'full_name' => [
			'type' => 'string',
		],
		'email' => [
			'type' => 'string',
		],
		'unit_name' => [
			'type' => 'string',
		],
		'gender' => [
			'type' => 'integer',
		],
//		'birthday' => [
//			'type' => 'date',
//			'format' => 'yyyy-MM-dd',
//		],
//		'created_at' => [
//			'type' => 'date',
//			'format' => 'basic_date_time_no_millis',
//		],
//		'updated_at' => [
//			'type' => 'date',
//			'format' => 'basic_date_time_no_millis',
//		]
	];

	function getIndexDocumentData()
	{
		return array(
			'id'      => $this->id,
			'name'   => $this->name,
			'slug'   => str_slug($this->name,'-'),
			'full_name'   => $this->full_name,
			'email'  => $this->email,
			'unit_name'  => $this->unit_name,
			'gender'  => $this->gender,
//			'birthday'  => $this->birthday,
			'created_at'  => $this->created_at,
			'updated_at'  => $this->updated_at,

		);
	}

	/**
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function providers() {
		return $this->hasMany('App\Models\UserProvider');
	}

	public function blog(){
        return $this->hasMany(Blog::class,'blo_user_id');
    }

    public function course(){
        return $this->hasMany(Course::class,'cou_user_id');
    }

    public function notifications()
	{
	    return $this->hasMany(Notification::class);
	}

	public function notificationSetting()
	{
		return $this->hasMany(NotificationSetting::class);
	}

	public function newNotification()
	{
		$notification = new Notification();
		$notification->user()->associate($this);
		return $notification;
	}

	/**
	 * Hash the users password
	 *
	 * @param $value
	 */
	public function setPasswordAttribute($value)
	{
		if (\Hash::needsRehash($value))
			$this->attributes['password'] = bcrypt($value);
		else
			$this->attributes['password'] = $value;
	}

	/**
	 * @return mixed
	 */
	public function canChangeEmail() {
		return config('access.users.change_email');
	}

	/**
	 * @return string
	 */
	public function getConfirmedLabelAttribute() {
		if ($this->confirmed == 1)
			return "<label class='label label-success'>Yes</label>";
		return "<label class='label label-danger'>No</label>";
	}

	public function showAvatar($temp = 'ua_small'){
		if($this->avatar_path != ''){
			return MyStorage::get_image_link($this->avatar_disk,$this->avatar_path,$temp);
		}else{
			return $this->showDefaultAvatar($temp);
		}
	}

	public function showCover(){
		return MyStorage::get_image_link($this->avatar_disk,$this->cover_path,'uc_medium');
	}

	public function showLinkProfile(){
//	    var_dump(route('profile.show',['id' => $this->id,'title' => str_slug($this->name,'-')]));
//        dd(1);
        return route('profile.show',['id' => $this->id,'title' => str_slug($this->name,'-')]);
//		return route('profile.show',['id' => $this->id,'title' => str_slug($this->name,'-')]);

	}

	public function showDefaultAvatar($temp = "ua_small"){
		return MyStorage::get_default_image('user.png');
		//return url('/images/default/user.png');
	}

	public function courseStudents(){
		return $this->hasMany('App\Models\CourseStudent', 'user_id');
	}

	public function registerCourse(Course $course, Order $order = null){

		/** @var CourseStudent $course_student */
		$course_student = new CourseStudent();
		$order_id = $order == null ? 0 : $order->id;
		$course_student->fill(['user_id' => $this->id, 'course_id' => $course->id, 'order_id' => $order_id]);
		return $course_student->save();
	}

	public static function registerCourseCod($user_id, $course_id, $cod_id){

		/** @var CourseStudent $course_student */
		$course_student = new CourseStudent();
		if(!CourseStudent::where('course_id',$course_id)->where('user_id',$user_id)->first()){
			$course_student->fill(['user_id' => $user_id, 'course_id' => $course_id, 'cod_id' => $cod_id]);
			return $course_student->save();
		}
	}

	public function leaveCourse(Course $course){

		$course_student = CourseStudent::whereUserId($this->id)->whereCourseId($course->id)->first();

		if($course_student){
			return $course_student->delete();
		}
		return true;
	}

	public function walletType()
	{
		return 'user';
	}

	public function primaryAmount($format = 'int', $append = '')
	{
		if($format == 'int')
			return $this->primary_wallet;
		if($format == 'view'){
			return number_format($this->primary_wallet, 0, '.', ',') . $append;
		}
	}

	public function primaryIncome($amount)
	{
		$new_value = $this->primary_wallet + $amount;
		$this->increment('primary_wallet', $amount);
		return $new_value;
	}

	public function primaryOutcome($amount)
	{
		$new_value = $this->primary_wallet - $amount;
		$this->decrement('primary_wallet', $amount);
		return $new_value;
	}

	public function secondaryAmount($format = 'int', $append = '')
	{
		if($format == 'int')
			return $this->secondary_wallet;
		if($format == 'view'){
			return number_format($this->secondary_wallet, 0, '.', ',') . $append;
		}
	}

	public function secondaryIncome($amount)
	{
		$new_value = $this->secondary_wallet + $amount;
		$this->increment('secondary_wallet', $amount);
		return $new_value;
	}

	public function secondaryOutcome($amount)
	{
		$new_value = $this->secondary_wallet - $amount;
		$this->decrement('secondary_wallet', $amount);
		return $new_value;
	}


}
