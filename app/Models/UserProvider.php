<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class UserProvider
 *
 * @package App
 * @property integer $id
 * @property integer $user_id
 * @property string $provider
 * @property string $provider_id
 * @property string $avatar
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\Models\UserProvider whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\UserProvider whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\UserProvider whereProvider($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\UserProvider whereProviderId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\UserProvider whereAvatar($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\UserProvider whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\UserProvider whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class UserProvider extends Model {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'user_providers';

	/**
	 * The attributes that are not mass assignable.
	 *
	 * @var array
	 */
	protected $guarded = ['id'];
}
