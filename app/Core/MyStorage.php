<?php
/**
 * Created by PhpStorm.
 * User: hocvt
 * Date: 9/14/15
 * Time: 17:35
 */

namespace App\Core;


use App\Core\ImageTemplate\CourseCoverLarge;
use App\Core\ImageTemplate\CourseCoverSmall;
use GrahamCampbell\Flysystem\Facades\Flysystem;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpFoundation\StreamedResponse;

class MyStorage
{
    /**
     * Lấy ổ đĩa mặc định của hệ thống
     * @return \League\Flysystem\Filesystem
     */
    public static function getDefaultDisk(){
        return self::getDisk(config('flysystem.default'));
    }

    /**
     * Lấy ổ đĩa tương ứng để thao tác
     * @param $disk
     * @return \League\Flysystem\Filesystem
     */
    public static function getDisk($disk){
        return \Flysystem::connection($disk);
    }

    /**
     * Lấy đường dẫn ảnh tùy thuộc vào các disk khác nhau
     * @todo chú ý từng disk khác nhau cần được ỗ trợ để có thể hiển thị.
     * @param $disk
     * @param $path
     * @param $template
     * @return string
     */
    public static function get_image_link($disk, $path, $template = 'medium'){
//        if(in_array($disk, ['public'])){
//            //return route('imagecache', ['template' => $template, 'filename' => self::pathToLink($path)]);
            return self::getThumbLinkAttribute($disk, $path,$template);
//        }else{
//            return self::get_default_image($template);
//        }

    }

    /**
     * Load ảnh cache
     * @param $disk
     * @param $path
     * @param $template
     * @return $path
     */
    public static function getThumbLinkAttribute($disk, $path, $template = 'medium', $flag = true){
        if($template == 'original'){
            $template = 'large';
        }
        $default =   url('/default/'.config('app.id').'/common.jpg');
        $image   =   ($path == "") ? $default : $path;
        try{

            $dir     = dirname($image);
            $file    = basename($image);
            $path_create = public_path('caches/'. $template . '/' . $dir);
            if(!\File::exists($path_create . '/' . $file)){
                if($flag){
                    \File::makeDirectory($path_create, $mode = 0777, true, true);
                }
                $cache   =  $path_create . '/' . $file;
//                $tmp = config('imagecache.templates.medium');
                $tmp     = config('imagecache.templates.'.$template);
//                dd('imagecache.templates.'.$template);
                \Image::make(MyStorage::getDisk($disk)->readStream($image))->filter(new $tmp)->save($cache);
            }
            return url('/caches/' . $template . '/' . self::pathToLink($path));
        } catch (\Exception $ex){
            return $default;
        }
    }

    /**
     * Get default image for specific goal
     * @param string $template
     * @param string $image
     * @return string
     */
    public static function get_default_image($image = 'common.jpg'){
        //return route('imagecache', ['template' => $template, 'filename' => 'default/' . $image]);
        $default =   url('/default/'.config('app.id').'/'.$image);
        return $default;
    }

    /**
     * @todo finish it !!!
     * @param $disk
     * @param $path
     * @param $options
     * @return string
     */
    public static function get_video_stream_link($disk, $path, $options){
        $version = array_get($options, 'version', 'origin');
        if(app()->environment() == 'production'){
            $storage_stream_config = config('flysystem.connections.' . $disk);
            if(!array_get($storage_stream_config, 'video_streaming_support', false)){
                return [];
            }else{
                try{
                    $closure = array_get($storage_stream_config, 'get_video_stream_closure');
                    $stream = call_user_func($closure,  $path, null, null );
                    if($version == 'hd'){
                        $stream['rel'] = '720';
                        $stream['label'] = 'HD';
                        $stream['title'] = 'HD';
                    }elseif($version == 'sd'){
                        $stream['rel'] = '360';
                        $stream['label'] = 'SD';
                        $stream['title'] = 'SD';
                    }else{
                        $stream['rel'] = '1000';
                        $stream['label'] = 'Origin';
                        $stream['title'] = 'Origin';
                    }

                    return $stream;
                }catch (\Exception $ex){
                    return [];
                }

            }
        }elseif(in_array($disk, ['public', 'tmp', 'local', 'vod_myedu'])){
            $stream = [
                'src' => route('api.video.stream',['id' => $options['id'], 'version' => $version]),
                'type' => 'video/mp4',
                'label' => strtoupper($version),
                'title' => strtoupper($version),
            ];
            return $stream;
        }
        return "";
    }

    /**
     * @todo finish it !!!
     * @param $disk
     * @param $path
     * @param $options
     * @return string
     */
    public static function get_audio_stream_link($disk, $path, $options){
        return "";
    }

    /**
     * @todo finish it !!!
     * @param $disk
     * @param $path
     * @param $options
     * @return string
     */
    public static function get_document_download_link($disk, $path, $options){
        if(in_array($disk, ['public', 'tmp', 'local'])
            && isset($options['secure']) && $options['secure'] == true
            && isset($options['document_id'])) {
            $id = $options['document_id'];
            return route('api.document.download',
                ['id' => $id, 'token' => TimeLimitAccess::makeRequestToken($id, '10minutes')]);
        }
        return "";
    }

