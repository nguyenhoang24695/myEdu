<ol class="list-ques-add list-unstyled ques-edit panel_ques list_ques_edit edit_ques_{{ $question->id }}" style="margin-bottom: 15px">
	<li class="item-quiz item_quiz">
		<form method="POST" action="{{ route('teacher.quizzes.updateQuestion') }}" class="frm_edit_question" >
			<div class="input-group">
			  <input 
			  type="text"
			  name="title"
			  value="{{ $question->title }}" 
			  class="form-control input-quiz input_ques" 
			  placeholder="Câu hỏi"
			  autocomplete="off" >
			  <span class="input-group-addon">

			    <i 
			    class="fa fa-trash-o" 
			    data-toggle="tooltip" 
			    data-placement="top" 
			    data-original-title="Xóa câu hỏi"
			    data-ques-id="{{ $question->id }}"
			    onclick="removeQuestion(this)"></i>

		      </span>
			</div>
			<ul class="list-ans list-unstyled list_ans_add sortable_answer">
			    @foreach ($question->answer as $answer)
		    		<li class="item-ans item_ans" data-id="{{ $answer->id }}">
						<div class="input-group">
						  <span class="input-group-addon">
					      	<input 
					      	type="checkbox"
					      	onclick="setIsTrue(this)"
					      	name="is_true_edit[{{ $answer->id }}]" 
					      	value="1" {{ ($answer->is_true == 1) ? "checked":"" }}>
					      </span>

					      <input 
					      type="text"
					      value="{{ $answer->content }}" 
					      name="content_edit[{{ $answer->id }}]" 
					      class="form-control input-quiz input_ans" 
					      placeholder="Đáp án"
					      autocomplete="off">
					      <input 
					      type="text"
					      value="{{ $answer->note }}" 
					      name="note_true_edit[{{ $answer->id }}]" 
					      class="form-control input-quiz input-note input_note {{ ($answer->is_true == 1) ? "":"hide" }}"
					      placeholder="Giải thích lý do đáp án đúng">

					      <input type="hidden" name="ans_vt[]" value="{{ $answer->id }}">

					      <span class="input-group-addon">
					        <i 
					      	class="fa fa-plus"  
					      	data-toggle="tooltip" 
					      	data-placement="top" 
					      	data-original-title="Thêm đáp án"
					      	onclick="addAnswer(this,'no_append')"></i>

						    <i 
						    class="fa fa-trash-o" 
						    data-toggle="tooltip" 
						    data-placement="top" 
						    data-original-title="Xóa đáp án"
						    data-ans-id="{{ $answer->id }}"
						    onclick="removeAnswer(this)"></i>
						    <i class="fa fa-arrows-alt move move_handle_ans"></i>
					      </span>

					    </div>
					</li>
		    	@endforeach
			    <li>
			    	<p 
			    	class="btn btn-default btn-xs btn-add-qs" 
			    	title="Thêm đáp án"
			    	onclick="addAnswer(this,'append')">
			    		<i class="fa fa-plus"></i>
			    		Thêm đáp án
			    	</p>
			    </li>
			</ul>
			<input type="hidden" name="ques_id" value="{{ $question->id }}">
			<button 
			type="button"
			onclick="storeQuestion(this)" 
			class="btn-sm btn btn-primary btn-save btn_save">Cập nhật</button>
			<button 
			type="button"
			onclick="hideFormEditQuestion(this)" 
			class="btn-sm btn btn-default btn-cancel btn_cancel">{{trans('common.cancel')}}</button>
		</form>
	</li>
</ol>