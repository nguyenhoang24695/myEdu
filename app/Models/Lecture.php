<?php
/**
 * Created by PhpStorm.
 * User: hocvt
 * Date: 9/25/15
 * Time: 08:39
 */

namespace App\Models;


use App\Core\HtmlTools;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

/**
 * App\Models\Lecture
 *
 * @property integer $id
 * @property string $lec_title
 * @property string $lec_sub_title
 * @property integer $lec_sec_id
 * @property integer $lec_content_id
 * @property string $lec_type
 * @property boolean $lec_active
 * @property integer $item_order
 * @property string $lecture_data
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $course_content_id
 * @property string $primary_data_type
 * @property integer $primary_data_id
 * @property string $secondary_data_type
 * @property integer $secondary_data_id
 * @property string $other_data
 * @property-read \App\Models\CourseContent $course_content
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Lecture whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Lecture whereLecTitle($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Lecture whereLecSubTitle($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Lecture whereLecSecId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Lecture whereLecContentId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Lecture whereLecType($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Lecture whereLecActive($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Lecture whereItemOrder($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Lecture whereLectureData($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Lecture whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Lecture whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Lecture whereCourseContentId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Lecture wherePrimaryDataType($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Lecture wherePrimaryDataId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Lecture whereSecondaryDataType($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Lecture whereSecondaryDataId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Lecture whereOtherData($value)
 * @mixin \Eloquent
 */
class Lecture extends Model implements CourseContentContract
{
    protected $table = 'lectures';

    protected $guarded = ['id'];

    private $primaryData = null;
    private $secondaryData = null;

    public function setLecSubTitleAttribute($val){
        if($val != ''){
            $val = HtmlTools::flyAddNofollowToLink($val);
        }
        $this->attributes['lec_sub_title'] = $val;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function course_content(){
        return $this->belongsTo('App\Models\CourseContent', 'course_content_id');
    }

    /**
     * @return mixed|string
     */
    public function get_title()
    {
        if(!$this->exists){
            return '';
        }
        return $this->lec_title;
    }

    /**
     * @return mixed|string
     */
    public function get_sub_title()
    {
        if(!$this->exists){
            return '';
        }
        return $this->lec_sub_title;
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

    public function delete_content($include_this = true){
        // remove primary data
        $primary_data = $this->getPrimaryData();
        if($primary_data != null && $primary_data->autoSave() && !$primary_data->delete()){
            return false;
        }
        // remove secondary data
        $secondary_data = $this->getSecondaryData();
        if($secondary_data != null && $secondary_data->autoSave() && !$secondary_data->delete()){
            return false;
        }
        // remove attachment

        // remove this
        if($include_this && !$this->delete()){
            return false;
        }
        return true;
    }

    public function removePrimaryData($save = false){
        $data = $this->getPrimaryData();
        if($data)$data->removeWhenUpdateLecture($this->id);
        $this->primary_data_id = 0;
        $this->primary_data_type = '';
        return $save ? $this->save() : true;
    }

    public function removeSecondaryData($save = false){
        $data = $this->getSecondaryData();
        if($data)$data->removeWhenUpdateLecture($this->id);
        $this->secondary_data_id = 0;
        $this->secondary_data_type = '';
        return $save ? $this->save() : true;
    }

//    public function update_content(CourseContentContract $content)
//    {
//        if($this->get_type() != $content->get_type()) return false;
//        $this->lec_title = $content->lec_title;
//        $this->lec_sub_title = $content->lec_sub_title;
//        $this->lecture_data = $content->lecture_data;
//        $this->lec_type = $content->lec_type;
//        return true;
//    }
    //public function getMain

    public function hasContent(){
        if($this->hasPrimaryData() || $this->hasSecondaryData()){
            return true;
        }
        return false;
    }

    public function hasPrimaryData(){
        if($this->primary_data_type != '' && $this->primary_data_id > 0){
            return true;
        }
        return false;
    }

    public function hasSecondaryData(){
        if($this->secondary_data_type != '' && $this->secondary_data_id > 0){
            return true;
        }
        return false;
    }

    public function hasAttachment(){
        if($this->other_data != ''){
            return true;
        }
        return false;
    }

    public function setPrimaryData(MediaContentContract $data, $save = true){
        $this->primary_data_type = $data->get_media_class();
        $this->primary_data_id = $data->get_media_id();
        // update data length
        $this->primary_data_length = $data->get_data_length();
        if($save){
            return $this->save();
        }
        return true;
    }

    public function setSecondaryData(MediaContentContract $data, $save = true){
        $this->secondary_data_type = $data->get_media_class();
        $this->secondary_data_id = $data->get_media_id();
        // update length
        $this->secondary_data_length = $data->get_data_length();
        if($save){
            return $this->save();
        }
        return true;
    }

    public function updateDataLength(){
        if($this->hasPrimaryData()){
            $this->primary_data_length = $this->getPrimaryData()->get_data_length();
        }
        if($this->hasSecondaryData()){
            $this->secondary_data_length = $this->getSecondaryData()->get_data_length();
        }
        return $this->save();
    }

    /**
     * @return MediaContentContract|null
     */
    public function getPrimaryData(){
        if($this->hasPrimaryData()){
            if(!$this->primaryData){
                $content_class = $this->primary_data_type;
                $this->primaryData = $content_class::find($this->primary_data_id);
            }
        }
        return $this->primaryData;
    }

    /**
     * @return MediaContentContract|null
     */
    public function getSecondaryData(){
        if($this->hasSecondaryData()){
            if(!$this->secondaryData){
                $content_class = $this->secondary_data_type;
                $this->secondaryData = $content_class::find($this->secondary_data_id);
            }

        }
        return $this->secondaryData;
    }

    public function addAttachment(AttachmentContract $attachmentContract){
        /** @todo finish it */
        return true;
    }

    public function removeAttachment(AttachmentContract $attachmentContract){
        /** @todo finish it */
        return true;
    }

    public function emptyAttachment(){
        /** @todo finish it */
        return true;
    }

}