    /**
     * @todo finish it !!!
     * @param $disk
     * @param $path
     * @param $options
     * @return string
     */
    public static function get_image_blog_link($disk, $path, $template = 'original'){
        if(in_array($disk, ['blog'])){
            return route('imagecache', ['template' => $template, 'filename' => self::pathToLink($path)]);
        }
    }

    public static function pathToLink($path){
        return str_replace('\\', '/', $path);
    }

    /**
     * @param $disk
     * @param $path
     * @return bool
     */
    public static function removeFromDisk($disk, $path){
        $disk = self::getDisk($disk);
        if($disk->has($path) && $disk->get($path)->isFile()){
            return $disk->delete($path);
        }
        return true;
    }

    public static function defaultValidUploadFile(UploadedFile $file, $type){
        $return = ['valid' => false, 'message' => 'Error'];
        if(is_string($type) && in_array($type,['video', 'document', 'audio','image'])){
            $allowed_size = [config('flysystem.max_size.' . $type)];
            $allowed_ext = config('flysystem.exts.' . $type);
        }elseif(is_array($type)){
            if(empty($type['allowed_size'])){
                $allowed_size = [config('flysystem.max_upload_size')];
            }elseif(is_array($type['allowed_size'])){
                $allowed_size = $type['allowed_size'];
            }else{
                $allowed_size = [intval($type['allowed_size'])];
            }

            if(empty($type['allowed_ext'])){
                $allowed_ext = [config('flysystem.upload_exts_default')];
            }elseif(is_array($type['allowed_ext'])){
                $allowed_ext = $type['allowed_ext'];
            }else{
                $allowed_ext = explode(',',$type['allowed_ext']);
            }
        }else{
            return $return;
        }

        if(count($allowed_size) == 1){
            $min_size = 0;
            $max_size = $allowed_size[0];
        }else{
            $min_size = $allowed_size[0];
            $max_size = $allowed_size[1];
        }
        $ext = $file->getClientOriginalExtension();
        $size = $file->getSize();
        if(!in_array(strtolower($ext), $allowed_ext)){
            $return['message'] = trans('validation.invalid_extension', ['exts' => implode(',', $allowed_ext)]);
            return $return;
        }
        if($size < $min_size || $size > $max_size){
            $return['message'] = trans_choice('validation.invalid_size',
                $min_size,
                ['min_size' => round($min_size/1024,3), 'max_size' => round($max_size/1024,3)]);
            return $return;
        }
        return ['valid' => true, 'message' => 'ok'];
    }

    public static function saveUploadedFile(UploadedFile $file, $disk_name, $file_path){
        $disk = self::getDisk($disk_name);
        return $disk->writeStream($file_path, fopen($file->getRealPath(), 'rb'));
    }

    /**
     * Download file tu flysystem disk, ho tro tat ca Adapter cuar flysystem
     * @param $disk_name
     * @param $path
     * @param string $custom_name
     *
     * @return StreamedResponse
     */
    public static function responseFromDisk($disk_name, $path, $custom_name = "", $ext_auto = true){
        $response = new StreamedResponse();
        $disk = self::getDisk($disk_name);
        try{
            $response->setCallBack(function() use($disk, $path)
            {
                echo $disk->get($path)->read();
            });
            $path_info = pathinfo($path);
            $file_name = $custom_name == "" ? $path_info['filename'] : $custom_name;
            if($ext_auto){
                $file_name = $file_name . "." . $path_info['extension'];
            }
            $disposition = $response->headers->makeDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT, $file_name);
            $response->headers->set('Content-Disposition',$disposition);
            return $response;
        }catch (\Exception $ex){
            \Log::error($ex->getMessage());
            abort(404);
        }

    }

    /**
     * get stream uri for local storage
     *
     * @param $path Path of video file from
     *
     * @param $object
     * @param $user
     *
     * @return array
     */
    public static function local_stream($path, $object, $user){
//        \Log::alert('Building stream link for ' . $path);
        //
        return [
            'type' => 'application/x-mpegURL',
            'src' => url('/') . $path . '/master.m3u8',
        ];
    }
    public static function vod_quochoc_stream($path, $object, $user){
//        \Log::alert('Building stream link for ' . $path);
        //
        return [
            'type' => 'application/x-mpegURL',
            'rel' => '720',
            'label' => 'HD',
            'title' => 'HD',
            'src' => url('/') . $path . '/master.m3u8',
        ];
    }
    public static function vod_ubclass_stream($path, $object, $user){
//        \Log::alert('Building stream link for ' . $path);
        //
        return [
            'type' => 'application/x-mpegURL',
            'rel' => '720',
            'label' => 'HD',
            'title' => 'HD',
            'src' => url('/') . $path . '/master.m3u8',
        ];
    }
}