<?php
/**
 * Created by PhpStorm.
 * User: hocvt
 * Date: 10/21/15
 * Time: 09:35
 */

namespace App\Models;

use App\Events\Frontend\StudentRegisterCourse;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\CourseStudent
 *
 * @property integer $id
 * @property integer $course_id
 * @property integer $user_id
 * @property string $deleted_at
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property integer $order_id
 * @property-read \App\Models\User $user
 * @property-read \App\Models\Course $course
 * @method static \Illuminate\Database\Query\Builder|\App\Models\CourseStudent whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\CourseStudent whereCourseId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\CourseStudent whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\CourseStudent whereDeletedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\CourseStudent whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\CourseStudent whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\CourseStudent whereOrderId($value)
 * @mixin \Eloquent
 */
class CourseStudent extends Model
{
    use SoftDeletes;

    protected $table = "course_students";

    protected $guarded = ["id"];

    protected $fillable = [
        'user_id',
        'course_id',
        'cod_id'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function user(){
        return $this->belongsTo('App\Models\User', 'user_id');
    }

    public function course(){
        return $this->belongsTo('App\Models\Course', 'course_id')->where('cou_active',1);
    }

    public function leave(){
        $this->delete();
    }

    public function save(array $options = [])
    {
        $return = parent::save($options);
        if($return){
            // increment count
            if($this->wasRecentlyCreated){
                $this->course->increment('user_count');
            }
            // fire event
            event(new StudentRegisterCourse($this->user_id, $this->course_id));
        }
        return $return;
    }

    public function delete(){
        $return = parent::delete();
        if($return){
            $this->course->decrement('user_count');
        }
        return $return;
    }



}