<?php

namespace App\Models;

use App\Core\MyStorage;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\Blog
 *
 * @property integer $id
 * @property string $blo_title
 * @property string $blo_summary
 * @property integer $blo_user_id
 * @property integer $blo_views
 * @property integer $blo_blc_id
 * @property boolean $blo_active
 * @property boolean $blo_delete
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property \Carbon\Carbon $deleted_at
 * @property string $blo_content
 * @property string $blo_disk
 * @property string $blo_path
 * @property integer $blo_cate
 * @property boolean $hot
 * @property boolean $public
 * @property-read \App\Models\User $user
 * @property-read \App\Models\BlogCategories $category
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Blog whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Blog whereBloTitle($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Blog whereBloSummary($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Blog whereBloUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Blog whereBloViews($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Blog whereBloBlcId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Blog whereBloActive($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Blog whereBloDelete($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Blog whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Blog whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Blog whereDeletedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Blog whereBloContent($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Blog whereBloDisk($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Blog whereBloPath($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Blog whereBloCate($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Blog whereHot($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Blog wherePublic($value)
 * @mixin \Eloquent
 */
class Blog extends Model
{
	use SoftDeletes;

    /**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'blogs';

	/**
	 * The attributes that are not mass assignable.
	 *
	 * @var array
	 */
	protected $guarded = ['id'];

	/**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

	/**
	 * @var bool
	 */
    public $timestamps  = true;


    public function user(){
        return $this->belongsTo(User::class,'blo_user_id');
    }

    public function category(){
        return $this->belongsTo('App\Models\BlogCategories','blo_cate');
    }

    public function getAvatar($template = 'blog_medium'){
        return MyStorage::get_image_blog_link($this->cover_disk, $this->cover_path, $template);
    }

}
