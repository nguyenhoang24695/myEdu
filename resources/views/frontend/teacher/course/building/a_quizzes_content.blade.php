<div class="a_quizzes_content" data-id="{{$content->id or ''}}">
	<div class="add-ques list_all_ques">
		<ul 
		class="panel-group list-unstyled list-view-ques sortable-question list_view_ques_{{ $content->id }} sortable_question " 
		role="tablist" 
		aria-multiselectable="true">
			@foreach ($content->question as $question)
				@include('frontend.teacher.course.building.a_question_view', ['question' => $question])
			@endforeach
		</ul>

		<ol class="list-ques-add list-unstyled list_ques_add list_ques_add_{{ $content->id }}">
		  <li class="item-quiz text-center">
			<p 
			class="btn btn-default btn-sm btn-add-qs" 
			title="Thêm câu hỏi" 
			onclick="addQuestion(this,{{ $content->id }},'append')">
				<i class="fa fa-plus-square"></i>
				Thêm câu hỏi
			</p>
		  </li>
		</ol>

	</div>
</div>