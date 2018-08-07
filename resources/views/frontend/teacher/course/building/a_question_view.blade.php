@if ($question)
	<li 
	class="panel panel-default reset-panel panel_ques question_{{ $question->id }}"
	data-id="{{ $question->id }}">
		<div class="panel-heading" 
		role="tab"> 
			<h4 class="panel-title view-tile-ques"> 
				<a 
				role="button" 
				data-toggle="collapse"
				href="#collapse_{{ $question->id }}" 
				class="collapsed">{{ $question->title }}</a>
				<span class="option-ques pull-right">
			        <i 
			      	class="fa fa-pencil"  
			      	data-toggle="tooltip" 
			      	data-placement="top" 
			      	data-original-title="Sửa câu hỏi"
			      	onclick="editQuestion(this,{{ $question->id }})"></i>

				    <i 
				    class="fa fa-trash-o" 
				    data-toggle="tooltip"
				    data-ques-id="{{ $question->id }}"
				    onclick="removeQuestion(this)" 
				    data-placement="top" 
				    data-original-title="Xóa câu hỏi"
				    ></i>

				    <i class="fa fa-arrows-alt move move_handle_ques"></i>
			    </span>
			</h4> 
		</div>
		<div id="collapse_{{ $question->id }}" 
		class="panel-collapse collapse">
			<div class="panel-body">
				@foreach ($question->answer as $answer)
					@if ($answer->is_true == 1)
						<p class="text-success">✔ {{ $answer->content }}</p>
					@else
						<p class="text-muted"> {{ $answer->content }}</p>
					@endif
				@endforeach
			</div>
		</div>
	</li>
@endif