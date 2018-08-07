<?php
/**
 * Created by PhpStorm.
 * User: MyPC
 * Date: 15/06/2016
 * Time: 3:18 CH
 */

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    protected $table    = 'answers';

    protected $guarded  = ["id"];

    public $timestamps  = false;

    public function question(){
        return $this->belongsTo(Question::class);
    }
}