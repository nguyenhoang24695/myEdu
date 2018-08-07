<?php

namespace App\Commands;

use App\Exceptions\SubActionNotSupportException;
use App\Models\Course;
use App\Models\CourseContent;
use App\Models\Section;
use App\Models\User;
use Illuminate\Contracts\Bus\SelfHandling;

class SectionActions extends Command implements SelfHandling
{
    private $sub_action;
    private $user;
    private $course;
    private $course_content;
    /** @var Section */
    private $section = null;
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
                return $this->addSection();
                break;

            case 'edit':
            case 'update':
                return $this->updateSection();
                break;

            case 'delete':
            case 'remove':
                return $this->deleteSection();
                break;

        }

    }

    /**
     * @return array
     */
    private function addSection(){
        $sec_title = $this->get_val('sec_title', '');
        if($sec_title == ''){
            return $this->buildReturn(false, trans('course.building.error.section.title'));
        }
        $sec_sub_title = $this->get_val('sec_sub_title', '');
        $this->section = Section::create([
            'sec_title' => $sec_title,
            'sec_sub_title' => $sec_sub_title
        ]);
        if($this->section){
            return $this->buildReturn(true, trans('course.building.alert.section.saved'));
        }
        return $this->buildReturn();
    }

    /**
     * @return array
     */
    private function updateSection(){
        $this->section = Section::find($this->get_val('id'));
        if(!$this->section){
            return $this->buildReturn(false, 'Unknown error', ['error_code' => 404]);
        }
        $sec_title = $this->get_val('sec_title', '');
        if($sec_title == ''){
            return $this->buildReturn(false, trans('course.building.error.section.title'));
        }
        $sec_sub_title = $this->get_val('sec_sub_title', '');
        $check_update = $this->section->update([
            'sec_title' => $sec_title,
            'sec_sub_title' => $sec_sub_title
        ]);
        if($check_update){
            return $this->buildReturn(true, trans('course.building.alert.section.saved'));
        }
        return $this->buildReturn();
    }

    /**
     * @return array
     */
    private function deleteSection(){
        $this->section = Section::find($this->get_val('id'));
        if(!$this->section){
            return $this->buildReturn(false, 'Unknown error', ['error_code' => 404]);
        }
        if($this->section->delete_content()){
            return $this->buildReturn(true, trans('course.building.alert.section.deleted'));
        }
        return $this->buildReturn();
    }

    /**
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
        if($success == true && $this->section != null){
            $return['content'] = $this->section;
            $return['id'] = $this->section->id;
        }
        return $return;
    }
}
