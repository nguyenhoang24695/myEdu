<?php
/**
 * Created by PhpStorm.
 * User: hocvt
 * Date: 11/23/15
 * Time: 11:11
 */

namespace App\Http\Controllers\Backend;


use App\Console\Commands\ConvertVideo;
use App\Core\MediaProcess\MyFFMpeg;
use App\Core\MediaProcess\MyFFProbe;
use App\Http\Controllers\Controller;
use App\Models\Audio;
use App\Models\Course;
use App\Models\CourseContent;
use App\Models\Lecture;
use App\Models\Video;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class CourseContentController extends Controller
{

    public function getUpdateMediaDuration(){
        $videos = Video::where('duration', '');
        foreach($videos as $video){
            /** @var Video $video */
            if($video->duration <= 0){
                $meta_info = MyFFProbe::getMediaInfo($video->video_disk, $video->video_file_path);
                if($meta_info['success']){
                    $video->duration = $meta_info['duration'];
                }else{
                    echo "<br/>" . $video->vid_title . " :: " . $meta_info['message'];
                }
            }
        }
    }

    public function getVideoStatus(Request $request){
        $videos = Video::with('user')->orderBy('updated_at', 'desc');

        $filter_by_user_id = $request->query('user_id', null);// id or email, array accepted

        $filter_by_video_id = $request->query('id', '');//
        if($filter_by_video_id != ''){
            $video_ids = explode(",", $filter_by_video_id);
            $videos = $videos->whereIn('id', $video_ids);
        }

        $filter_by_video_status = $request->query('status', '');//
        if($filter_by_video_status != ''){
            $videos = $videos->where('convert_status', $filter_by_video_status);
        }

        $filter_by_course_id = $request->query('course_id', '');
        if($filter_by_course_id !== ''){
            /** @var Course $course */
            $course = Course::find($filter_by_course_id);
            if($course){
                /** @var Collection $course_content */
                $course_content = $course->getCachedContents();
                $video_ids = $course_content->map(function($item){
                    /** @var CourseContent $item */
                    if($item->get_type() == config('course.content_types.lecture')){
                        /** @var Lecture $lecture */
                        $lecture = $item->getContent();
                        return $lecture->primary_data_id;
                    }
                    return null;
                })->all();
            }
            $videos = $videos->whereIn('id', array_filter($video_ids));
        }

        $videos = $videos->simplePaginate(20);

        $convert_status_list = [
            '' => 'Tất cả trạng thái',
            ConvertVideo::__RE_SD => 'Chờ convert lại SD',
            ConvertVideo::__RE_HD => 'Chờ convert lại HD',
            ConvertVideo::__GEN_INFO => 'Chờ tạo lại info',
            ConvertVideo::__RE => 'Chờ convert lại chủ động',
            ConvertVideo::__ING => 'Đang convert',
            ConvertVideo::__WAITING => 'Chưa convert',
            ConvertVideo::__OK => 'Convert OK',
            ConvertVideo::__FAILS => 'Lỗi khi convert',
        ];

        //dd(action('Backend\CourseContentController@getUpdateMediaDuration'));
        return view('backend.course_content.video_status', ['medias' => $videos, 'convert_status_list' => $convert_status_list]);
    }

    public function getReconvertVideo($video_id, $option = null){
        if($option == null){
            $option = ConvertVideo::__RE;
        }
        /** @var Video $video */
        $video = Video::find($video_id);
        if(!$video){
            abort(404, "Not found media!!!");
        }
        else{
            $video->convert_status = $option;
            $success = $video->save();
            return redirect()->back()->withFlashSuccess("Đã thêm video vào danh sách chờ convert lại");
//            return response()->json([
//                'success' => $success,
//                'news_status' => $video->convert_status_string,
//            ]);
        }
    }

    public function getUpdateStatus($type, $id){
        switch($type){
            case 'video':
                /** @var Video $video */
                $video = Video::find($id);
                if(!$video){
                    abort(404, "Not found media!!!");
                }

                $video->gen_info();

                break;
            case 'audio':
                /** @var Audio $media */
                $media = Audio::find($id);
                if(!$media){
                    abort(404, "Not found media!!!");
                }

                if($media->duration <= 0){
                    $meta_info = MyFFProbe::getMediaInfo($media->get_media_disk(), $media->get_media_path());
                    if($meta_info['success']){
                        $media->duration = $meta_info['duration'];
                    }
                }

                $media->save();
                break;
            default :
                abort(404, "Not supported!!!");
        }
        return redirect()->back()->withFlashSuccess("Đã cập nhật thông tin");
    }
}