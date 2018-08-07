<?php namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Permission
 *
 * @package App
 * @property integer $id
 * @property string $name
 * @property string $display_name
 * @property boolean $system
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Role[] $roles
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\User[] $users
 * @property-read mixed $system_label
 * @property-read mixed $edit_button
 * @property-read mixed $delete_button
 * @property-read mixed $action_buttons
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Permission whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Permission whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Permission whereDisplayName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Permission whereSystem($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Permission whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Permission whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Permission extends Model {
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table;

	/**
	 *
	 */
	public function __construct()
	{
		$this->table = config('access.permissions_table');
	}

	/**
	 * Many-to-Many relations with Roles.
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
	 */
	public function roles()
	{
		return $this->belongsToMany(config('access.role'), config('access.permission_role_table'), 'permission_id', 'role_id');
	}

	/**
	 * Many-to-Many relations with Users.
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
	 */
	public function users()
	{
		return $this->belongsToMany(config('auth.model'), config('access.permission_user_table'), 'permission_id', 'user_id');
	}

	/**
	 * @return string
	 */
	public function getSystemLabelAttribute() {
		switch($this->system) {
			case 0:
				return '<span class="label label-success">No</span>';

			case 1:
				return '<span class="label label-danger">Yes</span>';
		}
	}

	/**
	 * @return bool
	 */
	public function isSystem() {
		return $this->system == 1 ? true : false;
	}

	/**
	 * @return string
	 */
	public function getEditButtonAttribute() {
		return '<a href="'.route('admin.access.roles.permissions.edit', $this->id).'" class="btn btn-xs btn-primary"><i class="fa fa-pencil" data-toggle="tooltip" data-placement="top" title="Edit"></i></a>';
	}

	/**
	 * @return string
	 */
	public function getDeleteButtonAttribute() {
		return '<a href="'.route('admin.access.roles.permissions.destroy', $this->id).'" class="btn btn-xs btn-danger" data-method="delete"><i class="fa fa-times" data-toggle="tooltip" data-placement="top" title="Delete"></i></a>';
	}

	/**
	 * @return string
	 */
	public function getActionButtonsAttribute() {
		$buttons = '';
		$buttons .= $this->getEditButtonAttribute();

		//If the permission is not a system item it can be deleted
		if (! $this->isSystem()) {
			$buttons .= ' '.$this->getDeleteButtonAttribute();
		}

		return $buttons;
	}

	/**
	 * Before delete all constrained foreign relations.
	 *
	 * @return bool
	 */
	public function beforeDelete()
	{
		DB::table(config('access.permission_role_table'))->where('permission_id', $this->id)->delete();
	}
}
