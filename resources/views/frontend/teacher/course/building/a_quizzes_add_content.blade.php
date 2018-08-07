@if ($type == "question")

	<li class="item-quiz item_quiz item_quiz_add">
		<form method="POST" action="{{ route('teacher.quizzes.storeQuestion') }}" class="frm_add_question" >
			<div class="input-group">
			  <input 
			  type="text"
			  name="title" 
			  class="form-control input-quiz input_ques" 
			  placeholder="Câu hỏi"
			  autocomplete="off" >
			  <span class="input-group-addon">

			    <i 
			    class="fa fa-trash-o" 
			    data-toggle="tooltip" 
			    data-placement="top" 
			    data-original-title="Xóa câu hỏi"
			    onclick="removeQuestion(this)"></i>

		      </span>
			</div>
			<ul class="list-ans list_ans_add list-unstyled">
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
			<input type="hidden" name="quiz_id" value="{{ $quiz_id }}">
			<button 
			type="button"
			onclick="storeQuestion(this)" 
			class="btn-sm btn btn-primary btn-save btn_save">Thêm mới</button>
			<button 
			type="button"
			onclick="removeQuestion(this)" 
			class="btn-sm btn btn-default btn-cancel btn_cancel">{{trans('common.cancel')}}</button>
		</form>
	</li>

@else

	<li class="item-ans item_ans">
		<div class="input-group">
		  <span class="input-group-addon">
	      	<input 
	      	type="checkbox" 
	      	name="is_true_add[]" 
	      	value="1" 
	      	onclick="setIsTrue(this)">
	      	<input type="hidden" name="correct[]" class="correct">
	      </span>

	      <input 
	      type="text"
	      name="content_add[]" 
	      class="form-control input-quiz input_ans" 
	      placeholder="Đáp án"
	      autocomplete="off">

	      <input 
	      type="text" 
	      name="note_true_add[]"
	      class="form-control input-quiz input-note input_note hide"
	      placeholder="Giải thích lý do đáp án đúng" >

	      <input type="hidden" name="ans_vt[]" >

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
		    onclick="removeAnswer(this)"></i>
		    <i class="fa fa-arrows-alt move move_handle_ans"></i>
	      </span>

	    </div>
	</li>

@endif