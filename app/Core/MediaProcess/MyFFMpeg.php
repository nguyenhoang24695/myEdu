<?php
/**
 * Created by PhpStorm.
 * User: hocvt
 * Date: 11/20/15
 * Time: 15:27
 */

namespace App\Core\MediaProcess;


use App\Core\MyStorage;
use FFMpeg\Coordinate\TimeCode;
use FFMpeg\FFMpeg;
use League\Flysystem\Adapter\Local;

class MyFFMpeg
{

    public static function create(){
        return FFMpeg::create([
        	'timeout' => config('ffmpeg.binaries.c_timeout'),
            'ffmpeg.binaries'  => config('ffmpeg.binaries.ffmpeg'),
            'ffmpeg.threads'  => config('ffmpeg.binaries.thread'),
            'ffmpeg.timeout' => config('ffmpeg.binaries.c_timeout'),

            'ffprobe.binaries' => config('ffmpeg.binaries.ffprobe'),
            'ffprobe.timeout' => config('ffmpeg.binaries.c_timeout'),
        ]);
    }

    /**
     * @param $disk
     * @param $path
     * @param string $thumb_disk
     * @param string $thumb_path
     * @param int $time_code thoi gian shot
     *
     * @return array
     */
    public static function genThumbnail($disk, $path, $thumb_disk = 'public', $thumb_path = '', $time_code = 1){
        if(auth()->user()){
            $secure_id = auth()->user()->id;
        }else{
            $secure_id = config('app.key');
        }

        if($thumb_path == ''){
            $thumb_path = getPathByDay('video_thumbnail') .'/'. md5($secure_id . time()).".jpg";
        }

        $return = ['success' => false,
            'message' => '',
            'thumb_disk' => $thumb_disk,
            'thumb_path' => $thumb_path,
        ];
        try{
            if($disk != null){
                // chi ho tro Local driver
                $disk = MyStorage::getDisk($disk);
                /** @var Local $disk_adapter */
                $disk_adapter = $disk->getAdapter();
                if(get_class($disk_adapter) != Local::class){
                    throw new \Exception("Không hỗ trợ adapter khác Local Adapter");
                }
                $file_path = $disk_adapter->applyPathPrefix($path);
            }else{
                $file_path = $path;
            }
            if(!file_exists($file_path) || is_dir($file_path)){
                throw new \Exception("Không tồn tại file cần xử lý");
            }


            /** @var FFMpeg $ffmpeg */
            $ffmpeg = MyFFMpeg::create();
            $video = $ffmpeg->open($file_path);
            /** @var Frame $frame */
            $frame = $video->frame(TimeCode::fromSeconds($time_code));
            $temp_file = tempnam(sys_get_temp_dir(), 'thumbnail').'.jpg';
            $frame->save($temp_file);

            $save_thumbnail = MyStorage::getDisk($thumb_disk)->writeStream($thumb_path, fopen($temp_file, 'rb'));

            if(!$save_thumbnail){
                throw new \Exception("Không lưu được file vào " . $thumb_disk . "::" . $thumb_path);
            }

            $return['success'] = true;

        }catch (\Exception $ex){
            $return['message'] = $ex->getMessage();
            \Log::error($ex->getMessage());
        }
        return $return;
    }
}