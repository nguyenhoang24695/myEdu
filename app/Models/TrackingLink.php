<?php
/**
 * Created by PhpStorm.
 * User: MyPC
 * Date: 25/04/2016
 * Time: 10:50 SA
 */

namespace App\Models;


use App\Models\Traits\UuidForKey;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TrackingLink extends Model
{
    use SoftDeletes;
    use UuidForKey;

    protected $table      = "tracking_links";
    protected $dates      = ['deleted_at'];
    protected $guarded    = [];

    public $timestamps    = true;
    public $incrementing  = false;

    public function course(){
        return $this->belongsTo(Course::class,'course_id');
    }
}