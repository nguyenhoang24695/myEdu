<?php
/**
 * Created by PhpStorm.
 * User: MyPC
 * Date: 15/06/2016
 * Time: 3:17 CH
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $table    = 'questions';

    protected $guarded  = ["id"];

    public $timestamps  = true;

    public function answer(){
        return $this->hasMany(Answer::class,'question_id')->orderBy('order','ASC');
    }
}