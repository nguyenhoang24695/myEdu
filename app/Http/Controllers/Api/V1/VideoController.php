<?php
/**
 * Created by PhpStorm.
 * User: hocvt
 * Date: 9/14/15
 * Time: 18:31
 */

namespace App\Http\Controllers\Api\V1;

use App\Core\MyStorage;
use App\Core\TimeLimitAccess;
use App\Core\VideoStreaming;
use App\Models\Video as VideoModel;
use App\Models\Video;
use Illuminate\Http\Request;
use League\Flysystem\Adapter\Local;


class VideoController extends ApiController
{

    public function stream($id, $version = 'origin'){
        /** @var VideoModel $video */
        $video = VideoModel::find($id);
        if(!$video){
            return response(trans('common.default_json_error'), 404);
        }
        // stream video
        $disk = MyStorage::getDisk($video->video_disk);
        if(!$disk->has($video->video_file_path)){
            abort(404, "Không tìm thấy video file");
        }
        /** @var Local $adapter */
        $adapter = $disk->getAdapter();
        if(!($adapter instanceof Local)){
            throw new \Exception("Video chưa hỗ trợ xem trực tuyến");
        }
        $f_path = $video->video_file_path;
        if($version == 'hd'){
            $f_path = $video->video_hd_path;
        }elseif($version == 'sd'){
            $f_path = $video->video_sd_path;
        }
        $path = $adapter->applyPathPrefix($f_path);
        $video_streamer = new VideoStreaming($path);
        return $video_streamer->start();
    }

    public function startView(){

    }

    public function searchMyLib(Request $request){
        $keyword = $request->get('keyword','');
        $my_id = auth()->user()->id;
        $query = [
            "bool" => [
                "must" => [
                    [
                        "multi_match" => [
                            "query" => $keyword,
                            "fields" => ["vid_title", "vid_description"]
                            ]
                    ],
                    [
                        "match" => [
                            "vid_user_id" => $my_id
                        ]
                    ]
                ]
            ]
        ];
        if($type = 'video'){
            try{
                $videos = Video::searchByQuery($query, null, ['created_at' => 'desc'], 10);
            }catch (\Exception $ex){
                $videos = Video::whereVidUserId($my_id)
                                    ->where('vid_title', 'like', '%'.$keyword.'%')
                                    ->orderBy('created_at','desc')
                                    ->limit(10)
                                    ->get();
            }

        }
        return response()->json(['success' => true, 'videos' => $videos]);
    }

    public function get_sub($video_id, $token){
        // check token
        if(!TimeLimitAccess::checkRequestToken($token, $video_id)){
            abort(403);
        }
        // check video
        /** @var Video $video */
        $video = Video::findOrFail($video_id);
        // response sub content
        return MyStorage::responseFromDisk($video->video_disk, $video->base_sub_path);

    }

}