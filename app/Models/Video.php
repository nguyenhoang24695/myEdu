<?php

namespace App\Models;

use App\Console\Commands\ConvertVideo;
use App\Core\MediaProcess\Dimensions\Hd;
use App\Core\MediaProcess\Dimensions\Sd;
use App\Core\MediaProcess\Filters\Subtitle;
use App\Core\MediaProcess\Formats\Mp4Format;
use App\Core\MediaProcess\MyFFMpeg;
use App\Core\MediaProcess\MyFFProbe;
use App\Core\MyStorage;
use App\Core\TimeLimitAccess;
use App\Jobs\MoveVideoToNewDisk;
use App\Models\Traits\MediaOfLecture;
use Elasticquent\ElasticquentTrait;
use FFMpeg\Coordinate\TimeCode;
use FFMpeg\Exception\InvalidArgumentException;
use FFMpeg\Exception\RuntimeException;
use FFMpeg\Media\Frame;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Bus\DispatchesJobs;
use League\Flysystem\Adapter\Local;

/**
 * App\Models\Video
 *
 * @property integer $id
 * @property string $vid_title
 * @property string $vid_description
 * @property integer $vid_user_id
 * @property boolean $vid_active
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property integer $is_public
 * @property string $video_disk
 * @property string $video_file_path
 * @property boolean $is_auto_save
 * @property integer $duration
 * @property string $thumbnail_disk
 * @property string $thumbnail_path
 * @property integer $file_size
 * @property string $file_type
 * @property string $base_sub_path
 * @property string $video_hd_path
 * @property string $video_sd_path
 * @property string $sub_languages
 * @property integer $convert_status
 * @property-read \App\Models\User $user
 * @property-read mixed $convert_status_string
 * @property-read mixed $human_duration
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Video whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Video whereVidTitle($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Video whereVidDescription($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Video whereVidUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Video whereVidActive($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Video whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Video whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Video whereIsPublic($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Video whereVideoDisk($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Video whereVideoFilePath($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Video whereIsAutoSave($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Video whereDuration($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Video whereThumbnailDisk($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Video whereThumbnailPath($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Video whereFileSize($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Video whereFileType($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Video whereBaseSubPath($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Video whereVideoHdPath($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Video whereVideoSdPath($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Video whereSubLanguages($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Video whereConvertStatus($value)
 * @mixin \Eloquent
 */
class Video extends Model implements MediaContentContract
{
    use DispatchesJobs;
    use MediaOfLecture;
    use ElasticquentTrait;
    //
    protected $fillable = [
        'vid_title',
        'vid_description',
        'vid_active',
        'is_public',
        'video_disk',
        'video_file_path',
    ];

    public $autoMoveToDisk = true;

    protected $mappingProperties = array(
        'vid_title' => array(
            'type' => 'string',
            'analyzer' => 'standard'
        ),
        'vid_description' => array(
            'type' => 'string',
            'analyzer' => 'standard'
        ),
        'vid_user_id' => array(
            'type' => 'integer',
            'analyzer' => 'standard'
        ),
        'video_disk' => array(
            'type' => 'string',
            'analyzer' => 'standard'
        ),
//        'created_at' => array(
//            'type' => 'date',
//            'format' => "basic_date_time_no_millis",
//        ),
//        'updated_at' => array(
//            'type' => 'date',
//            "format" => "yyy-MM-dd HH:mm:ss",
//        ),
    );

    function getIndexDocumentData()
    {
        return array(
            'id'      => $this->id,
            'vid_title'   => $this->vid_title,
            'vid_description'  => $this->vid_description,
            'duration'  => $this->duration,
            'video_hd_path'  => $this->video_hd_path,
            'video_sd_path'  => $this->video_sd_path,
            'base_sub_path'  => $this->base_sub_path,
            'created_at' => $this->created_at,
        );
    }

    public function user(){
        return $this->belongsTo('App\Models\User', 'vid_user_id');
    }

