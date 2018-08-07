<?php
/**
 * Created by PhpStorm.
 * User: hocvt
 * Date: 11/18/15
 * Time: 08:04
 */

namespace App\Models;


use App\Core\MyStorage;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\ExternalSource
 *
 * @property integer $id
 * @property integer $course_content_id
 * @property integer $user_id
 * @property string $title
 * @property string $source_type
 * @property string $content
 * @property string $data_disk
 * @property string $data_path
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \App\Models\User $user
 * @property-read \App\Models\CourseContent $course_content
 * @method static \Illuminate\Database\Query\Builder|\App\Models\ExternalSource whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\ExternalSource whereCourseContentId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\ExternalSource whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\ExternalSource whereTitle($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\ExternalSource whereSourceType($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\ExternalSource whereContent($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\ExternalSource whereDataDisk($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\ExternalSource whereDataPath($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\ExternalSource whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\ExternalSource whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ExternalSource extends Model{

    protected $table = 'external_sources';

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function course_content(){
        return $this->belongsTo(CourseContent::class, 'course_content_id');
    }

    public function delete(){
        $return = parent::delete();
        if($return && $this->data_path){
            try{
                MyStorage::getDisk($this->data_disk)->delete($this->data_path);
            }catch (\Exception $ex){
                \Log::error($ex->getMessage());
            }
        }
        return $return;
    }

}