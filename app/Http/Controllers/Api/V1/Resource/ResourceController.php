<?php
/**
 * Created by PhpStorm.
 * User: hocvt
 * Date: 9/15/15
 * Time: 15:29
 */

namespace App\Http\Controllers\Api\V1\Resource;


use App\Http\Controllers\Api\V1\ApiController;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ResourceController extends ApiController
{
    /**
     * @todo remove it and use MyStorage::defaultValidUploadFile
     * @param UploadedFile $file
     * @return array
     * @deprecated Use MyStorage::defaultValidUploadFile instead
     * @see MyStorage::defaultValidUploadFile
     */
    public function validUploadedFile($file, $type){
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
        $ext = $file->guessExtension();
        $size = $file->getSize();
        if(!in_array($ext, $allowed_ext)){
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

}