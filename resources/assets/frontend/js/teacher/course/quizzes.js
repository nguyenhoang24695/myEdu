$(function(){
	
});

function sortableQuestion(){
	$(".sortable_question").sortable({
		group: 'no-drop',
		handle: '.move_handle_ques',
		onDrop: function ($item, container, _super) {

			var new_order = [];
			$item.parent('ul').find('li').each(function(){
		        var id = 0;
		        if(id = $(this).attr('data-id')){
		            new_order.push(id);
		        }
		    });

		    updateNewOrder('question',new_order);

			_super($item, container);
		}
	});
}

function sortableAnswer(){
	$(".sortable_answer").sortable({
		handle: '.move_handle_ans'
	});
}

function updateNewOrder(type,array_id){
	$.ajax({
		url: '/teacher/quizzes/updateOrder',
		type: 'POST',
		data: {type: type , data: array_id},
	})
	.done(function() {
		console.log("success");
	});
}

function addQuestion(obj,quizzes_id,append){
	var obj   =	$(obj);

	$.ajax({
		url: '/teacher/quizzes/addContent',
		type: 'POST',
		data: {type: 'question', quiz_id : quizzes_id},
	})
	.done(function(html) {

		if(obj.parents('ol').find('li.item_quiz_add').length == 0){
		
			if(append == 'append'){
				$(html).insertBefore(obj.parent('li'));
				obj.parent('li').prev('li').find('.input_ques').focus();
			} else {
				$(html).insertAfter(obj.parents('li.item_quiz'));
				obj.parents('li.item_quiz').next('li').find('.input_ques').focus();
			}

			//Set lại attr plaholder cho input câu hỏi
			updatePlaceholderQuestion(obj);
			ajaxFormQuestion();
			$('[data-toggle="tooltip"]').tooltip();
			////END////

		} else {
			obj.parent('li').prev('li').find('.input_ques').focus();
		}

	});
}

function editQuestion(obj,ques_id){
	var obj   =	$(obj);
	$.ajax({
		url: '/teacher/quizzes/editContent',
		type: 'POST',
		data: {ques_id : ques_id},
	})
	.done(function(html) {
		$(".list_ques_edit").remove();
		$(".panel_ques").show();
		obj.parents('.panel_ques').hide();
		$(html).insertAfter(obj.parents('.panel_ques'));
		sortableAnswer();
		ajaxFormEditQuestion();
		$('[data-toggle="tooltip"]').tooltip();
	});
}

function hideFormEditQuestion(obj){
	var obj   	=	$(obj);
	var is_obj 	= 	obj.parents('.list_ques_edit');
	is_obj.prev().show();
	obj.parents('.list_ques_edit').remove();
}

function storeQuestion(obj){
	var obj   =	$(obj);
	obj.parents('form').submit();
}

function ajaxFormQuestion(){
	var optionsStoreQuestion = {
		beforeSubmit: validateStoreQuestion,
        success:   responseStoreQuestion,
        timeout:   3000 
    };
    $('.frm_add_question').ajaxForm(optionsStoreQuestion);
}

function ajaxFormEditQuestion(){
	var optionsStoreQuestion = {
		beforeSubmit: validateEditQuestion,
        success:   responseEditQuestion,
        timeout:   3000 
    };
    $('.frm_edit_question').ajaxForm(optionsStoreQuestion);
}

function validateStoreQuestion(formData, jqForm, options){
	
	var form = jqForm[0]; 
    if (!form.title.value) { 
        $.notify({message: 'Bạn chưa nhập nội dung câu hỏi'}, {type: 'danger'}); 
        return false; 
    }

    //Lấy value đáp án
    var answer  = $("input[name='content_add[]']")
              .map(function(){return $(this).val();}).get();


    //Lấy value đáp án đúng
    var is_true = [];
    $("input[name='is_true_add[]']").map(function(){

      	if($(this).is(":checked")){
      		is_true.push($(this).val());
      	} else {
      		is_true.push(0);
      	}

    }).get();

    //Validate
    if(answer.isNull()){
    	$.notify({message: 'Câu hỏi cần tối thiểu 1 đáp án có nội dung'}, {type: 'danger'}); 
        return false;
    }

    if(is_true.indexOf('1') <= -1){
    	$.notify({message: 'Bạn chưa chọn đáp án đúng cho câu hỏi'}, {type: 'danger'}); 
        return false;
    }

    for(var i=0; i<is_true.length; i++) {
	    if(is_true[i] > 0 && answer[i] === ""){
	    	$.notify({message: 'Bạn chưa nhập nội dung cho đán án đúng'}, {type: 'danger'}); 
	    	return false;
	    }
	}

	return true;
}

