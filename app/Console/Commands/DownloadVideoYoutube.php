<?php

namespace App\Console\Commands;

use App\Core\MyStorage;
use App\Core\YoutbeDownloader;
use App\Events\Frontend\CourseContentChange;
use App\Models\CourseContent;
use App\Models\ExternalSource;
use App\Models\User;
use App\Models\Video;
use Illuminate\Console\Command;

class DownloadVideoYoutube extends Command
{
    const __WAITING = 0; // chờ dowload
    const __OK = 1; // convert thành công
    const __FAILS = 2; // dowload lỗi

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'youtube:download';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Download video tu youtube';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //Lấy danh sách video youtube
        $video_dl   =   ExternalSource::query();
        $video_dl   =   $video_dl->where('flag_dl',1)->where('dl_status',0)->orderBy('updated_at', 'ASC')->take(20);
        $count      =   0;
        $video_dl->chunk(5, function($videos) use($count){
            foreach($videos as $video){
                self::Download($video);
            }
        });
    }

    public static function Download(ExternalSource $externalSource)
    {
        $url_parsed = [];
        parse_str(parse_url($externalSource->content, PHP_URL_QUERY), $url_parsed);
        if (!isset($url_parsed['v'])) {
            return \Log::alert('Không xác định được video id');
        }
        $youtube_id     = $url_parsed['v'];

        if($youtube_id != ""){
            $quality_s  = YoutbeDownloader::getInstance()->getLink($youtube_id);

            if(is_string($quality_s)) {
                $externalSource->dl_status = self::__FAILS;
                $externalSource->save();
                echo $quality_s;
                \Log::alert($quality_s . 'Video ID = ' . $youtube_id);

            } else {

                foreach ($quality_s as $video) {

                    //Chỉ lấy định dạng MP4
                    if($video['type'] == 'video/mp4'){

                        //Thực hiện dowload về server
                        $disk_name = config('flysystem.default_video');
                        $file_name = md5($youtube_id . time()) . '.mp4';
                        $file_path = getPathByDay('/video', 'now', $file_name);

                        //$current   = file_get_contents($video['url']);
                        $current   = fopen($video['url'], 'rb');
                        $disk      = MyStorage::getDisk($disk_name);
                        $save      = $disk->writeStream($file_path, $current);

                        if($save){
                            \Log::alert('Download thành công Video ID = ' . $youtube_id);
                            $content   = CourseContent::find($externalSource->course_content_id);

                            //Lấy dung lượng của file
                            $file_size = $disk->getSize($file_path);

                            $media = new Video();
                            $media->video_disk      = $disk_name;
                            $media->video_file_path = $file_path;
                            $media->file_type       = $video['type'];
                            $media->file_size       = $file_size;
                            $media->is_auto_save    = 1;
                            $media->vid_title       = $externalSource->title;
                            $media->created_at      = $externalSource->created_at;
                            $media->user()->associate(User::find($content->course->cou_user_id));

                            //Dowload sub video
                            $sub_content  = YoutbeDownloader::getInstance()->getCaption($youtube_id);
                            if($sub_content == ""){
                                //không có sub vi thì lấy sub en
                                $sub_content  = YoutbeDownloader::getInstance()->getCaption($youtube_id,'vtt','en');
                            }
                            $sub_path     = preg_replace( '/\.[A-Za-z0-9]+$/', '.vtt', $file_path);

                            if($sub_content != ""){
                                $sub         = $disk->put($sub_path,$sub_content);
                                if($sub){
                                    $media->base_sub_path = $sub_path;
                                    $media->sub_enabled   = true;
                                }
                            }

                            //Lấy mô tả của video
                            $description    =   YoutbeDownloader::getInstance()->getDescription($youtube_id);
                            if($description != ""){
                                $media->vid_description =  $description;
                            }

                            if($media->save()){

                                //Xóa link youtube đi
                                $externalSource->delete();

                                //Lấy thông tin video
                                $media_object = Video::find($media->id);
                                if(!$media_object){
                                    return \Log::alert('Không tìm thấy media');
                                }

                                $addition = [];
                                $addition['new_media_id']   = $media_object->id;
                                $addition['new_media_type'] = $media_object->get_media_type();

                                //Gán video đó cho từng bài học tương ứng
                                if($content->getContent()->getPrimaryData()){
                                    $content->getContent()->getPrimaryData()->removeWhenUpdateLecture($content->getContent()->id);
                                }
                                //assign new
                                $content->getContent()->setPrimaryData($media_object);
                                $check_update = $content->getContent()->save();

                                event(new CourseContentChange($content->course));

                                if($check_update){
                                    return \Log::alert('Save thông tin thành công');
                                } else {
                                    return \Log::alert('Lỗi không update được nội dung video cho lecture');
                                }

                            } else {
                                //Xóa file trên server
                                MyStorage::removeFromDisk($disk_name,$file_path);
                                return \Log::alert('Khong save được thông tin video');
                            }
                        }
                    }
                }
            }
        }
    }
}
