<?php
/**
 * Created by PhpStorm.
 * User: hocvt
 * Date: 9/20/15
 * Time: 13:04
 */

namespace App\Http\Controllers\Api\V1\Resource;


use App\Core\MyStorage;
use App\Events\Frontend\ViewCourseContentLogging;
use App\Models\Course;
use App\Models\CourseContent;
use App\Models\CourseContentViewLog;
use App\Models\CourseStudent;
use App\Models\Section;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Intervention\Image\Facades\Image;
use League\Flysystem\FileNotFoundException;

class CourseController extends ResourceController
{

    private $building_actions = [];

    function __construct()
    {
        $this->building_actions = config('course.content_actions');
    }

    public function updateAvatar(Request $request){

        /** @var Course $course */
        $course = Course::whereId($request->input('id', 0))->whereCouUserId(auth()->user()->id)->first();

        if(!$course){
            abort(404, 'Không tìm thấy khóa học');
        }elseif($course->cou_user_id != auth()->user()->id){
            return response('Unauthorized!', 401);
        }

        // valid file
        $file_uploaded = $request->file('cou_avatar');
        $valid_result = MyStorage::defaultValidUploadFile($file_uploaded, 'image');


        if($valid_result['valid'] == false){
            return response()->json(['success' => false, 'message' => $valid_result['message']]);
        }

        //save new image
        $disk = MyStorage::getDisk('public');
        $name = getPathByDay('cou_avatar','now',
            md5(auth()->user()->id . time()) . '.' . $request->file('cou_avatar')->getClientOriginalExtension());

        $uploaded_image = Image::make($request->file('cou_avatar'));
        // valid min dimension
        if($uploaded_image->height() < config('flysystem.course_avatar_min_size.height')
            || $uploaded_image->width() < config('flysystem.course_avatar_min_size.width')){
            return response()->json(['success' => false,
                'message' => trans('validation.image_dimension',
                    ['height' => config('flysystem.course_avatar_min_size.height'),
                     'width' => config('flysystem.course_avatar_min_size.width'),
                    ])]);
        }

        $saved = $disk->putStream($name,
                $uploaded_image->resize(2048, 2048, function ($constraint) {
                    $constraint->aspectRatio();
                })
                ->encode(null,100)
                ->stream()
                ->detach());
        if($saved){
            // remove old image
            try{
                $old_disk = MyStorage::getDisk($course->cover_disk);
                if($old_disk && $course->cover_path != '' &&$old_disk->has($course->cover_path)){
                    $old_disk->delete($course->cover_path);
                }
            }catch (FileNotFoundException $e){
                // do nothing
            }
            $saved = $course->update(['cover_path' => $name, 'cover_disk' => 'public']);

            if($saved){
                return response()->json(['success' => true]);
            }
        }

        return response()->json(trans('common.default_json_error'));



    }

    public function updateIntroVideo(Request $request){
        /** @var Course $course */
        $course = Course::whereId($request->input('id', 0))->whereCouUserId(auth()->user()->id)->first();

        if(!$course){
            abort(404, 'Không tìm thấy khóa học');
        }

        // valid file
        $file_uploaded = $request->file('intro_video');
        $valid_result = MyStorage::defaultValidUploadFile($file_uploaded, 'video');
        if($valid_result['valid'] == false){
            return response()->json(['success' => false, 'message' => $valid_result['message']]);
        }

        //save new video
        $disk = MyStorage::getDisk('public');
        $name = getPathByDay('cou_intro_video','now',
            md5(auth()->user()->id . time()) . '.' . $request->file('intro_video')->getClientOriginalExtension());

        $saved = $disk->writeStream($name, fopen($file_uploaded->getRealPath(), 'rb'));
        if($saved){
            // remove old image
            try{
                $old_disk = MyStorage::getDisk($course->intro_video_disk);
                if($old_disk && $course->intro_video_path != '' && $old_disk->has($course->intro_video_path)){
                    $old_disk->delete($course->intro_video_path);
                }
            }catch (FileNotFoundException $e){
                // do nothing
            }
            $saved = $course->update(['intro_video_path' => $name, 'intro_video_disk' => 'public']);

            if($saved){
                return response()->json(['success' => true]);
            }
        }
        else
        {
            return response()->json(trans('common.default_json_error'));
        }
    }