    /**
     * Lecture using it
     */
    public function getUsingLectures($is_bool = false){
        return $is_bool ? $this->getUsingLectureScope()->exists() : $this->getUsingLectureScope()->get();
    }

    /**
     * @param array $exclude
     * @return mixed
     */
    public function countUsingLectures(Array $exclude = []){
        $return = $this->getUsingLectureScope($exclude)->count();
        \Log::alert($this->id . "-" . $return);
        return $return;
    }

    /**
     * @param array $exclude
     * @return Builder
     */
    private function getUsingLectureScope(Array $exclude = []){
        return Lecture::where(function(Builder $query) use ($exclude){
            $query->whereNotIn('id', $exclude);
            $query->where('primary_data_type', config('course.lecture_types.video'));
            $query->where('primary_data_id', $this->id);
        })->orWhere(function(Builder $query) use ($exclude){
            $query->whereNotIn('id', $exclude);
            $query->where('secondary_data_type', config('course.lecture_types.video'));
            $query->where('secondary_data_id', $this->id);
        });
    }

    public function getVidTitleAttribute()
    {
        if(empty($this->attributes['vid_title'])){
            return trans('common.missing.title');
        }else{
            return $this->attributes['vid_title'];
        }
    }

    public function getVidDescriptionAttribute()
    {
        if(empty($this->attributes['vid_description'])){
            return trans('common.missing.description');
        }else{
            return $this->attributes['vid_description'];
        }
    }

    public function get_stream_link($options = []){
        if(!$this->exists)return "";
        $options['id'] = $this->id;
        $options['secure'] = true;
        $streams = [];
        if($this->video_hd_path != ''){
            $options['version'] = 'hd';
            $streams[] = MyStorage::get_video_stream_link($this->video_disk, $this->video_hd_path, $options);
        }
        if($this->video_sd_path != ''){
            $options['version'] = 'sd';
            $streams[] = MyStorage::get_video_stream_link($this->video_disk, $this->video_sd_path, $options);
        }
        if(count($streams) == 0){
            $streams[] = MyStorage::get_video_stream_link($this->video_disk, $this->video_file_path, $options);
        }
        return $streams;
    }

    public function makeUrlSetLink(){
        if(!$this->exists)return "";
        $options['id'] = $this->id;
        $options['secure'] = true;
        $stream = MyStorage::get_video_stream_link($this->video_disk, $this->video_file_path, $options);
        $base_url = $stream['src'];
        $set = [];
        if(strpos($this->video_hd_path, '_hd')){
            $hd_version = preg_replace('/^.*(_hd\d*)\.mp4$/', '$1', $this->video_hd_path);
            $set[] = $hd_version;
        }

        if(strpos($this->video_sd_path, '_sd')){
            $sd_version = preg_replace('/^.*(_sd\d*)\.mp4*$/', '$1', $this->video_sd_path);
            $set[] = $sd_version;
        }

        \Log::alert($set);

        $version_count = count($set);
        switch($version_count){
            case 0:
                $set = ",,";
                break;
            case 1:
                $set = ",," . $set[0] . ",";
                break;
            case 2:
                $set = "," . $set[0] . "," . $set[1] . ",";
                break;
            default:
                $set = ',,';
        }

        if(isset($set)){
            $base_url = str_replace('.mp4', $set . ".mp4.urlset", $base_url);
        }

        $stream['url'] = $base_url;
        return $stream;
    }

    public function get_sub_link($language = ""){
        if(!$this->sub_enabled){
            return "";
        }
        $token = TimeLimitAccess::makeRequestToken($this->id);
        return route('api.video.subtitle', [
            'id' => $this->id,
            'token' => $token,
        ]);
    }

