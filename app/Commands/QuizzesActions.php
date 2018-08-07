<?php

namespace App\Commands;

use App\Commands\Command;
use App\Exceptions\SubActionNotSupportException;
use App\Models\Course;
use App\Models\CourseContent;
use App\Models\Quizzes;
use App\Models\User;
use Illuminate\Contracts\Bus\SelfHandling;

class QuizzesActions extends Command implements SelfHandling
{
    private $user;
    private $sub_action;

    /** @var  Quizzes */
    private $quizzes;
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
     * @return void
     */
    public function __construct(User $user, Course $course, CourseContent $course_content = null, $sub_action = '', $data = [])
    {
        $this->user             = $user;
        $this->sub_action       = $sub_action;
        $this->set_data($data);
        $this->course           = $course;
        $this->course_content   = $course_content;
    }

    /**
     * Execute the command.
     *
     * @return void
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
                return $this->addQuizzes();
                break;

            case 'edit':
            case 'update':
                return $this->updateQuizzes();
                break;

            case 'delete':
            case 'remove':
                return $this->deleteQuizzes();
                break;

        }
    }

    private function addQuizzes(){
        $qui_title      = $this->get_val('qui_title', '');
        $qui_sub_title  = $this->get_val('qui_sub_title', '');
        $require        = $this->get_val('require',0);

        if($qui_title   == ''){
            return $this->buildReturn(false, trans('course.building.error.quizzes.title'));
        }
        $this->quizzes  = Quizzes::create([
            'qui_title'     => $qui_title,
            'qui_sub_title' => $qui_sub_title,
            'require'       => $require
        ]);

        if($this->quizzes){
            return $this->buildReturn(true, trans('course.building.alert.quizzes.saved'));
        }
        return $this->buildReturn();
    }

    private function updateQuizzes(){
        $addition = [];
        $this->quizzes = Quizzes::find($this->get_val('id'));

        if(!$this->quizzes){
            return $this->buildReturn(false, 'Unknown error', ['error_code' => 404]);
        }

        $new_title     = $this->get_val('quizzes_title');
        $new_sub_title = $this->get_val('quizzes_sub_title');
        $require       =  $this->get_val('require');

        $this->quizzes->qui_title = $new_title;
        $this->quizzes->qui_sub_title = $new_sub_title;
        $this->quizzes->require = $require;

        // update privacy
        if($new_privacy = $this->get_val('access_privacy', 'student')){
            if($this->course_content->access_privacy != $new_privacy){
                $this->course_content->access_privacy = $new_privacy;
                $this->course_content->save();
                \Log::alert(var_export($this->course_content->toArray(), true));

            }
        }

        // save and return
        $check_update = $this->quizzes->save();
        if($check_update){
            return $this->buildReturn(true, trans('course.building.alert.quizzes.saved'), $addition);
        }
        return $this->buildReturn();
    }

    private function deleteQuizzes(){
        $this->quizzes = Quizzes::find($this->get_val('id'));
        if(!$this->quizzes){
            return $this->buildReturn(false, 'Unknown error', ['error_code' => 404]);
        }
        $save_check = $this->quizzes->delete_content();
        if($save_check){
            return $this->buildReturn(true, trans('course.building.alert.quizzes.deleted'));
        }else{
            return $this->buildReturn(false, trans('course.building.error.quizzes.delete'));
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
        if($success == true && $this->quizzes != null){
            $return['content'] = $this->quizzes;
            $return['id'] = $this->quizzes->id;
        }
        return $return;
    }
}
