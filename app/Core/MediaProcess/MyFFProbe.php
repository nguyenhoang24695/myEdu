<?php
/**
 * Created by PhpStorm.
 * User: hocvt
 * Date: 11/20/15
 * Time: 14:54
 */

namespace App\Core\MediaProcess;


use App\Core\MyStorage;
use FFMpeg\FFProbe;
use League\Flysystem\Adapter\Local;

class MyFFProbe
{
    /**
     * Trả về đối tương FFProbe để thao tác lấy thông tin file media
     * @return FFProbe
     */
    public static function create(){
        return FFProbe::create([
            'ffmpeg.binaries'  => config('ffmpeg.binaries.ffmpeg'),
            'ffmpeg.threads'  => config('ffmpeg.binaries.thread'),
            'ffmpeg.timeout' => config('ffmpeg.binaries.c_timeout'),

            'ffprobe.binaries' => config('ffmpeg.binaries.ffprobe'),
            'ffprobe.timeout' => config('ffmpeg.binaries.c_timeout'),
        ]);
    }

    /**
     * Lấy thông tin file media : duration, type, ....
     * Hiện tại lấy ra duration, các thông tin khác cần thiết sẽ thêm sau
     * @param $disk
     * @param $path
     * @return array
     */
    public static function getMediaInfo($disk, $path){
        $return = ['success' => false,
            'message' => '',
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
            $probe = MyFFProbe::create();
            $file_format = $probe->streams($file_path);
//            dd($file_format->all());
            $return['duration'] = $file_format->first()->get('duration');
            try{
                $_video = $file_format->videos()->first();
                $return['height'] = $_video->get('height');
                $return['width'] = $_video->get('width');
            }catch(\Exception $ex){

            };

            $return['success'] = true;

        }catch (\Exception $ex){
            $return['message'] = $ex->getMessage();
            \Log::error($ex->getMessage());
        }
        return $return;
    }
}