    /**
     * @param $id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse\
     * @throws \Exception
     */
    public function buildContent($id, Request $request){

        if($request->get('action', '') == 'reorder'){
            return $this->reorderContents($id, $request);
        }
        if($request->get('action', '') == 'change_privacy'){
            return $this->changePrivacy($id, $request);
        }

        /** @var CourseContent $course_content */
        /** @var Course $course */
        list($action, $content_action, $course, $course_content) = $this->validBuildingRequest($id, $request);

        $command = config('course.content_type_commands.' . $this->building_actions[$action]);

        $course->touch();

        try{
            $data = $request->get('data', []);
            if($course_content && $course_content->exists){
                $data['id'] = $course_content->content_id;
            }
            $run_command = $this->dispatchFrom($command, $request,
                ['user' => auth()->user(),
                    'course' => $course,
                    'course_content' => $course_content,
                    'data' => $data]
            );
            // stop if error occur
            if($run_command['success'] != true){
                return response()->json($run_command);
            }

            switch($content_action){
                case 'add':
                case 'new':
                    $course_content = new CourseContent();

                    $course_content->course()->associate($course);

                    $course_content->set_content($run_command['content']);

                    $course_content->content_order = $course->course_contents()->count('id') + 1;

                    if($course_content->save()){
                        return response()->json($run_command + ['course_content' => $course_content->to_array()]);
                    }else{
                        $run_command['content']->delete_content();
                        return response()->json(trans('common.default_json_error'));
                    }
                    break;

                case 'edit':
                case 'update':
                    $course_content->touch();
                    return response()->json($run_command + ['course_content' => $course_content->to_array()]);
                    break;

                case 'delete':
                case 'remove':
                    $course_content->delete();
                    return response()->json($run_command);
                    break;
            }

        }catch (\Exception $ex){
            \Log::error($ex->getMessage());
            return response()->json(['success' => false, 'message' => $ex->getMessage()]);
        }
    }

    private function reorderContents($id, Request $request){
        /** @var Course $course */
        $course = Course::find($id);
        if(!$course){
            abort('404', 'Không thấy khóa học');
        }
        $cc = $request->get('new_order', []);
        $cc_list = $course->course_contents;
        $return = trans('common.default_json_error');

        if(count($cc) == count($cc_list)){
            try{
                DB::transaction(function() use($cc_list, $cc){

                    foreach($cc_list as $_cc){
                        $_index = array_search($_cc->id, $cc) + 1;
                        //Log::alert($_index);
                        DB::table($_cc->getTable())->where('id', $_cc->id)->update(['content_order' => $_index]);
                    }
                });
                // return true
                $return['success'] = true;
                $return['message'] = trans('common.saved');
                $course->touch();
            }catch (\Exception $ex){
                \Log::error($ex->getMessage());
                $return['message'] = $ex->getMessage();
            }

        }
        // return false;
        return response()->json($return);
    }

    private function changePrivacy($id, Request $request){
        $new_edit_status = "";
    }

    private function validBuildingRequest($id, Request $request){
        $action = $request->get('action');
        if(!array_has($this->building_actions, $action)){
            abort('404', 'Không hỗ trợ action');
        }
        $course = Course::find($id);
        if(!$course){
            abort('404', 'Không thấy khóa học');
        }
        $course_content = null;
        if($request->has('content_id')){
            $course_content = CourseContent::find($request->get('content_id'));
        }

        $content_action = preg_replace('/_.*$/', '', $action);
        if(!in_array($content_action, ['add','new','edit','update','delete','remove']))
        {
            abort('404', 'Không hỗ trợ action');
        }

        return [$action, $content_action, $course, $course_content];
    }

