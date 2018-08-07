<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Quizzes extends Model implements CourseContentContract
{
    protected $table = 'quizzes';

    protected $guarded  = ["id"];

    public $timestamps  = true;

    public function course_content(){
        return $this->belongsTo('App\Models\CourseContent', 'course_content_id');
    }

    public function question(){
        return $this->hasMany(Question::class,'quizzes_id')->orderBy('order','ASC');
    }

    /**
     * @return mixed|string
     */
    public function get_title()
    {
        if(!$this->exists){
            return '';
        }
        return $this->qui_title;
    }

    /**
     * @return mixed|string
     */
    public function get_sub_title()
    {
        if(!$this->exists){
            return '';
        }
        return $this->qui_sub_title;
    }

    /**
     * @return string
     */
    public function get_type()
    {
        return $this->getMorphClass();
    }

    /**
     * @return Lecture $this
     */
    public function get_content()
    {
        return $this;
    }

    public function delete_content(){
        return $this->delete();
    }
}
