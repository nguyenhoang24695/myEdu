<?php
/**
 * Created by PhpStorm.
 * User: hocvt
 * Date: 9/9/15
 * Time: 17:57
 */

namespace App\Http\Controllers\Frontend\Teacher;

use App\Console\Commands\ConvertVideo;
use App\Core\MediaProcess\MyFFMpeg;
use App\Core\MediaProcess\MyFFProbe;
use App\Core\MyStorage;
use App\Core\TimeLimitAccess;
use App\Http\Requests\Frontend\Teacher\AddVideoRequest;
use App\Models\Audio;
use App\Models\Course;
use App\Models\Document;
use App\Models\Lecture;
use App\Models\MediaContentContract;
use App\Models\Video;
use FFMpeg\Coordinate\TimeCode;
use FFMpeg\Driver\FFMpegDriver;
use FFMpeg\FFMpeg;
use FFMpeg\FFProbe;
use FFMpeg\Media\Frame;
use Illuminate\Contracts\Validation\ValidationException;
use Illuminate\Http\Exception\HttpResponseException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\MessageBag;
use League\Flysystem\Adapter\Local;

class LibraryController extends TeacherController
{

    public function index($media_type = 'video'){
        if(!in_array($media_type, ['video', 'document', 'audio'])){
            abort(404, "Chỉ hỗ trợ video, document, audio");
        }
        \SEO::setTitle(trans('meta.my_library.' . $media_type));
        $page_size = 10;
        $media_class = config('course.lecture_types.' . $media_type);
        $medias = null;
        switch($media_type){
            case 'video':
                $medias = Video::whereVidUserId(auth()->user()->id)->orderBy('created_at', 'desc')->simplePaginate($page_size);
                break;
            case 'document':
                $medias = Document::whereDocUserId(auth()->user()->id)->orderBy('created_at', 'desc')->simplePaginate($page_size);
                break;
            case 'audio':
                $medias = Audio::whereAudUserId(auth()->user()->id)->orderBy('created_at', 'desc')->simplePaginate($page_size);
                break;
        }
        $data['medias'] = $medias;
        $data['media_type'] = $media_type;
        $data['teacher_name'] = auth()->user()->name;
        return view('frontend.teacher.library.index', $data);
    }

    public function add(){

    }

    public function saveCourse(CourseRequest $courseRequest){

    }

    public function viewVideo($id){
        /** @var Video $video */
        $video = Video::find($id);
        if(!$video){
            abort(404);
        }

        \SEO::setTitle("Video :: " . $video->vid_title);

//        if($video->video_disk != 'vod_quochoc'){
//            $video->move_to_disk('vod_quochoc');
//        }


//        // update thumbnail
//        if($video->thumbnail_path != ""){
//            $deleted = MyStorage::getDisk($video->thumbnail_disk)->delete($video->thumbnail_path);
//            if($deleted){
//                $video->thumbnail_disk = '';
//                $video->thumbnail_path = '';
//            }
//            $video->save();
//        }
//        if($video->thumbnail_path == ''){
//            $thumbnail_info = MyFFMpeg::genThumbnail($video->video_disk, $video->video_file_path);
//
//            if($thumbnail_info['success'] == true){
//                $video->thumbnail_disk = $thumbnail_info['thumb_disk'];
//                $video->thumbnail_path = $thumbnail_info['thumb_path'];
//            }
//
//            $video->save();
//
//        }
        $data = ['video' => $video];
//        if($video->base_sub_path == '' || $video->sub_enabled == false){
//            $data['video_player'] = 'clappr';
//            $data['streams'] = $video->makeUrlSetLink();
//        }else{
            $data['streams'] = $video->get_stream_link();
        //dd($video->thumbnail_link('original'));
//        }
        return view('frontend.teacher.library.view_video', $data);
    }

