$(function(){

	//Lọc danh mục khóa học
	$(".c_option").change(function(){
		var url  = $(this).val();
		window.location.href = ''+url+'';
	});

});