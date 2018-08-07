<?php

namespace App\Models;

use App\Core\MyStorage;
use App\Jobs\MoveDocumentToNewDisk;
use App\Models\Traits\MediaOfLecture;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Bus\DispatchesJobs;

/**
 * App\Models\Document
 *
 * @property integer $id
 * @property string $doc_title
 * @property string $doc_description
 * @property integer $doc_user_id
 * @property string $doc_type
 * @property boolean $doc_active
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property integer $is_public
 * @property string $doc_disk
 * @property string $doc_file_path
 * @property string $thumbnail_disk
 * @property string $thumbnail_path
 * @property boolean $is_auto_save
 * @property integer $pages
 * @property integer $file_size
 * @property string $file_type
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Document whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Document whereDocTitle($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Document whereDocDescription($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Document whereDocUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Document whereDocType($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Document whereDocActive($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Document whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Document whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Document whereIsPublic($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Document whereDocDisk($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Document whereDocFilePath($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Document whereThumbnailDisk($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Document whereThumbnailPath($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Document whereIsAutoSave($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Document wherePages($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Document whereFileSize($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Document whereFileType($value)
 * @mixin \Eloquent
 */
class Document extends Model implements MediaContentContract
{
    use MediaOfLecture;
    use DispatchesJobs;

    //


    protected $fillable = [
        'doc_title',
        'doc_description',
        'doc_active',
        'is_public',
        'doc_disk',
        'doc_file_path',
    ];

    /**
     * User created it
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(){
        return $this->belongsTo('App\Models\User', 'doc_user_id');
    }

    /**
     * Lecture using it
     */
    public function getUsingLectures($is_bool = false){
        return $is_bool ? $this->getUsingLectureScope()->get() : $this->getUsingLectureScope()->exists();
    }

    public function getDocTitleAttribute()
    {
        if(empty($this->attributes['doc_title'])){
            return trans('common.missing.title');
        }else{
            return $this->attributes['doc_title'];
        }
    }

    public function getDocDescriptionAttribute()
    {
        if(empty($this->attributes['doc_description'])){
            return trans('common.missing.description');
        }else{
            return $this->attributes['doc_description'];
        }
    }

    /**
     * @param array $exclude
     * @return mixed
     */
    public function countUsingLectures(Array $exclude = []){
        return $this->getUsingLectureScope()->whereNotIn('id', $exclude)->count();
    }

    /**
     * @return Builder
     */
    private function getUsingLectureScope(){
        return Lecture::where(function(Builder $query){
            $query->where('primary_data_type', config('course.lecture_types.document'));
            $query->where('primary_data_id', $this->id);
        })->orWhere(function(Builder $query){
            $query->where('secondary_data_type', config('course.lecture_types.document'));
            $query->where('secondary_data_id', $this->id);
        });
    }

    /**
     * @param array $options
     * @return string
     */
    public function get_download_link($options = []){
        if(!$this->exists) return "";
        $options['secure'] = true;
        $options['document_id'] = $this->id;
        return MyStorage::get_document_download_link($this->doc_disk, $this->doc_file_path,$options);
    }

    public function get_media_id()
    {
        return $this->id;
    }

    public function setTitle($title)
    {
        $this->doc_title = $title;
    }

    public function getTitle()
    {
        return $this->doc_title;
    }

    public function setSubTitle($sub_title)
    {
        $this->doc_description = $sub_title;
    }

    public function getSubTitle()
    {
        return $this->doc_description;
    }

    /**
     * @param int $user_id
     */
    public function setCreator($user_id)
    {
        $this->doc_user_id = $user_id;
    }

    public function autoSave($bool = null)
    {
        if($bool == null){
            return boolval($this->is_auto_save);
        }else{
            $this->is_auto_save = boolval($bool);
        }
    }

    /**
     * @return string loại dữ liệu
     */
    public function get_media_type()
    {
        if($this->exists){
            return 'document';
        }
        return '';
    }

    public function get_media_class()
    {
        return $this->getMorphClass();
    }


    /**
     * @return string ổ lưu trữ dữ liệu
     */
    public function get_media_disk()
    {
        return $this->doc_disk;
    }

    public function setMediaDisk($disk)
    {
        $this->doc_disk = $disk;
    }

    /**
     * @return string đường dẫn lưu trữ từ thư mục gốc của ổ đĩa
     */
    public function get_media_path()
    {
        return $this->doc_file_path;
    }

    public function setMediaPath($path)
    {
        $this->doc_file_path = $path;
    }

    /**
     * @param $disk ổ đĩa đích
     * @param array $disk_only chỉ chuyển sang ổ đĩa đích nếu ổ đĩa trong nhóm này
     * @param array $disk_ignore không di chuyển nếu dữ liệu đang ở một trong những ổ đĩa này
     * @return bool true nếu di chuyển ok, false nếu di chuyển không thành công.
     */
    public function move_to_disk($disk, $new_path = '', $disk_only = [], $disk_ignore = [])
    {
        if($this->doc_disk == $disk) return true;// nếu đã ở ổ đĩa đích thì bỏ qua
        $job = new MoveDocumentToNewDisk($this->id, $disk);
        //$job->delay(1);// delay a second
        $this->dispatch($job);// add job to queue
    }

    public function save(array $options = [])
    {
        $return = parent::save($options);
        if($return && $this->wasRecentlyCreated){
            $this->move_to_disk(config('flysystem.default_document'));
        }
        return $return;
    }


    public function thumbnail_link($template = 'small')
    {
        return MyStorage::get_default_image($template, 'document.jpg');
    }

    public function gen_thumbnail()
    {
        // TODO: Implement gen_thumbnail() method.
    }

    public function delete()
    {
        try{
            $deleted = MyStorage::removeFromDisk($this->doc_disk, $this->doc_file_path);
            if(!$deleted)return false;// khong xoa dc file thi ko xoa ban ghi
        }catch (\Exception $ex){
            \Log::error("Không xóa được file. " . $ex->getMessage());
        }
        return parent::delete(); // TODO: Change the autogenerated stub
    }

    public function removeWhenUpdateLecture($id)
    {
        if(!$this->autoSave()) return true;
        if($this->countUsingLectures([$id]) == 0){
            return $this->delete();
        }
        return true;

    }


    /**
     * Gen thumbnail, get file size, page number, ...
     * @return mixed
     */
    public function gen_info($regen = false, $save = true)
    {
        // TODO: Implement gen_info() method.
    }

    public function deletePlus() {
        // TODO: Implement deletePlus() method.
        return $this->delete();
    }

    public function get_data_length() {
        // TODO: Implement get_data_length() method.
        return $this->pages;
    }


}