    public function delete()
    {
        $disk = MyStorage::getDisk($this->video_disk);
        if($disk->has($this->video_file_path) && $disk->get($this->video_file_path)->isFile()){
            $disk->delete($this->video_file_path);
        }
        if($disk->has($this->video_hd_path) && $disk->get($this->video_hd_path)->isFile()){
            $disk->delete($this->video_hd_path);
        }
        if($disk->has($this->video_sd_path) && $disk->get($this->video_sd_path)->isFile()){
            $disk->delete($this->video_sd_path);
        }
        if($disk->has($this->base_sub_path) && $disk->get($this->base_sub_path)->isFile()){
            $disk->delete($this->base_sub_path);
        }
        return parent::delete(); // TODO: Change the autogenerated stub
    }


    public function get_media_type()
    {
        if($this->exists){
            return 'video';
        }
        return '';
    }

    public function get_media_class()
    {
        return $this->getMorphClass();
    }


    public function get_media_disk()
    {
        if($this->exists){
            return $this->video_disk;
        }
        return '';
    }

    public function get_media_path()
    {
        if($this->exists){
            return $this->video_file_path;
        }
        return '';
    }

    public function move_to_disk($disk, $new_path = '', $disk_only = [], $disk_ignore = [])
    {
        if($new_path == ''){
            $new_path = config('flysystem.default_video');
        }
        if($this->video_disk == $disk) return true;// nếu đã ở ổ đĩa đích thì bỏ qua
        $job = new MoveVideoToNewDisk($this->id, $disk);
        //$job->delay(1);// delay a second
        $this->dispatch($job);// add job to queue
    }

    public function save(array $options = [])
    {
        $return = parent::save($options);
//        if($return && $this->wasRecentlyCreated){
//            $this->move_to_disk(config('flysystem.default_video'));
//        }
        if($return){
            try{
                $this->addToIndex();
            }catch (\Exception $ex){

            }
        }
        return $return;
    }


    public function get_media_id()
    {
        return $this->id;
    }

    public function thumbnail_link($template = 'small')
    {
        if($this->thumbnail_path != ''){
            return MyStorage::get_image_link($this->thumbnail_disk, $this->thumbnail_path, $template);
        }
        return MyStorage::get_default_image($template, 'video.jpg');
    }

    public function gen_thumbnail()
    {
        // TODO: Implement gen_thumbnail() method.
    }


    public function setTitle($title)
    {
        $this->vid_title = $title;
    }

    public function getTitle()
    {
        return $this->vid_title;
    }

    public function setMediaDisk($disk)
    {
        $this->video_disk = $disk;
    }

    public function setMediaPath($path)
    {
        $this->video_file_path = $path;
    }

    public function setSubTitle($sub_title)
    {
        $this->vid_description = $sub_title;
    }

    public function getSubTitle()
    {
        return $this->vid_description;
    }

    public function setCreator($user_id)
    {
        $this->vid_user_id = $user_id;
    }