    public function editVideo($id, Request $request){
        /** @var Video $video */
        $video = Video::find($id);
        if(!$video){
            abort(404);
        }

        \SEO::setTitle(trans('meta.my_library.edit_video'));

        if($request->isMethod('post')) {
            $this->validate($request, [
                'vid_title' => 'required|max:255',
                'vid_description' => 'required|max:255',
//                'vid_subtitle' => 'mimes:txt',
            ]);
            $file = $request->file('vid_subtitle');
            if($file){
                if($file->getClientOriginalExtension() != 'vtt'){
                    $msg = [
                        'vid_subtitle' => ['Chỉ chấp nhận phụ đề định dạng vtt'],
                    ];
                    $request->headers->set('referer', route('teacher.my_library.edit_video', ['id' => $video->id]));
                    throw new HttpResponseException($this->buildFailedValidationResponse($request, $msg));
                }
                $disk = MyStorage::getDisk($video->video_disk);
                $sub_ext = $file->getClientOriginalExtension();
                $sub_path = preg_replace( '/\.[A-Za-z0-9]+$/', '.' . $sub_ext, $video->video_file_path);
                $save_sub = $disk->putStream($sub_path, fopen($file->getRealPath(), 'rb'));
                if($save_sub){
                    $video->base_sub_path = $sub_path;
                    $video->sub_enabled = true;
                    if($video->convert_status != ConvertVideo::__ING){
                        $video->convert_status = ConvertVideo::__RE;
                    }
                }
            }
            $check_save = $video->update($request->only(['vid_title', 'vid_description']));
            if($check_save){
                return redirect()->route('teacher.my_library.video', ['id' => $id])->withFlashSuccess(trans('common.saved'));
            }else{
                return redirect()->route('teacher.my_library.video', ['id' => $id])->withFlashWarning(trans('common.unsaved'));
            }
        }
        return view('frontend.teacher.library.edit_video', ['video' => $video]);
    }

    public function addVideo(Request $request){

        \SEO::setTitle(trans('meta.my_library.add_video'));

        // lưu file
        if($request->isMethod('post')){
            if($request->hasFile('upload_file')){
                $file = $request->file('upload_file');
                $valid_file = MyStorage::defaultValidUploadFile($file, 'video');
                if($valid_file['valid'] == false){
                    return response()->json(['success' => false, 'message' => $valid_file['message']]);
                }

                $disk_name = config('flysystem.default_video');
                $file_name = md5(auth()->user()->id . time()) . '.' . $file->getClientOriginalExtension();
                $file_path = getPathByDay('/video', 'now', $file_name);

                $file_saved = MyStorage::saveUploadedFile($file, $disk_name, $file_path);

                if($file_saved){
                    $media = new Video();
                    $media->video_disk = $disk_name;
                    $media->user()->associate(auth()->user());
                    $media->video_file_path = $file_path;
                    $media->vid_title = $file->getClientOriginalName();
                    $media->vid_description = $file->getClientOriginalName();
                    $media->file_size = $file->getSize();
                    $media->file_type = $file->getMimeType();
                    if($media->save()){
                        // gen thumbnail
                        $thumnail_info = MyFFMpeg::genThumbnail(null, $file->getRealPath());
                        if($thumnail_info['success']){
                            $media->thumbnail_disk = $thumnail_info['thumb_disk'];
                            $media->thumbnail_path = $thumnail_info['thumb_path'];
                        }
                        // get duration
                        $meta_info = MyFFProbe::getMediaInfo(null, $file->getRealPath());
                        if($meta_info['success']){
                            $media->duration = $meta_info['duration'];
                        }

                        $media->save();

                        return response()->json(['success' => true,
                                                 'media_id' => $media->id,
                                                 'edit_link' => route('teacher.my_library.edit_video', ['id' => $media->id])]);
                    }
                }
            }else if($request->has('media_id')){
                /** @var Video $media */
                $media = Video::find($request->get('media_id', 0));
                if(!$media){
                    abort(404, 'Không tìm thấy media');
                }
                $media->vid_title = $request->get('vid_title');
                $media->vid_description = $request->get('vid_description');

                // add sub
                $file = $request->file('vid_subtitle');
                if($file){
                    if($file->getClientOriginalExtension() != 'vtt'){
                        $msg = [
                            'vid_subtitle' => ['Chỉ chấp nhận phụ đề định dạng vtt'],
                        ];
                        throw new HttpResponseException($this->buildFailedValidationResponse($request, $msg));
                    }
                    $disk = MyStorage::getDisk($media->video_disk);
                    $sub_ext = $file->getClientOriginalExtension();
                    $sub_path = preg_replace( '/\.[A-Za-z0-9]+$/', '.' . $sub_ext, $media->video_file_path);
                    $save_sub = $disk->putStream($sub_path, fopen($file->getRealPath(), 'rb'));
                    if($save_sub){
                        $media->base_sub_path = $sub_path;
                        $media->sub_enabled = true;
                        if($media->convert_status != ConvertVideo::__ING){
                            $media->convert_status = ConvertVideo::__RE;
                        }
                    }
                }

                if($media->save()){
                    return redirect(route('teacher.my_library.video', ['id' => $media->id]));
                }

            }
            return response()->json(['success' => false, 'message' => trans('course.alert.error_save')]);
        }

        javascript()->put([
            'upload_video_link' => $request->url(),
            'upload_video_max_size' => config('flysystem.max_size.video'),
            'upload_video_exts' => config('flysystem.exts.video'),
        ]);
        $data = [];
        return view('frontend.teacher.library.new_video');
    }

