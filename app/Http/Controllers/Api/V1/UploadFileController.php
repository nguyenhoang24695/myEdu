<?php
/**
 * Created by PhpStorm.
 * User: hocvt
 * Date: 9/11/15
 * Time: 10:24
 */

namespace App\Http\Controllers\Api\V1;

use App\Core\MyStorage;
use App\Jobs\RemoveTmpFile;
use App\Services\Access\Facades\Access;
use Illuminate\Http\Request;
use League\Flysystem\Plugin\ListPaths;
use League\Flysystem\Plugin\ListWith;

class UploadFileController extends ApiController
{

//    const UPLOAD_FILE = 0;
//    const UPLOAD_VIDEO = 1;
//    const UPLOAD_DOCUMENT = 2;
//    const UPLOAD_AUDIO = 3;
//
//    private $upload_type = [
//        'video' => self::UPLOAD_VIDEO,
//        'document' => self::UPLOAD_DOCUMENT,
//        'audio' => self::UPLOAD_AUDIO
//    ];
//
//    public function upload(Request $request){
//
//    }
//
//    public function uploadVideo(Request $request){
//        return $this->uploadProcess($request, 'video');
//    }
//
//    public function uploadDocument(Request $request){
//        return $this->uploadProcess($request, 'document');
//    }
//
//    public function uploadAudio(Request $request){
//        return $this->uploadProcess($request, 'audio');
//    }
//
//    private function uploadProcess(Request $request, $type){
//        $return = [
//            'success' => false,
//            'message' => trans('common.default_json_error.message')
//        ];
//
//        if(!isset($this->upload_type[$type])){
//            $return['message'] = 'Không hỗ trợ loại upload này';
//            return response()->json($return);
//        }
//
//        if($this->checkCanUpload() == false){
//            echo json_encode(trans('common.default_json_error_permission'));
//        }
//
//        // valid
//        if(!$request->has('uploadfile')){
//            $return['message'] = trans('validation.errors.blank_file_upload');
//            return response()->json($return);
//        }
//
//        // disk upload
//        $tmp_disk = MyStorage::getDisk('tmp');
//        // remove old file
//        if($request->has('old_file') && $request->get('old_file') != ''){
//            $old_path = self::getTmpPath($type, $request->get('old_file'));
//            if($tmp_disk->has($old_path)){
//                $tmp_disk->delete($old_path);
//            }
//        }
//
//        $file_uploaded = $request->file('uploadfile');
//        $file_name = md5(auth()->user()->id . time()) . '.' . $file_uploaded->getClientOriginalExtension();
//
//        switch($this->upload_type[$type]){
//            case self::UPLOAD_VIDEO :
//                $max_size = config('flysystem.max_size.video');
//                $allowed_exts = config('flysystem.exts.video');
//                break;
//            case self::UPLOAD_DOCUMENT :
//                $max_size = config('flysystem.max_size.document');
//                $allowed_exts = config('flysystem.exts.document');
//
//                break;
//            case self::UPLOAD_AUDIO :
//                $max_size = config('flysystem.max_size.audio');
//                $allowed_exts = config('flysystem.exts.audio');
//                break;
//        }
//
//        // valid
//        $this->validate($request, [
//            'uploadfile' => 'max:' . $max_size/1024 . '|mimes:' . implode(',',$allowed_exts)
//        ]);
//        $saved = $tmp_disk->writeStream(self::getTmpPath($type, $file_name),
//            fopen($file_uploaded->getRealPath(), 'rb'));
//
//        if (!$saved) { // ERROR
//            return response()->json(array('success' => false, 'msg' => 'ERROR UPLOADING...'));
//        }
//
////        $tmp_disk->addPlugin(new ListWith());
////        dd($tmp_disk->listWith(['timestamp'], 'video'));
//
//        // OK
//        // auto remove file
//        $job = (new RemoveTmpFile(self::getTmpPath($type, $file_name)))->delay(config('queue.tmp_timeout'));
//        $this->dispatch($job);
//        // return result
//        return response()->json(array('success' => true,
//            'file_tmp' => $file_name,
//            'file_name' => $file_uploaded->getClientOriginalName()));
//    }
//
//
//    private function checkCanUpload(){
//        // allow for all administrator
//        if(Access::hasRole(config('access.role_list.administrator')) == true)
//            return true;
//        // allow for all teacher
//        if(Access::hasRole(config('access.role_list.teacher')) == true)
//            return true;
//        return false;
//    }
//
//    public static function getTmpPath($type, $file_name = null){
//        if($file_name === null){
//            return  $type;
//        }
//        return  $type . DIRECTORY_SEPARATOR . $file_name;
//    }

}