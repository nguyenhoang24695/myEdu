<?php

namespace App\Models;

use App\Core\MediaProcess\MyFFProbe;
use App\Core\MyStorage;
use App\Models\Traits\MediaOfLecture;
use FFMpeg\FFProbe;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Audio
 *
 * @property integer $id
 * @property string $aud_title
 * @property string $aud_description
 * @property integer $aud_user_id
 * @property boolean $aud_active
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property integer $is_public
 * @property string $aud_disk
 * @property string $aud_file_path
 * @property boolean $is_auto_save
 * @property integer $duration
 * @property integer $file_size
 * @property string $file_type
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Audio whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Audio whereAudTitle($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Audio whereAudDescription($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Audio whereAudUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Audio whereAudActive($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Audio whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Audio whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Audio whereIsPublic($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Audio whereAudDisk($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Audio whereAudFilePath($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Audio whereIsAutoSave($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Audio whereDuration($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Audio whereFileSize($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Audio whereFileType($value)
 * @mixin \Eloquent
 */
class Audio extends Model implements MediaContentContract
{

    use MediaOfLecture;

    protected $table = 'audios';

    //
    protected $fillable = [
        'aud_title',
        'aud_description',
        'aud_active',
        'is_public',
        'aud_disk',
        'aud_file_path',
    ];

    public function user(){
        return $this->belongsTo('App\Models\User', 'aud_user_id');

    }

    public function getAudTitleAttribute()
    {
        if(empty($this->attributes['aud_title'])){
            return trans('common.missing.title');
        }else{
            return $this->attributes['aud_title'];
        }
    }

    public function getAudDescriptionAttribute()
    {
        if(empty($this->attributes['aud_description'])){
            return trans('common.missing.description');
        }else{
            return $this->attributes['aud_description'];
        }
    }


    public function get_media_id()
    {
        return $this->id;
    }

    public function setTitle($title)
    {
        $this->aud_title = $title;
    }

    public function getTitle()
    {
        return $this->aud_title;
    }

    public function setSubTitle($sub_title)
    {
        $this->aud_description = $sub_title;
    }

    public function getSubTitle()
    {
        return $this->aud_description;
    }

    public function setCreator($user_id)
    {
        return $this->aud_user_id = $user_id;
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
            return 'audio';
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
        if($this->exists){
            return $this->aud_disk;
        }
        return '';
    }

    public function setMediaDisk($disk)
    {
        // TODO: Implement setMediaDisk() method.
    }

    /**
     * @return string đường dẫn lưu trữ từ thư mục gốc của ổ đĩa
     */
    public function get_media_path()
    {
        // TODO: Implement get_media_path() method.
    }

    public function setMediaPath($path)
    {
        // TODO: Implement setMediaPath() method.
    }

    /**
     * @param $disk ổ đĩa đích
     * @param array $disk_only chỉ chuyển sang ổ đĩa đích nếu ổ đĩa trong nhóm này
     * @param array $disk_ignore không di chuyển nếu dữ liệu đang ở một trong những ổ đĩa này
     * @return bool true nếu di chuyển ok, false nếu di chuyển không thành công.
     */
    public function move_to_disk($disk, $new_path = '', $disk_only = [], $disk_ignore = [])
    {
        // TODO: Implement move_to_disk() method.
    }

    /**
     * Trả về link đến thumbnail của media
     * @param $template
     * @return mixed
     */
    public function thumbnail_link($template = 'small')
    {
        return MyStorage::get_default_image($template, 'video.jpg');
    }

    /**
     * Các media cần phải được gen thumbnail qua hàm này
     * @return mixed
     */
    public function gen_thumbnail()
    {
        // TODO: Implement gen_thumbnail() method.
    }

    /**
     * Thử xóa media khi cập nhật lecture, nhưng nếu media đó còn dùng ở 1 lecture khác thì sẽ không bị xóa
     * @param $id
     * @return mixed
     */
    public function removeWhenUpdateLecture($id)
    {
        // TODO: Implement removeWhenUpdateLecture() method.
    }

    /**
     * Gen thumbnail, get file size, page number, ...
     * @return mixed
     */
    public function gen_info($regen = false, $save = true)
    {
        try{
            if($this->duration <= 0){
                $meta_info = MyFFProbe::getMediaInfo($this->get_media_disk(), $this->get_media_path());
                if($meta_info['success']){
                    $this->duration = $meta_info['duration'];
                }
            }
        }catch (\Exception $ex){
            \Log::error($ex->getMessage());
            return false;
        }

        //return !$save || $this->save();

        if($save){
            return $this->save();
        }
        return true;
    }

    public function deletePlus() {
        // TODO: Implement deletePlus() method.
        return $this->delete();
    }

    public function get_data_length() {
        // TODO: Implement get_data_length() method.
        return $this->duration;
    }


}