    public function addVideoIntro(Request $request){


        $course = Course::whereId($request->input('id', 0))->whereCouUserId(auth()->user()->id)->first();

        if(!$course){
            abort(404, 'Không tìm thấy khóa học');
        }
        \SEO::setTitle(trans('meta.my_library.add_video'));

        // lưu file
        if($request->isMethod('post')){

            if($request->hasFile('upload_file')){

                $file = $request->file('upload_file');
                $valid_file = MyStorage::defaultValidUploadFile($file, 'video');

                if($valid_file['valid'] == false){
                    return response()->json(['success' => false, 'message' => $valid_file['message']]);
                }

                $disk_name = config('flysystem.default_video');
                $file_name = md5(auth()->user()->id . time()) . '.' . $file->getClientOriginalExtension();
                $file_path = getPathByDay('/cou_intro_video', 'now', $file_name);
//                Log::log(1,$file_path);
                $file_saved = MyStorage::saveUploadedFile($file, $disk_name, $file_path);

                if($file_saved){

                    $course->update(['intro_video_path' => $file_path, 'intro_video_disk' => 'vod_myedu']);
                    $media = new Video();
                    $media->video_disk = $disk_name;
                    $media->user()->associate(auth()->user());
                    $media->video_file_path = $file_path;
                    $media->vid_title = $file->getClientOriginalName();
                    $media->vid_description = $file->getClientOriginalName();
                    $media->file_size = $file->getSize();
                    $media->file_type = $file->getMimeType();

                    if($media->save()){

                        return response()->json(['success' => true,
                            'media_id' => $media->id,
                            'edit_link' => route('teacher.build_course', ['id' => $request->id, 'action' => 'video_gioi_thieu'])]);
                    }
                }
            }
            return response()->json(['success' => false, 'message' => trans('course.alert.error_save')]);
        }

        javascript()->put([
            'upload_video_link' => $request->url(),
            'upload_video_max_size' => config('flysystem.max_size.video'),
            'upload_video_exts' => config('flysystem.exts.video'),
        ]);
        $data = [];
        //return view('frontend.teacher.library.new_video');
    }

    public function removeMedia($type, $id){
        $class = config('course.lecture_types.' . $type, false);
        /** @var Array $default_return */
        $default_return = trans('common.default_json_error');
        if($class == false){
            $default_return['message'] = 'Không hỗ trợ loại media này, hãy khai báo trong config';
            return response()->json($default_return);
        }
        /** @var MediaContentContract $media */
        if(!$media = $class::find($id)){
            $default_return['message'] = 'Không tìm thấy media cần xóa';
            return response()->json($default_return);
        }

        if($media->user->id != auth()->user()->id){
            abort(401, 'Không đủ quyền');
        }

        if(Lecture::wherePrimaryDataType($class)->wherePrimaryDataId($id)->exists()
            || Lecture::whereSecondaryDataType($class)->whereSecondaryDataId($id)->exists()){
            $default_return['message'] = 'Media còn liên quan đến bài học, không thể xóa.';
            return response()->json($default_return);
        }

        $media->delete();

        return response()->json(['success' => true, 'message' => trans('common.deleted')]);

    }

    public function viewAudio($id){
        /** @var Audio $audio */
        $audio = Audio::find($id);
        if(!$audio){
            abort(404);
        }


        \SEO::setTitle("Audio :: " . $audio->aud_title);

        $data['audio'] = $audio;
        $token_access = TimeLimitAccess::makeRequestToken($id);
        $data['download_link'] = route('api.audio.download',['id' => $id, 'token' => $token_access]);
        $data['stream_link'] = route('api.audio.stream',['id' => $id, 'token' => $token_access]) ;

        return view('frontend.teacher.library.view_audio', $data);
    }

