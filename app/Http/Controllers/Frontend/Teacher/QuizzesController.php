<?php

namespace App\Http\Controllers\Frontend\Teacher;

use App\Models\Answer;
use App\Models\Question;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class QuizzesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $type       =   $request->get('type');
        $quiz_id    =   $request->get('quiz_id');

        $arr_type   =   ['question','answer'];
        if(in_array($type,$arr_type)){
            $html   =   view('frontend.teacher.course.building.a_quizzes_add_content',compact('type','quiz_id'))->render();
            return response()->json($html);
        } else {
            return response()->json(['success' => false, 'mess' => 'Lỗi không tồn tại type Content']);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $quizzes_id  =  $request->get('quiz_id');
        $title       =  $request->get('title');
        $description =  $request->get('description','');
        $answer_type =  $request->get('answer_type','multiple');

        $answers     =  $request->get('content_add',[]);
        $correct     =  $request->get('correct',[]);
        $note        =  $request->get('note_true_add',[]);

        $this->validate($request, [
            'quiz_id' => 'required',
            'title'   => 'required|max:255'
        ],[
            'quiz_id.required' => 'Không tồn tại ID bài kiểm tra',
            'title.required'   => 'Bạn chưa nhập nội dung câu hỏi'
        ]);

        if(empty($answers) || strlen(implode($answers)) == 0){
            return response()->json([
                'success'    => false,
                'data'       => 'Câu hỏi cần tối thiểu 1 đáp án có nội dung'
            ]);
        }

        if(empty($correct) || !in_array(1,$correct)){
            return response()->json([
                'success'    => false,
                'data'       => 'Bạn chưa chọn đáp án đúng cho câu hỏi'
            ]);
        }

        $question    =  Question::create([
            'title'         => $title,
            'description'   => $description,
            'quizzes_id'    => $quizzes_id,
            'answer_type'   => $answer_type
        ]);

        if($question){
            foreach($answers as $key => $answer){
                if($answer != ""){
                    $question->answer()->create([
                        'content'   => $answer,
                        'is_true'   => isset($correct[$key]) ? $correct[$key] : 0,
                        'note'      => isset($note[$key]) ? $note[$key] : ''
                    ]);
                }
            }

            $html   =   view('frontend.teacher.course.building.a_question_view',compact('question'))->render();
            return response()->json([
                'success'    => true,
                'html'       => $html,
                'quizzes_id' => $quizzes_id
            ]);

        } else {
            return response()->json([
                'success'    => false,
                'data'       => 'Lưu nội dung câu hỏi thất bại'
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
        $id             =   $request->get('ques_id');
        if($id > 0){
            $question   =   Question::find($id);
            return view('frontend.teacher.course.building.a_quizzes_edit_content',compact('question'))->render();
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $title       =  $request->get('title');
        $description =  $request->get('description','');
        $ques_id     =  $request->get('ques_id');

        //Phần đáp án
        $answers     =  $request->get('content_edit',[]);
        $correct     =  $request->get('is_true_edit',[]);
        $note        =  $request->get('note_true_edit',[]); // Lý do đáp án đúng

        $content_add =  $request->get('content_add',[]); //Thêm mới đáp án mới
        $is_true_add =  $request->get('is_true_add',[]); //Đáp án đúng mới
        $note_add    =  $request->get('note_true_add',[]); //Ghi chú đáp án đúng mới

        $ans_move    =  $request->get('ans_move',[]); //Đáp án bị xóa
        $ans_vt      =  $request->get('ans_vt',[]); // Vị trí từng đáp án

        $this->validate($request, [
            'title'   => 'required|max:255'
        ],[
            'title.required'   => 'Bạn chưa nhập nội dung câu hỏi'
        ]);

        if(empty(array_merge($answers,$content_add)) || strlen(implode(array_merge($answers,$content_add))) == 0){
            return response()->json([
                'success'    => false,
                'data'       => 'Câu hỏi cần tối thiểu 1 đáp án có nội dung'
            ]);
        }

        if(empty(array_merge($correct,$is_true_add)) || !in_array(1,array_merge($correct,$is_true_add))){
            return response()->json([
                'success'    => false,
                'data'       => 'Bạn chưa chọn đáp án đúng cho câu hỏi'
            ]);
        }

        $question   =   Question::find($ques_id);
        if($question){
            $question->title        =   $title;
            $question->description  =   $description;
            if($question->save()){

                $queue_vt   =   [];//Vị trí còn bỏ trống sẽ dc cập nhật cho câu hỏi mới
                foreach($ans_vt as $vt  => $id_ans){
                    if($id_ans > 0){
                        if(isset($answers[$id_ans]) && $answers[$id_ans] != ""){
                            $answer_model   =   Answer::where('id',$id_ans)->where('question_id',$ques_id)->first();
                            if($answer_model){
                                $answer_model->content  =   $answers[$id_ans];
                                $answer_model->is_true  =   isset($correct[$id_ans]) ? $correct[$id_ans] : 0;
                                $answer_model->order    =   $vt;
                                $answer_model->note     =   isset($note[$id_ans]) ? $note[$id_ans] : '';
                                $answer_model->save();
                            }
                        }
                    } else {
                        $queue_vt[] =   $vt;
                    }
                }

                //Thêm mới đáp án nếu có
                if(!empty($content_add)){
                    foreach($content_add as $key => $content){
                        if($content != ""){
                            $question->answer()->create([
                                'content'   => $content,
                                'order'     => isset($queue_vt[$key]) ? $queue_vt[$key] : 0,
                                'is_true'   => isset($is_true_add[$key]) ? $is_true_add[$key] : 0,
                                'note'      => isset($note_add[$key]) ? $note_add[$key] : ''
                            ]);
                        }
                    }
                }

                //Xóa các câu hỏi nếu có
                if(!empty($ans_move)){
                    $question->answer()->whereIn('id',$ans_move)->delete();
                }

                $html   =   view('frontend.teacher.course.building.a_question_view',compact('question'))->render();
                return response()->json([
                    'success'    => true,
                    'ques_id'    => $question->id,
                    'html'       => $html
                ]);

            } else {
                return response()->json([
                    'success'    => false,
                    'ques_id'    => $question->id,
                    'data'       => 'Cập nhật câu hỏi thất bại'
                ]);
            }
        }

    }

    public function reorder(Request $request){
        $type        =   $request->get('type');
        $ids         =   $request->get('data');

        $arr_type    =   ['question','answer'];
        if(in_array($type,$arr_type)){
            foreach($ids as $key => $id){
                if($type ==  'question'){
                    $question  = Question::find($id);
                    if($question){
                        $question->order =  $key;
                        $question->save();
                    }
                } else {
                    $answer    = Answer::find($id);
                    if($answer){
                        $answer->order  =   $key;
                        $answer->save();
                    }
                }
            }
        } else {
            return response()->json(['success' => false, 'mess' => 'Lỗi không tồn tại type Order']);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $id             =   $request->get('ques_id');
        if($id > 0){
            $question   =   Question::find($id);
            if($question) {
                $ans    =   $question->answer()->delete();
                if($ans){
                    $question->delete();
                }
            }
        }
    }
}
