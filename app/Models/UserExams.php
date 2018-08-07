<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserExams extends Model
{
    protected $table = 'user_exams_results';

    protected $fillable = ['user_id', 'course_id', 'exam_name', 'score', 'time_practice'];

    protected $primaryKey = 'id';

    public function course(){
        return $this->belongsTo('App\Models\Course','course_id');
    }
}