    public function students(Request $request){
        $course_id = $request->input('course_id');
        $coure_students = CourseStudent::with('user')->whereCourseId($course_id)->get();
        $student_list = [];
        if($coure_students){
            foreach($coure_students as $student){
                /** @var CourseStudent $student */
                $student_list[] = [
                    'name' => $student->user->full_name ? $student->user->full_name : $student->user->name,
                    'id'   => $student->user->id,
                    'registered' => $student->created_at->toDateTimeString(),
                    'link' => $student->user->showLinkProfile(),
                    'pic' => $student->user->showAvatar()
                ];
            }
        }
        return response()->json(['success' => true, 'students' => $student_list]);
    }

    /**
     * Lấy trạng thái các bài học
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function view_status(Request $request){
        $course_id = $request->get('course_id');
        $user_id = $request->get('user_id', auth()->user()->id);
        /** @var Course $course */
        $course = Course::find($course_id);
        if(!$course){
            abort(404, "Không tìm thấy khóa học");
        }
        $course_contents = $course->getCachedContents();
        $content_ids = [];
        foreach($course_contents as $content){
            /** @var CourseContent $content */
            if($content->get_type() != config('course.content_types.section')){
                $content_ids[] = $content->id;
            }
        }
        //var_dump($content_ids);
        $view_logs = CourseContentViewLog::whereUserId($user_id)->whereIn('course_content_id',$content_ids)->get();
        $cc_status = [];
        foreach($view_logs as $log){
            $cc_status['cc_status_' . $log->course_content_id] = $log->status;
        }

        $progress = (count($cc_status) + array_sum($cc_status))/(count($content_ids) * 2);

        return response()->json([
            'success' => true,
            'status' => $cc_status,
            'progress' => $progress,
        ]);
    }

    public function view_status_log(Request $request){
        $content_id = $request->get('content_id');
        $user_id = $request->get('user_id', auth()->user()->id);
        $token = $request->get('view_token', '');
        $course_content = CourseContent::find($content_id);
        if(!$course_content){// kiem tra khoa hoc
            abort(404, "Không tìm thấy bài học");
        }
        $user = User::find($user_id);
        if(!$user){// kiem tra nguoi dung
            abort(404, "Không tìm thấy người dùng");
        }
        if(myRole($course_content->course, $user) != 'register'){// kiem tra moi lien he giua khoa hoc va nguoi hoc
            return response()->json([
                'success' => true,
                'stop_log' => true
            ]);
        }

        if($course_content->get_type() != config('course.content_types.quizzes')){
            event(new ViewCourseContentLogging($user, $course_content, $token));
        }

        $finished = CourseContentViewLog::whereUserId($user_id)
            ->whereCourseContentId($content_id)
            ->whereStatus(config('course.content_view_status.viewed'))
            ->exists();
        return response()->json([
            'success' => true,
            'finished' => $finished
        ]);
    }

    //Log trạng thái của quizzes
    public function view_status_log_quizzes(Request $request){
        $content_id = $request->get('content_id');
        $user_id    = $request->get('user_id', auth()->user()->id);
        $token      = $request->get('view_token', '');
        $course_content = CourseContent::find($content_id);
        if(!$course_content){// kiem tra khoa hoc
            abort(404, "Không tìm thấy bài học");
        }
        $user = User::find($user_id);
        if(!$user){// kiem tra nguoi dung
            abort(404, "Không tìm thấy người dùng");
        }
        if(myRole($course_content->course, $user) != 'register'){// kiem tra moi lien he giua khoa hoc va nguoi hoc
            return response()->json([
                'success' => true,
                'stop_log' => true
            ]);
        }

        event(new ViewCourseContentLogging($user, $course_content, $token, true));

        return response()->json([
            'success' => true,
            'finished' => true
        ]);
    }

}