    /**
     * Kiểm tra hoặc cài đặt video hiện tại có phải là tự động lưu khi build lecture content hay không
     * @param null $bool
     * @return bool
     */
    public function autoSave($bool = null)
    {
        if($bool == null){
            return boolval($this->is_auto_save);
        }else{
            $this->is_auto_save = boolval($bool);
        }
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
     * Convert to another version with video
     * @return mixed
     */
    public function gen_info($regen = false, $save = true)
    {
        // TODO: Implement gen_info() method.

        try{
            /** Gen lại  */
            if($regen || $this->thumbnail_path == ""){
                $thumbnail_info = MyFFMpeg::genThumbnail($this->video_disk, $this->video_file_path);
                if($thumbnail_info['success']){
                    $this->thumbnail_disk = $thumbnail_info['thumb_disk'];
                    $this->thumbnail_path = $thumbnail_info['thumb_path'];
                }
            }

            if($regen || $this->duration <= 0){
                $meta_info = MyFFProbe::getMediaInfo($this->video_disk, $this->video_file_path);
                if($meta_info['success']){
                    $this->duration = $meta_info['duration'];
                }
            }
        }catch (\Exception $ex){
            \Log::error($ex->getTraceAsString());
            return false;
        }

        if($save){
            $this->updateDataLengthForLecture($this->get_data_length());
            return $this->save();
        }
        return true;
    }

    public function deletePlus() {
        // TODO: Xóa các file được tạo ra cho các định dạng,
        return $this->delete();
    }


    public function gen_other_formats(){
        // change to converting;
        $o_status = $this->convert_status;
        $this->convert_status = ConvertVideo::__ING;
        $this->save();
        try{
            $ffmpeg = MyFFMpeg::create();
            $mp4_format = new Mp4Format();
//            $mp4_format->on('progress', function ($video, $format, $percentage) {
//                \Log::alert("$percentage % transcoded");
//            echo "\n$percentage % transcoded";
//            });
            $mp4_format->setAudioChannels(2);

            $disk = MyStorage::getDisk($this->video_disk);
            /** @var Local $disk_adapter */
            $disk_adapter = $disk->getAdapter();
            if(get_class($disk_adapter) != Local::class){
                throw new \Exception("Không hỗ trợ adapter khác Local Adapter");
            }
            $o_path = $this->video_file_path;
            $o_full_path = $disk_adapter->applyPathPrefix($o_path);

            $next_success_count = $this->convert_success_count + 1;

            $hd_path = self::make_path($o_path, 'hd' . $next_success_count);
            $hd_full_path = self::make_path($o_full_path, 'hd' . $next_success_count);

            $sd_path = self::make_path($o_path, 'sd' . $next_success_count);
            $sd_full_path = self::make_path($o_full_path, 'sd' . $next_success_count);

            $video = $ffmpeg->open($o_full_path);
            // add subtitle
//            $video->addFilter()
            //echo "\n === Start process " . $this->video_file_path . " === \n";
            $video_format_info = MyFFProbe::getMediaInfo($this->video_disk, $this->video_file_path);
            if(!empty($this->base_sub_path)){
                $sub_title_path = $disk_adapter->applyPathPrefix($this->base_sub_path);
                if(!file_exists($sub_title_path)){
                    throw new InvalidArgumentException("==vid" . $this->id . "==Không tìm thấy sub " . $sub_title_path);
                    $sub_title_path = false;
                }
                $subtitle_filter = new Subtitle($sub_title_path);
                // apply style
                $subtitle_filter->fontSize(22);


            }else{
                $sub_title_path = false;
            }

            /** Quyết định convert dựa vào kích thước video gốc */
            if($video_format_info['height'] <= 360){                // gốc nhỏ hơn SD
                $convert_sd = 'origin';                         // lay ban goc
                $convert_hd = false;
            }elseif($video_format_info['height'] <= 720){           // gốc nhỏ hơn HD, lớn hơn SD
                $convert_sd = true;                             // convert ko sub
                $convert_hd = 'origin';                         // lấy bản gốc
            }else{                                                  // Gốc lớn hơn HD
                $convert_sd = true;                             // convert ko sub
                $convert_hd = true;                             // convert ko sub
            }

            /** Quyết định convert dựa vào trạng thái sub */
            if($sub_title_path != false){
                $video->addFilter($subtitle_filter);
                $convert_sd = true;
                $convert_hd = true;
            }

            /** Quyết định convert dựa vào lựa chọn chủ động của quản trị */
            if($o_status == ConvertVideo::__RE_HD){
                $convert_sd = false;
                $convert_hd = true;
            }elseif($o_status == ConvertVideo::__RE_SD) {
                $convert_sd = true;
                $convert_hd = false;
            }elseif($o_status == ConvertVideo::__RE){
                $convert_sd = true;
                $convert_hd = true;
            }

            \Log::alert([
                'o_status' => $o_status,
                'sub_title_path' => $sub_title_path,
                'convert_hd' => $convert_hd,
                'hd_full_path' => $hd_full_path,
                'convert_sd' => $convert_sd,
                'sd_full_path' => $sd_full_path,
            ]);

            if($convert_sd === 'origin'){
//                \Log::alert("No Convert SD");
                $this->video_sd_path = $this->video_file_path;
                $this->save();
            }elseif($convert_sd === true){
//                \Log::alert("Convert SD");
                $mp4_format->setAudioKiloBitrate(64);               // audio bitrate
                $mp4_format->setKiloBitrate(300);                   // video bitrate
                $video->filters()->resize(new Sd())->synchronize(); // resize
                $video->save($mp4_format, $sd_full_path);           // convert
                if($this->video_sd_path != ''
                   && $this->video_sd_path != $this->video_file_path
                   && $disk->has($this->video_sd_path)) {
                    $disk->delete($this->video_sd_path );           // xóa file cũ
                }
                $this->video_sd_path = $sd_path;                    // gán file mới
                $this->save();                                      // save
            }else{

//                \Log::alert(var_export($convert_sd, true));
            }

            if($convert_hd === 'origin') {
//                \Log::alert("No Convert HD");
                $this->video_hd_path = $this->video_file_path;
                $this->save();
            }elseif($convert_hd === true) {
//                \Log::alert("Convert HD");
                $mp4_format->setAudioKiloBitrate(128);               // audio bitrate
                $mp4_format->setKiloBitrate(1500);                   // video bitrate
                $video->filters()->resize(new Hd())->synchronize(); // resize
                $video->save($mp4_format, $hd_full_path);           // convert
                if($this->video_hd_path != ''
                   && $this->video_hd_path != $this->video_file_path
                   && $disk->has($this->video_hd_path)) {
                    $disk->delete($this->video_hd_path );           // xóa file cũ
                }
                $this->video_hd_path = $hd_path;                    // gán file mới
                $this->save();                                      // save
            }else{

//                \Log::alert(var_export($convert_hd, true));
            }


            $this->convert_success_count = $this->convert_success_count + 1; // tăng số lần convert thành công

            $this->convert_status = ConvertVideo::__OK;                 // Everything is OK
            if($convert_hd == $convert_sd && $convert_hd == 'with_sub'){
                $this->sub_enabled = false;
            }
            $this->save();

        }catch (RuntimeException $ex){
            $pre_ex = $ex->getPrevious();
            \Log::error($msg = $pre_ex->getMessage());
            $this->convert_fail_count = $this->convert_fail_count + 1; // tăng số lần convert thất bại
            $this->convert_status = ConvertVideo::__FAILS;
            $this->save();
        }catch (InvalidArgumentException $ex){
            \Log::error($msg = $ex->getMessage());
            $this->convert_fail_count = $this->convert_fail_count + 1; // tăng số lần convert thất bại
            $this->convert_status = ConvertVideo::__FAILS;
            $this->save();
        }catch (\Exception $ex){
            \Log::error($msg = $ex->getMessage());
            $this->convert_fail_count = $this->convert_fail_count + 1; // tăng số lần convert thất bại
            $this->convert_status = ConvertVideo::__FAILS;
            $this->save();
        }
        $msg = "OK";
        return "\n[" . date('Y-m-d H:i:s') . "] " . $msg;
    }

    /**
     * Chỉ là string path, chưa tác động đến disk
     * @param $dimension HD/SD
     *
     * @return mixed
     */
    public static function make_path($path, $dimension){
        return preg_replace('/\.([a-zA-Z0-9]+)$/', '_' . $dimension . '.$1', $path);
    }

    public function getConvertStatusStringAttribute() {
        switch($this->convert_status){
            case ConvertVideo::__OK:
                return 'success';
            case ConvertVideo::__GEN_INFO:
                return 'update info';
            case ConvertVideo::__RE_SD:
                return 'Reconvert SD';
            case ConvertVideo::__RE_HD:
                return 'Reconvert HD';
            case ConvertVideo::__ING:
                return 'converting';
            case ConvertVideo::__WAITING:
            case ConvertVideo::__RE:
                return 'waiting';
            default :
                return 'fail';
        }
    }


    public function getHumanDurationAttribute(){
        return gmdate('H:i:s', $this->duration);
    }

    public function get_data_length() {
        // TODO: Implement get_data_length() method.
        return $this->duration;
    }


}
