<?php
/**
 * Created by PhpStorm.
 * User: hocvt
 * Date: 9/30/15
 * Time: 14:24
 */

namespace App\Commands;


use App\Core\MyStorage;
use App\Models\Attachment;
use App\Models\Course;
use App\Models\CourseContent;
use App\Models\ExternalSource;
use App\Models\Lecture;
use App\Models\MediaContentContract;
use App\Models\User;
use App\Models\Video;
use Illuminate\Contracts\Bus\SelfHandling;

class LectureActions extends Command implements SelfHandling
{
    private $user;
    private $sub_action;
    /** @var  Lecture */
    private $lecture;
    private $course;
    private $course_content;
    private $actions = [
        'add','new',  // thêm
        'update','edit', // sửa
        'delete','remove' // xóa
    ];

    /**
     * Create a new command instance.
     *
     * @param User $user
     * @param Course $course
     * @param CourseContent $course_content
     * @param string $sub_action
     * @param array $data
     */
    public function __construct(User $user, Course $course, CourseContent $course_content = null, $sub_action = '', $data = [])
    {
        //
        //$this->request = $request;
        $this->user = $user;
        $this->sub_action = $sub_action;
        $this->set_data($data);
        $this->course = $course;
        $this->course_content = $course_content;
    }

    /**
     * Execute the command.
     *
     * @throws SubActionNotSupportException
     */
    public function handle()
    {
        //
        if(array_has($this->actions, $this->sub_action)){
            throw new SubActionNotSupportException($this->user, $this->sub_action);
        }

        switch($this->sub_action){
            case 'add':
            case 'new':
                return $this->addLecture();
                break;

            case 'edit':
            case 'update':
                return $this->updateLecture();
                break;

            case 'delete':
            case 'remove':
                return $this->deleteLecture();
                break;

        }

    }

    private function addLecture(){
        $lec_title = $this->get_val('lec_title', '');
        $lec_sub_title = $this->get_val('lec_sub_title', '');
        if($lec_title == ''){
            return $this->buildReturn(false, trans('course.building.error.lecture.title'));
        }
        $this->lecture = Lecture::create([
            'lec_title' => $lec_title,
            'lec_sub_title' => $lec_sub_title,
        ]);
        if($this->lecture){
            return $this->buildReturn(true, trans('course.building.alert.lecture.saved'));
        }
        return $this->buildReturn();
    }

    /**
     * @return array
     * @throws \Exception
     */
    private function updateLecture(){
        $addition = [];
        $this->lecture = Lecture::find($this->get_val('id'));
        if(!$this->lecture){
            return $this->buildReturn(false, 'Unknown error', ['error_code' => 404]);
        }
        // update title
        if($new_title = $this->get_val('lec_title')){
            $this->lecture->lec_title = $new_title;
        }
        // update sub title
        if($new_sub_title = $this->get_val('lec_sub_title')){
            $this->lecture->lec_sub_title = $new_sub_title;
        }
        // update privacy
        if($new_privacy = $this->get_val('access_privacy', 'student')){
            if($this->course_content->access_privacy != $new_privacy){
                $this->course_content->access_privacy = $new_privacy;
                $this->course_content->save();
                \Log::alert(var_export($this->course_content->toArray(), true));

            }
        }

        // add media
        if($this->has_val('add_media')){
            $can_replace = false;
            $media_type = $this->get_val('media.type');
            $media_id = $this->get_val('media.id');
            $media_class = config('course.lecture_types.' . $media_type);
            /** @var MediaContentContract $media_object */
            $media_object = $media_class::find($media_id);
            if(!$media_object){
                abort(404, 'Không tìm thấy media');
            }

            $addition['new_media_id'] = $media_object->id;
            $addition['new_media_type'] = $media_object->get_media_type();

            switch($media_type){
                case 'video':
                case 'audio':
                // video/audio to primary
                    //remove old
                    if($this->lecture->getPrimaryData()){
                        if($can_replace){
                            $this->lecture->getPrimaryData()->removeWhenUpdateLecture($this->lecture->id);
                        }else{
                            throw new \Exception("Một bài giảng chỉ có thể hiển thị 1 video hoặc 1 audio,
                            nếu muốn thêm bạn hãy upload vào mục tập tin đính kèm");
                        }
                    }
                    //assign new
                    $this->lecture->setPrimaryData($media_object);
                    break;
                case 'document':
                // document to secondary
                    // remove old
                    if ($this->lecture->getSecondaryData()) {
                        if ($can_replace) {
                            $this->lecture->getSecondaryData()->removeWhenUpdateLecture($this->lecture->id);
                        } else {
                            throw new \Exception("Một bài giảng chỉ có thể hiển thị 1 tài liệu văn bản,
                            nếu muốn thêm bạn hãy upload vào mục tập tin đính kèm");
                        }
                    }

                    // asign new
                    $this->lecture->setSecondaryData($media_object);
                    break;
            }
        }

        if($this->has_val('remove_media')){
            $media_type = $this->get_val('media.type');
            switch($media_type){
                case 'video':
                case 'audio':
                    $this->lecture->removePrimaryData();
                    break;
                case 'document':
                    $this->lecture->removeSecondaryData();
                    break;
            }
        }

        if($this->has_val('add_attachment')){
            $addition['attachment_added']  = false;
            $attachment = new ExternalSource();

            $attachment->user()->associate(auth()->user());
            $attachment->course_content()->associate($this->course_content);

            $attachment->title = $this->get_val('attachment.title');
            $attachment->source_type = $this->get_val('attachment.source_type');
            $attachment->content = $this->get_val('attachment.content');

            $addition['attachment_added'] = $attachment->save();
            $addition['new_attachment'] = $attachment->attributesToArray();
        }

        if($this->has_val('remove_attachment')){
            $addition['removed'] = ExternalSource::whereUserId(auth()->user()->id)
                ->whereCourseContentId($this->course_content->id)
                ->whereId($this->get_val('attachment_id'))
                ->delete();
        }


        // update another fields



        // save and return
        $check_update = $this->lecture->save();
        if($check_update){
            return $this->buildReturn(true, trans('course.building.alert.lecture.saved'), $addition);
        }
        return $this->buildReturn();
    }

    private function deleteLecture(){
        $this->lecture = Lecture::find($this->get_val('id'));
        if(!$this->lecture){
            return $this->buildReturn(false, 'Unknown error', ['error_code' => 404]);
        }
        $save_check = $this->lecture->delete_content();
        if($save_check){
            return $this->buildReturn(true, trans('course.building.alert.lecture.deleted'));
        }else{
            return $this->buildReturn(false, trans('course.building.error.lecture.delete'));
        }
    }

    /**
     * Tự gen kết quả trả về dạng array
     * @param bool|false $success
     * @param string $message
     * @param array $addition
     * @return array
     */
    private function buildReturn($success = false, $message = 'Unknown error', $addition = []){
        $return = [
                'success' => $success,
                'message' => $message
            ] + $addition;
        if($success == true && $this->lecture != null){
            $return['content'] = $this->lecture;
            $return['id'] = $this->lecture->id;
        }
        return $return;
    }
}