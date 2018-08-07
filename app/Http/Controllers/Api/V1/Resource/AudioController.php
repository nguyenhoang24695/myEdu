<?php
/**
 * Created by PhpStorm.
 * User: hocvt
 * Date: 9/16/15
 * Time: 12:01
 */

namespace App\Http\Controllers\Api\V1\Resource;


use App\Core\MyStorage;
use App\Core\TimeLimitAccess;
use App\Models\Audio;
use Illuminate\Support\Facades\Response;
use League\Flysystem\Adapter\Local;

class AudioController extends ResourceController
{

    /** @var  Audio */
    private $audio;

    public function download($id, $token){
        $this->validDocId($id, $token);

        /** @var Local $disk_adapter */
        $disk_adapter = MyStorage::getDisk($this->audio->aud_disk)->getAdapter();

        if(!($disk_adapter instanceof Local)){
            abort(409, "File không hỗ trợ download");
        }

        $path = $disk_adapter->applyPathPrefix($this->audio->aud_file_path);

        $file_spl = new \SplFileInfo($path);

        return Response::download($file_spl, makeValidFileName($this->audio->aud_title, '[edus365] ') . "." . $file_spl->getExtension());

    }

    public function stream($id, $token){

    }

    private function validDocId($id, $token){
        $this->audio = Audio::find($id);
        if(!$this->audio){
            abort(404);
        }
        if(!TimeLimitAccess::checkRequestToken($token, $id)){
            abort(403);
        }
        return true;
    }
}