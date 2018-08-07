<?php
/**
 * Created by PhpStorm.
 * User: hocvt
 * Date: 9/15/15
 * Time: 15:30
 */

namespace App\Http\Controllers\Api\V1\Resource;


use App\Core\MyStorage;
use App\Core\TimeLimitAccess;
use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use League\Flysystem\Adapter\Local;


class DocumentController extends ResourceController
{
    /** @var  Document */
    private $document;

    public function download($id, $token){

        $this->validDocId($id, $token);

        /** @var Local $disk_adapter */
        $disk_adapter = MyStorage::getDisk($this->document->doc_disk)->getAdapter();
        
        if(!($disk_adapter instanceof Local)){
            abort(409, "File không hỗ trợ download");
        }

        $path = $disk_adapter->applyPathPrefix($this->document->doc_file_path);

        $file_spl = new \SplFileInfo($path);

        return Response::download($file_spl, makeValidFileName($this->document->doc_title, '[edus365] ') . "." . $file_spl->getExtension());
    }

    private function validDocId($id, $token){
        $this->document = Document::find($id);
        if(!$this->document){
            abort(404);
        }
        if(!TimeLimitAccess::checkRequestToken($token, $id)){
            abort(403);
        }
        return true;
    }

    public function searchMyLib(Request $request){
        $keyword = $request->get('keyword','');
        $my_id = auth()->user()->id;
        //if($type == 'document'){
            $documents = Document::whereDocUserId($my_id)
                ->where('doc_title', 'like', '%'.$keyword.'%')
                ->orderBy('created_at','desc')
                ->limit(5)
                ->get(['id', 'doc_title', 'created_at', 'doc_description']);
        //}
        return response()->json(['success' => true, 'documents' => $documents]);
    }

}