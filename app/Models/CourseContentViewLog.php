<?php
/**
 * Created by PhpStorm.
 * User: hocvt
 * Date: 11/11/15
 * Time: 14:43
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\CourseContentViewLog
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $course_content_id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $token
 * @property integer $status
 * @property-read \App\Models\CourseContent $course_content
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Query\Builder|\App\Models\CourseContentViewLog whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\CourseContentViewLog whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\CourseContentViewLog whereCourseContentId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\CourseContentViewLog whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\CourseContentViewLog whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\CourseContentViewLog whereToken($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\CourseContentViewLog whereStatus($value)
 * @mixin \Eloquent
 */
class CourseContentViewLog extends Model
{
    protected $table = 'course_content_view_logs';

    public function course_content(){
        return $this->belongsTo(CourseContent::class, 'course_content_id');
    }

    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }

}