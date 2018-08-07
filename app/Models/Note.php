<?php
/**
 * Created by PhpStorm.
 * User: hocvt
 * Date: 11/9/15
 * Time: 14:00
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Note
 *
 * @property integer $id
 * @property integer $content_id
 * @property integer $user_id
 * @property string $bookmark
 * @property string $content
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \App\Models\User $user
 * @property-read \App\Models\CourseContent $course_content
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Note whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Note whereContentId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Note whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Note whereBookmark($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Note whereContent($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Note whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Note whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Note extends Model
{
    protected $table = 'notes';

    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }

    public function course_content(){
        return $this->belongsTo(CourseContent::class, 'content_id');
    }


}