<?php
/**
 * Created by PhpStorm.
 * User: hocvt
 * Date: 11/6/15
 * Time: 13:53
 */

namespace App\Http\Controllers\Frontend;


use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseContent;
use App\Models\Note;
use Illuminate\Http\Request;

class NoteController extends Controller
{
    public function getList($id, $content_id, Request $request){

        $action = $request->get('action', 'get');

        list($course, $course_content) = $this->validateAccess($id, $content_id);

        switch($action){
            case 'get':

                $notes = Note::where('content_id', $content_id)
                    ->where('user_id', auth()->user()->id)
                    ->orderBy('created_at', 'desc')
                    ->get();

                return response()->json([
                    'success' => true,
                    'html' => view('includes.lecture_note.list_read_only', ['notes' => $notes])->render()
                ]);
                break;

            case 'create':

                /** @var Note $note */
                $note = new Note();
                $note->user()->associate(auth()->user());
                $note->course_content()->associate($course_content);
                $note->content = '';
                $note->save();
                return response()->json([
                    'success' => true,
                    'note' => [
                        'created_at' => $note->created_at->format('H:i:s d/m/y'),
                        'content' => $note->content,
                        'id' => $note->id,
                    ]
                ]);
                break;

            case 'edit':
                $note_id = $request->get('pk', 0);
                /** @var Note $note */
                $note = Note::find($note_id);

                if(!$note){
                    abort(404, 'Không tìm thấy ghi chú');
                }

                $note->content = $request->get('value', '');

                if($note->save()){
                    return response()->json(['success' => true]);
                }else{
                    return response()->json(['success' => false, 'message' => 'Không sửa được ghi chú.']);
                }

                break;
            case 'delete':
                $note_id = $request->get('id', 0);
                /** @var Note $note */
                $note = Note::find($note_id);

                if(!$note){
                    abort(404, 'Không tìm thấy ghi chú');
                }

                if($note->delete()){
                    return response()->json(['success' => true]);
                }else{
                    return response()->json(['success' => false, 'message' => 'Không sửa được ghi chú.']);
                }

                break;
            default:
                abort(404, 'Không hỗ trợ thao tác.');
        }


    }

    private function validateAccess($id, $content_id){
        $course = Course::find($id);
        if(!$course){
            abort(404, 'Không tìm thấy khóa học');
        }
        $course_content = CourseContent::find($content_id);
        if(!$course_content){
            abort(404, 'Không tìm thấy bài học');
        }
        $my_role = myRole($course);
        if(!in_array($my_role, ['teacher', 'admin', 'register'])){
            abort(401, 'Không có quyền');
        }
        return [$course, $course_content];
    }

    private function getListNote($id, $content_id){

    }

}