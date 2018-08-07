<?php
/**
 * Created by PhpStorm.
 * User: hocvt
 * Date: 12/3/15
 * Time: 14:37
 */

namespace App\Http\Controllers\Api\V1\Resource;


use App\Http\Controllers\Api\V1\ApiController;
use App\Models\Course;
use App\Models\CourseContent;
use App\Models\ExternalSource;
use App\Models\Lecture;
use App\Models\User;
use Illuminate\Http\Request;

class YoutubeController extends ApiController
{

    private $client;
    private $youtube;

    function __construct()
    {
        $this->client = new \Google_Client();
        $this->client->setDeveloperKey(config('app.youtube_server_key'));
        $this->youtube = new \Google_Service_YouTube($this->client);
    }


    /**
     * Truyền link playlist từ youtube vào, chương trình tự động thêm bài học vào khóa học được chọn, người dùng phải
     * có quyền can_import_video_playlist và là người tạo khóa học
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function importPlaylist(Request $request){
        // check permission
        /** @var User $logined_user */
        $logined_user = auth()->user();
        if(!$logined_user->hasPermission(config('access.perm_list.can_import_video_playlist'))){
            abort(401, 'Không có quyền sử dụng tính năng này');
        }
        $youtube_link = $request->get('link', '');
        $course_id = $request->get('course_id', 0);

        $course = Course::find($course_id);
        if (!$course) {
            abort(404, "Không tìm thấy khóa học");
        }elseif(myRole($course, auth()->user()) != 'teacher'){
            abort(401, "Bạn phải là người tạo khóa học này");
        }

        try {

            $url_parsed = [];
            parse_str(parse_url($youtube_link, PHP_URL_QUERY), $url_parsed);
            if (!isset($url_parsed['list'])) {
                abort(404, "Không xác định được playlist id");
            }
            $playlist_id = $url_parsed['list'];

            /** @var \Google_Service_YouTube_PlaylistListResponse $playlist */
            $playlist = $this->youtube->playlistItems->listPlaylistItems('id,snippet', [
                'playlistId' => $playlist_id,
                'maxResults' => 50
            ]);
            $playlist_videos = $playlist->getItems();

            foreach($playlist_videos as $a_video) {
                $this->importVideoToCourse($course, $a_video->getSnippet());
            }

            while($playlist->getNextPageToken() != null){
                $nextPageToken = $playlist->getNextPageToken();
                $playlist = $this->youtube->playlistItems->listPlaylistItems('id,snippet', [
                    'playlistId' => $playlist_id,
                    'maxResults' => 50,
                    'pageToken' => $nextPageToken,
                ]);
                $playlist_videos = $playlist->getItems();
                foreach($playlist_videos as $a_video) {
                    $this->importVideoToCourse($course, $a_video->getSnippet());
                }
            }

            return response()->json([
                'success' => true,
                'message' => "DONE"
            ]);

        }catch (\Exception $ex){
            \Log::error($ex->getMessage());
            return response()->json([
                'success' => false,
                'message' => $ex->getMessage()
            ]);
        }

    }

    public function importVideo(Request $request){

        // check permission
        /** @var User $logined_user */
        $logined_user = auth()->user();
        if(!$logined_user->hasPermission(config('access.perm_list.can_import_video_playlist'))){
            abort(401, 'Không có quyền sử dụng tính năng này');
        }
        $youtube_link = $request->get('link', '');
        $course_id = $request->get('course_id', 0);

        $course = Course::find($course_id);
        if (!$course) {
            abort(404, "Không tìm thấy khóa học");
        }elseif(myRole($course, auth()->user()) != 'teacher'){
            abort(401, "Bạn phải là người tạo khóa học này");
        }
        try {

            $url_parsed = [];
            parse_str(parse_url($youtube_link, PHP_URL_QUERY), $url_parsed);
            if (!isset($url_parsed['v'])) {
                abort(404, "Không xác định được video id");
            }
            $video_id = $url_parsed['v'];

            /** @var \Google_Service_YouTube_Video $video */
            $video = $this->youtube->videos->listVideos('snippet', [
                'id' => $video_id,
            ])->getItems()[0];

            if($cc = $this->importVideoToCourse($course, $video->getSnippet())){
                return response()->json([
                    'success' => true,
                    'message' => 'DONE!',
//                    'content' => $cc->getContent(),
//                    'id' => $cc->getContent()->id,
                ]);
            }else{
                throw new \Exception("Không import được.");
            }



        }catch (\Exception $ex){
            \Log::error($ex->getMessage());
            return response()->json([
                'success' => false,
                'message' => $ex->getMessage()
            ]);
        }


    }

    private function importVideoToCourse(Course $course, $youtube_video_snippet){
        try{
            $video_title = array_get($youtube_video_snippet, 'title');
            $video_id = $youtube_video_snippet->resourceId->videoId;
            // create lecture
            $lecture = new Lecture();
            $lecture->lec_title = $video_title;
            if(!$lecture->save()){
                throw new \Exception("Không tạo được lecture");
            }
            // create course content
            $course_content = new CourseContent();
            $course_content->course()->associate($course);
            $course_content->set_content($lecture);
            $course_content->content_order = $course->content_count + 1;
            if(!$course_content->save()){
                throw new \Exception("Không tạo được course content");
            }
            // created attachment
            $attachment = new ExternalSource();
            $attachment->source_type = 'youtube';
            $attachment->course_content()->associate($course_content);
            $attachment->content = 'http://youtube.com/watch/?v=' . $video_id;
            $attachment->title = $video_title;
            $attachment->flag_dl = 1; //Đánh dấu video youtube sẽ dowload về server
            if(!$attachment->save()){
                throw new \Exception("Không tạo được course content");
            }

            $course->updateCounter();

            return $course_content;
        }catch (\Exception $ex){
            try{
                $lecture->delete();
                $course_content->delete();
                $attachment->delete();
            }catch(\Exception $ex1){

            }
            \Log::error($ex->getMessage());
            return false;
        }

    }
}