    public function addAudio(Request $request){

        \SEO::setTitle(trans('meta.my_library.add_audio'));
        // lưu file
        if($request->isMethod('post')){
            if($request->hasFile('upload_file')){
                $file = $request->file('upload_file');
                $valid_file = MyStorage::defaultValidUploadFile($file, 'audio');
                if($valid_file['valid'] == false){
                    return response()->json(['success' => false, 'message' => $valid_file['message']]);
                }

                $disk_name = config('flysystem.default_audio');
                $file_name = md5(auth()->user()->id . time()) . '.' . $file->getClientOriginalExtension();
                $file_path = getPathByDay('/audio', 'now', $file_name);

                $file_saved = MyStorage::saveUploadedFile($file, $disk_name, $file_path);

                if($file_saved){
                    $media = new Audio();
                    $media->aud_disk = $disk_name;
                    $media->user()->associate(auth()->user());
                    $media->aud_file_path = $file_path;
                    $media->aud_title = $file->getClientOriginalName();
                    $media->aud_description = $file->getClientOriginalName();
                    $media->file_size = $file->getSize();
                    $media->file_type = $file->getMimeType();
                    if($media->save()){
                        // get duration
                        $meta_info = MyFFProbe::getMediaInfo(null, $file->getRealPath());
                        if($meta_info['success']){
                            $media->duration = $meta_info['duration'];
                        }

                        return response()->json(['success' => true, 'media_id' => $media->id]);
                    }
                }
            }else if($request->has('media_id')){
                /** @var Audio $media */
                $media = Audio::find($request->get('media_id', 0));
                if(!$media){
                    abort(404, 'Không tìm thấy media');
                }
                $media->aud_title = $request->get('aud_title');
                $media->aud_description = $request->get('aud_description');
                if($media->save()){
                    return redirect(route('teacher.my_library.audio', ['id' => $media->id]));
                }
            }
            return response()->json(['success' => false, 'message' => trans('course.alert.error_save')]);
        }
        javascript()->put([
            'upload_audio_link' => $request->url(),
            'upload_audio_max_size' => config('flysystem.max_size.audio'),
            'upload_audio_exts' => config('flysystem.exts.audio'),
        ]);
        $data = [];
        return view('frontend.teacher.library.new_audio');
    }

    public function viewDocument($id){
        /** @var Document $document */
        $document = Document::find($id);
        if(!$document){
            abort(404);
        }

        \SEO::setTitle("Document :: " . $document->doc_title);

        $data['document'] = $document;
        $data['download_link'] = $document->get_download_link();
        return view('frontend.teacher.library.view_document', $data);
    }

    public function addDocument(Request $request){

        if($request->isMethod('post')){
            if($request->hasFile('upload_file')){
                $file = $request->file('upload_file');
                $valid_file = MyStorage::defaultValidUploadFile($file, 'document');
                if($valid_file['valid'] == false){
                    return response()->json(['success' => false, 'message' => $valid_file['message']]);
                }

                $disk_name = config('flysystem.default_audio');
                $file_name = md5(auth()->user()->id . time()) . '.' . $file->getClientOriginalExtension();
                $file_path = getPathByDay('/document', 'now', $file_name);

                $file_saved = MyStorage::saveUploadedFile($file, $disk_name, $file_path);

                if($file_saved){
                    $media = new Document();
                    $media->doc_disk = $disk_name;
                    $media->user()->associate(auth()->user());
                    $media->doc_file_path = $file_path;
                    $media->doc_title = $file->getClientOriginalName();
                    $media->doc_description = $file->getClientOriginalName();
                    $media->file_size = $file->getSize();
                    $media->file_type = $file->getMimeType();
                    if($media->save()){
                        return response()->json(['success' => true, 'media_id' => $media->id]);
                    }
                }
            }else if($request->has('media_id')){
                /** @var Document $media */
                $media = Document::find($request->get('media_id', 0));
                if(!$media){
                    abort(404, 'Không tìm thấy media');
                }
                $media->doc_title = $request->get('doc_title');
                $media->doc_description = $request->get('doc_description');
                if($media->save()){
                    return redirect(route('teacher.my_library.document', ['id' => $media->id]));
                }
            }
            return response()->json(['success' => false, 'message' => trans('course.alert.error_save')]);
        }

        $data = [];

        javascript()->put([
            'upload_document_link' => $request->url(),
            'upload_document_max_size' => config('flysystem.max_size.document'),
            'upload_document_exts' => config('flysystem.exts.document'),

        ]);

        return view('frontend.teacher.library.new_document', $data);
    }

    public function addText(){
        echo "Comming soon";
    }


}