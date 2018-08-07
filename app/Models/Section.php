<?php
/**
 * Created by PhpStorm.
 * User: hocvt
 * Date: 9/24/15
 * Time: 18:00
 */

namespace App\Models;



use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

/**
 * App\Models\Section
 *
 * @property integer $id
 * @property string $sec_title
 * @property string $sec_sub_title
 * @property integer $sec_cou_id
 * @property boolean $sec_active
 * @property integer $item_order
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $course_content_id
 * @property-read \App\Models\CourseContent $course_content
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Section whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Section whereSecTitle($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Section whereSecSubTitle($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Section whereSecCouId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Section whereSecActive($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Section whereItemOrder($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Section whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Section whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Section whereCourseContentId($value)
 * @mixin \Eloquent
 */
class Section extends Model implements CourseContentContract
{

    protected $table = 'sections';

    protected $guarded = ['id'];

    //protected $forward_actions = [];

    /**
     * Section constructor.
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

//        $this->forward_actions = [
//            'add' => \App\Http\Controllers\Api\V1\Resource\SectionController::class . '@addSection',
//            'update' => \App\Http\Controllers\Api\V1\Resource\SectionController::class . '@updateSection',
//            'delete' => \App\Http\Controllers\Api\V1\Resource\SectionController::class . '@deleteSection',
//        ];
    }


    public function course_content(){
        return $this->belongsTo('App\Models\CourseContent', 'course_content_id');
    }

    public function get_title()
    {
        if(!$this->exists){
            return '';
        }
        return $this->sec_title;
    }

    public function get_sub_title()
    {
        if(!$this->exists){
            return '';
        }
        return $this->sec_sub_title;
    }

    public function get_type()
    {
        return $this->getMorphClass();
    }

    public function get_content()
    {
        return $this;
    }

    public function delete_content(){
        return $this->delete();
    }

//    public function update_content(CourseContentContract $content)
//    {
//        if($this->get_type() != $content->get_type()) return false;
//        $this->sec_title = $content->sec_title;
//        $this->sec_sub_title = $content->sec_title;
//        return true;
//    }

}