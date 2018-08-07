<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\Reviews
 *
 * @property integer $id
 * @property integer $rating
 * @property string $rev_content
 * @property integer $rev_cou_id
 * @property integer $rev_user_id
 * @property boolean $rev_active
 * @property boolean $rev_delete
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property \Carbon\Carbon $deleted_at
 * @property-read \App\Models\User $user
 * @property-read \App\Models\Course $course
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Reviews whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Reviews whereRating($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Reviews whereRevContent($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Reviews whereRevCouId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Reviews whereRevUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Reviews whereRevActive($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Reviews whereRevDelete($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Reviews whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Reviews whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Reviews whereDeletedAt($value)
 * @mixin \Eloquent
 */
class Reviews extends Model
{
    use SoftDeletes;

    /**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'reviews';

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
     *
     */
    public $timestamps  = true;

    public function user(){
        return $this->belongsTo('App\Models\User','rev_user_id');
    }

    public function course(){
        return $this->belongsTo('App\Models\Course','rev_cou_id');
    }

    /**
     * @param $avg_rate
     * @return string
     * @deprecated su dung helper de thay the
     */
    public function genRating($avg_rate){
        $star       = '';
        $emty_rate  = 5-$avg_rate;
        for ($i = 0; $i < $avg_rate; $i++){
            $star .= '<i class="fa fa-star on"></i>';
        }
        if ($emty_rate > 0){
            for ($ie = 0; $ie < $emty_rate; $ie++){
                $star .= '<i class="fa fa-star off"></i>';
            }
        }
        return $star;
    }
}
