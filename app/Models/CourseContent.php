<?php
/**
 * Created by PhpStorm.
 * User: hocvt
 * Date: 9/25/15
 * Time: 08:28
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\CourseContent
 *
 * @property integer $course_id
 * @property string $content_type
 * @property integer $content_id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property integer $id
 * @property integer $content_order
 * @property integer $edit_status
 * @property string $access_privacy
 * @property-read \App\Models\Course $course
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Note[] $notes
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\ExternalSource[] $external_sources
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\CourseContentViewLog[] $view_logs
 * @method static \Illuminate\Database\Query\Builder|\App\Models\CourseContent whereCourseId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\CourseContent whereContentType($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\CourseContent whereContentId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\CourseContent whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\CourseContent whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\CourseContent whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\CourseContent whereContentOrder($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\CourseContent whereEditStatus($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\CourseContent whereAccessPrivacy($value)
 * @mixin \Eloquent
 */
class CourseContent extends Model
{

    protected $table = 'course_contents';

    protected $guarded = ['id'];

    protected $fillable = ['content_id', 'content_order', 'course_id'];

    /** @var CourseContentContract $content */
    private $course_content = null;

    public function course(){
        return $this->belongsTo('App\Models\Course', 'course_id');
    }

    public function notes(){
        return $this->hasMany(Note::class, 'content_id');
    }

    /**
     * Trả về các tài nguyên khác của khóa học như external link, file đính kèm(future feature)
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function external_sources(){
        return $this->hasMany(ExternalSource::class, 'course_content_id');
    }

    /**
     * Kiểm tra xem có tài nguyên đính kèm ko
     * @return bool
     */
    public function hasExternalSource(){
        return $this->external_sources()->exits();
    }

    public function get_title(){
        if($this->exists && $this->getContent()){
            return $this->getContent()->get_title();
        }
        return '';
    }

    public function get_sub_title(){
        if($this->exists && $this->getContent()){
            return $this->getContent()->get_sub_title();
        }
        return '';
    }

    public function get_type(){
        if($this->exists && $this->getContent()){
            return $this->getContent()->get_type();
        }
        return null;
    }



    public function set_content(CourseContentContract $content, $force = false){
        if($this->course_content && $this->content->get_type() != $content->get_type()){
        // tự động đổi force thành true khi class content cũ và mới khác nhau
            $force = true;
        }
        if($force == true){
            // remove old content
            $this->content->delete_content();
            // change to new content
            $this->course_content = $content;
        }elseif($this->course_content == null){
            $this->course_content = $content;
        }else{
            $this->content->update_content($content);
        }
        $type = $content->get_type();
    }

    public function getContent(){

        if($this->exists && $this->course_content == null && $this->content_type != ''){
            $content_class = $this->content_type;
            $content_id = $this->content_id;
            $this->course_content = $content_class::find($content_id);
        }

        return $this->course_content;
    }

    public function save(array $options = []){

        if($this->content_order < 1){
            $this->content_order = 99;
        }

        if($this->getContent() == null) return false;

        $this->content_type = $this->course_content->get_type();

        if($save_this = $this->course_content->save()){

            $this->content_id = $this->course_content->id;
            if(parent::save($options)){
                // update counter
                /** @var Course $course */
                if($this->wasRecentlyCreated){
                    $course = $this->course;
                    if($this->get_type() == config('course.content_types.section')){
                        $course->increment('content_section_count');
                    }elseif($this->get_type() == config('course.content_types.lecture')){
                        $course->increment('content_lecture_count');
                    }elseif($this->get_type() == config('course.content_types.quizzes')){
                        $course->increment('content_quizzes_count');
                    }
                }
                return true;
            }else{
                $this->course_content->delete_content();
                $this->delete();
                return false;
            }
        }else{

            $this->delete();
            return false;

        }
    }

    public function delete()
    {
        $this_type = $this->content_type;
        $course = $this->course;
        $return = parent::delete();

        if($return){
            // delete notes
            $this->notes()->delete();
            // delete logs
            $this->view_logs()->delete();
            // remove external source
            $this->external_sources()->delete();

            // update counter
            /** @var Course $course */
            //if($this->wasRecentlyCreated){
            if($this_type == config('course.content_types.section')){
                $course->decrement('content_section_count');
            }elseif($this_type == config('course.content_types.lecture')){
                $course->decrement('content_lecture_count');
            }elseif($this->get_type() == config('course.content_types.quizzes')){
                $course->decrement('content_quizzes_count');
            }
            //}
        }
        return $return;
    }


    public function to_array(){
        $frontend_type = '';
        foreach(config('course.content_types') as $k => $v){
            if($v == $this->get_type()){
                $frontend_type = $k;
                break;
            }
        }
        return ['id' => $this->id,
            'content_id' => $this->course_content->id,
            'content_type' => $frontend_type,
            'title' => $this->get_title(),
            'access_privacy' => $this->accessPrivacy(),
            'sub_title' => $this->get_sub_title()];
    }

    public function getFrontEndType(){
        foreach(config('course.content_types') as $k => $v){
            if($v == $this->get_type()){
                return $k;
            }
        }
        return '';
    }

    public function editingStatus($status = null){
        if($status == null){
            return $this->edit_status;
        }else{
            $this->edit_status = $status;
        }
    }

    public function accessPrivacy($privacy = null){
        if($privacy == null){
            return $this->access_privacy;
        }else{
            $this->access_privacy = $privacy;
        }
    }




    ////////////////////LOGGING////////////////////
    public function view_logs(){
        return $this->hasMany(CourseContentViewLog::class, 'course_content_id');
    }

}