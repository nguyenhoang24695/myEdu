<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CourseCod extends Model
{
    use SoftDeletes;

    protected $table    = 'course_cods';

    protected $guarded  = ["id"];

    protected $dates    = ['deleted_at'];

    public $timestamps  = true;

    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }

    public function course(){
        return $this->belongsTo(Course::class,'course_id');
    }
}
