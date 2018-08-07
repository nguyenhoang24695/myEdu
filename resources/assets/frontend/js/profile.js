$(function(){


	/* Hiển thị form sửa thông tin */
	$(".edit_info").click(function(event) {
		/* Act on the event */
		$(this).parents("li").find(".show_info").hide();
		$(this).parents("li").find(".update_info").show();
	});

	$(".disabled_update").click(function(event) {
		/* Act on the event */
		$(this).parents("li").find(".update_info").hide();
		$(this).parents("li").find(".show_info").show();
	});
	/*************End****************/

	/**Thay thông tin profile**/
	$(".profile-top-edit").hover(function(){
		$(".update_avata").show();
		$(".update_avata").addClass("animated fadeInUp");

		/*$(".cover").css({background: '#000'});
		$(".cover-pic").css({opacity:0.5});
		$(".update_avata").show();
		$(".update_avata").addClass("animated fadeInUp");
		$(".icon_edit_profile").addClass("animated flash");
		$(".btn_add_cover,.icon_edit_profile").css({visibility:'visible'});*/
		
	},function(){
		$(".update_avata").hide();
		$(".update_avata").removeClass("animated fadeInUp");
		//trở vê mặc định
		/*$(".cover").removeAttr('style');
		//$(".cover-pic").removeAttr('style');
		$(".update_avata").hide();
		$(".update_avata").addClass("animated fadeInUp");
		$(".btn_add_cover,.icon_edit_profile").css({visibility:'hidden'});*/
	});

	$(".update_avata,.btn_add_cover").click(function(){
		$(".up_name,.up_status_text").hide();
		$(".up_cover,.up_avatar").show();
	});

	$(".update-all").click(function(){
		$('.up_cover,.up_avatar,.up_name,.up_status_text').show();
	});

	//Cài đặt nhận thông báo
	$('.input_setting').change(function() {
		var type 	= $(this).attr('data-type');
		var status  = $(this).attr('data-status');
		$.ajax({
			url: '/notification/setting',
			type: 'POST',
			data: {'type': type,'status':status},
		})
		.done(function(data) {
			if(data.success){
				$.notify({
		          message: data.message
		        },{
		          type: 'success'
		        });
			} else {
				$.notify({
		          message: data.message
		        },{
		          type: 'danger'
		        });
			}
		});
		
	});

	$('.updateInfoProfile').ajaxForm({

        success: function(data){
        	if(data.cover != ""){
        		$(".cover_pic").attr({
	        		src: data.cover
	        	});
	        	$( "input[name='cover']" ).val('');
        	}

        	if(data.avatar != ""){
        		$(".avatar_pic").attr({
	        		src: data.avatar
	        	});
	        	$( "input[name='avatar']" ).val('');
        	}

        	if(data.name != ""){
        		$(".is_name").text(data.name);
        	}

        	if(data.status_text != ""){
        		$(".is_status_text").text(data.status_text);
        	}
        	
            $('#profileUpdate').modal('hide');

            //reset lại mặc định
            $(".cover_error,.avatar_error").removeClass('label label-danger');
            $(".cover_error,.avatar_error").removeAttr('style');
            $('.cover_error').text('Kích thước đề nghị tối thiểu 1600x340.');
            $('.avatar_error').text('Kích thước đề nghị tối thiểu 200x200.');
        },
        error: function(data){
            var errors = data.responseJSON;
            $.each( errors , function( key, value ) {
                $("."+key+"_error").text(value);
                $("."+key+"_error").addClass('label label-danger');
                $("."+key+"_error").css("color","#fff");
            });
        }

    });

    $(".edit_discount").click(function(){
    	var obj 	= $(this);
    	obj.hide();
    	obj.next().show();
    	var value 	= obj.next().find('input').val();
    	obj.next().find('input').val('');
    	obj.next().find('input').focus();
    	obj.next().find('input').val(value);
    });

    $(".num_discount").bind('keyup mouseup', function () {
    	var obj 	 = $(this);
    	var type 	 = obj.attr('d-type');
    	var discount = obj.val();
    	if(discount == "" || parseInt(discount) < 0){
    		discount = 0;
    	}
	    if(type == 1){
	    	var enjoy 	 	   = $(".enjoy_1").attr('d-val');
	    	var after_discount = $('.after_discount_1');
	    } else {
	    	var enjoy = $(".enjoy_2").attr('d-val');
	    	var after_discount = $('.after_discount_2');
	    }
	    var rest = parseInt(enjoy) - parseInt(discount);
	    if(rest < 0){
	    	$.notify({
				message: '% sử dụng mã không được vượt quá % bạn được hưởng'
			},{
				type: 'danger'
			});
	    } else {
	    	after_discount.val(rest+'%');
	    }
	});

    $('.canel').click(function(){
    	$(this).parents('.input-group').hide();
    	$(this).parents('.input-group').prev().show();
    });

    $('.save').click(function(){
    	var obj 	= $(this);
    	var value 	= obj.parents('.input-group').find('input').val();
    	var code 	= obj.parents('.input-group').find('input').attr('code');
    	var type 	= obj.parents('.input-group').find('input').attr('d-type');

    	$.ajax({
    		url: '/code/updateDiscount',
    		type: 'POST',
    		data: {'value': value, 'type': type, 'code': code},
    	})
    	.done(function(data) {
    		obj.parents('.input-group').hide();
    		obj.parents('.input-group').prev().show();
    		obj.parents('.input-group').prev().val(value+'%');

    		//thông báo
			$.notify({
				message: data.message
			},{
				type: 'success'
			});
    	})
    	.fail(function(data) {
    		var jsonValue = jQuery.parseJSON( data.responseText )
    		$.notify({
				message: jsonValue.message
			},{
				type: 'danger'
			});
    	});
    	
    });

});

function ViewMoreStatus(obj){
	if($(obj).parents('.text_status').find('.is_status_text').hasClass('ad_more')){
		$(obj).parents('.text_status').find('.is_status_text').removeClass('ad_more');
		$(obj).text('Rút gọn');
	} else {
		$(obj).parents('.text_status').find('.is_status_text').addClass('ad_more');
		$(obj).text('Xem thêm');
	}
}

