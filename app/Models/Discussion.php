<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Discussion
 *
 * @property integer $id
 * @property string $title
 * @property string $content
 * @property integer $cou_id
 * @property integer $user_id
 * @property integer $parent_id
 * @property boolean $active
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property \Carbon\Carbon $deleted_at
 * @property integer $content_id
 * @property integer $vote_up
 * @property integer $report
 * @property-read \App\Models\User $user
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Discussion[] $children
 * @property-read \App\Models\CourseContent $coursecontent
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Discussion whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Discussion whereTitle($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Discussion whereContent($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Discussion whereCouId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Discussion whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Discussion whereParentId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Discussion whereActive($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Discussion whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Discussion whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Discussion whereDeletedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Discussion whereContentId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Discussion whereVoteUp($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Discussion whereReport($value)
 * @mixin \Eloquent
 */
class Discussion extends Model
{
    protected $table = "discussions";

    protected $guarded = ["id"];

    protected $dates = ['deleted_at'];

    public $timestamps = true;

    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }

    public function children(){
    	return $this->hasMany(Discussion::class,'parent_id')->with('user');
    }

    public function coursecontent(){
        return $this->belongsTo(CourseContent::class,'content_id');
    }

}