function validateEditQuestion(formData, jqForm, options){
	var form = jqForm[0]; 
    if (!form.title.value) { 
        $.notify({message: 'Bạn chưa nhập nội dung câu hỏi'}, {type: 'danger'}); 
        return false; 
    }

    return true;
}

function responseStoreQuestion(data){
	if (data.success == false) {
		$.notify({message: data.data}, {type: 'danger'}); 
	} else {
		$('.list_view_ques_' + data.quizzes_id).append(data.html);
		$('.list_ques_add_' + data.quizzes_id).find('li.item_quiz').remove();
		$.notify({message: 'Thêm mới câu hỏi thành công'}, {type: 'success'}); 
	}
}

function responseEditQuestion(data){
	
	if (data.success == false) {
		$.notify({message: data.data}, {type: 'danger'}); 
	} else {
		$('.question_' + data.ques_id).show();
		$('.question_' + data.ques_id).html($(data.html).filter('.question_' + data.ques_id).html());
		$('.edit_ques_' + data.ques_id).remove();
		$.notify({message:'Cập nhật nội dung câu hỏi thành công'}, {type: 'success'}); 
	}
}

Array.prototype.isNull = function (){
    return this.join().replace(/,/g,'').length === 0;
};

function setIsTrue(obj){
	var obj   =	$(obj);
	if(obj.is(":checked")){
		obj.parent('span').find('.correct').val(1);
		obj.parents('.input-group').find('.input_note').removeClass('hide');
	} else {
		obj.parent('span').find('.correct').val(0);
		obj.parents('.input-group').find('.input_note').addClass('hide');
	}
}

function addAnswer(obj,append){
	var obj   =	$(obj);

	$.ajax({
		url: '/teacher/quizzes/addContent',
		type: 'POST',
		data: {type: 'answer'},
	})
	.done(function(html) {
		if(append == 'append'){
			$(html).insertBefore(obj.parent('li'));
			obj.parent('li').prev('li').find('.input_ans').focus();
		} else {
			$(html).insertAfter(obj.parents('li.item_ans'));
			obj.parents('li.item_ans').next('li').find('.input_ans').focus();
		}

		//Set lại attr plaholder cho input đáp án
		updatePlaceholderAnswer(obj);
		$('[data-toggle="tooltip"]').tooltip();
		////END////

	});
}

function removeAnswer(obj){
	var obj    	= $(obj);
	var ans_id 	= obj.attr('data-ans-id');
	var theForm	= obj.parents('.frm_edit_question');
	
	if(ans_id > 0){
		addHidden(theForm, 'ans_move[]', ans_id);
		obj.parents('li.item_ans').remove();
	} else {
		var is_obj = obj.parents('.list_ans_add');
		obj.parents('li.item_ans').remove();
		updatePlaceholder(is_obj, 'input_ans', 'Đáp án');
	}

}

function removeQuestion(obj){
	var obj    	= $(obj);
	var ques_id = obj.attr('data-ques-id');
	if (ques_id > 0) {
		bootbox.confirm("Bạn muốn xóa câu hỏi này ?", function(result){
			if(result){
				$.post('/teacher/quizzes/deleteQuestion', {ques_id: ques_id}, function(data, textStatus, xhr) {
					obj.parents('.panel_ques ').remove();
					$.notify({message: 'Xóa câu hỏi thành công'}, {type: 'success'}); 
				});
			}
		});
	} else {
		var is_obj 	= obj.parents('.list_ques_add');
		obj.parents('li.item_quiz').remove();
		updatePlaceholder(is_obj, 'input_ques', 'Câu hỏi');
	}
}

function updatePlaceholderAnswer(obj){
	var obj_ques = obj.parents(".list_ans_add");
	updatePlaceholder(obj_ques, 'input_ans', 'Đáp án');
}

function updatePlaceholderQuestion(obj){
	var obj_ques = obj.parents(".list_ques_add");
	updatePlaceholder(obj_ques, 'input_ques', 'Câu hỏi');
}

function updatePlaceholder(obj, class_find, value){
	obj.find('li').find('.'+class_find).each(function( index ) {
	  $(this).attr('placeholder', value + ' ' + parseInt(index+1));
	});
}

function addHidden(theForm, key, value) {
    var input = document.createElement('input');
    input.type = 'hidden';
    input.name = key;
    input.value = value;
    theForm.append(input);
}