@extends('frontend.layouts.default')

@section('after-styles-end')
	{!! HTML::style('frontend/plugin/summernote/summernote.css') !!}
@endsection

@section('after-scripts-end')
	{!! HTML::script('frontend/plugin/summernote/summernote.js') !!}
	{!! HTML::script('frontend/plugin/summernote/summernote_image_resize.js') !!}
	{!! HTML::script('frontend/js/teacher/course/edit_object.js') !!}
@endsection

@section('content')
	<div class="container become-teacher">
		@if (isset($module) && $module == "add")
			<section>
				<h3 style="font-size:25px;" class="text-center">Đăng ký trở thành giảng viên tại {!! config("app.name") !!}</h3>
				<div class="row">
					<div class="col-md-8 col-md-offset-2">
						<form enctype="multipart/form-data" method="POST" class="form-horizontal frm-add-become" action="{{ route('become.success') }}">
						  <div class="form-group">
						    <label for="inputEmail3" class="col-sm-3 control-label">Ảnh đại diện</label>
						    <div class="col-sm-9">
						      <input type="file" class="form-control" name="avatar">
						      {!! $errors->first('avatar', '<label class="label label-danger">:message</label>') !!}
						    </div>
						  </div>
						  <div class="form-group">
						    <label class="col-sm-3 control-label">Tên hiển thị <span class="req">(*)</span></label>
						    <div class="col-sm-9">
						      <input name="name" type="text" class="form-control" placeholder="Tên hiển thị" value="{{ Request::old('name',Auth::user()->name) }}">
						      {!! $errors->first('name', '<label class="label label-danger">:message</label>') !!}
						    </div>
						  </div>
						  <div class="form-group">
						    <label class="col-sm-3 control-label">Tên đơn vị <span class="req">(*)</span></label>
						    <div class="col-sm-9">
						      <input name="unit_name" type="text" class="form-control" placeholder="Đơn vị công tác" value="{{ Request::old('unit_name',Auth::user()->unit_name) }}">
						      {!! $errors->first('unit_name', '<label class="label label-danger">:message</label>') !!}
						    </div>
						  </div>
						  <div class="form-group">
						    <label class="col-sm-3 control-label">Vị trí <span class="req">(*)</span></label>
						    <div class="col-sm-9">
						      <input name="position" type="text" class="form-control" placeholder="Vị trí" value="{{ Request::old('position',Auth::user()->position) }}">
						      {!! $errors->first('position', '<label class="label label-danger">:message</label>') !!}
						    </div>
						  </div>
						  <div class="form-group">
						    <label class="col-sm-3 control-label">Giới thiệu bản thân <span class="req">(*)</span></label>
						    <div class="col-sm-9">
						      <textarea name="status_text" class="form-control summernote_editor">{{ Request::old('status_text',Auth::user()->status_text) }}</textarea>
						      <small class="note"> - Để tăng uy tín của bản thân, bạn vui lòng giới thiệu chi tiết về cá nhân như. Học vị, chức vụ, kỹ năng đặc biệt ...</small>
						      {!! $errors->first('status_text', '<label class="label label-danger">:message</label>') !!}
						    </div>
						  </div>
						  <div class="form-group">
						    <label class="col-sm-3 control-label">Thành tích đạt được</label>
						    <div class="col-sm-9">
						      <textarea name="achievement" class="form-control summernote_editor">{{ Request::old('achievement',Auth::user()->achievement) }}</textarea>
						      <small class="note"> - Thành tích cá nhân là dẫn chứng quan trọng để học viên quyết định có chọn khóa học của bạn hay không.</small>
						      {!! $errors->first('achievement', '<label class="label label-danger">:message</label>') !!}
						    </div>
						  </div>
						  <div class="form-group">
						    <div class="col-sm-offset-3 col-sm-9 text-center">
						      <button type="buton" class="btn btn-primary">Đăng ký</button>
						      <a href="{{ url('/') }}" class="btn btn-link">Hủy</a>
						    </div>
						  </div>
						  {!! csrf_field() !!}
						</form>
					</div>
				</div>
			</section>

		@elseif($module == "success")
			<section class="become-success">
				<div class="row">
					<div class="col-md-8 col-md-offset-2">
						<div class="media">
							<a class="pull-left icon-all icon-success-become" href="{{ url('/') }}"></a>
							<div class="media-body">
								<h5 class="media-heading">Bạn Đã gửi yêu cầu thành công.</h5>
								<p>Chúng tôi sẽ duyệt yêu cầu của bạn trong 2 ngày làm việc.</p>
								<p>Nếu bạn không nhận được bất kỳ phản hồi nào từ chúng tôi hãy vui lòng liên hệ sớm nhất để được hỗ trợ.</p>
								<p>
								<p><a href="{{ url('/') }}">Click  tại đây</a> trở về trang chủ.</p>
							</div>
						</div>
					</div>	
				</div>	
			</section>
		@else
			<section class="text-center">
				<h3>Đăng ký trở thành giảng viên tại nền tảng giáo dục trực tuyến lớn nhất Việt Nam</h3>
				<p>{!! config("app.name") !!} sẽ mang đến cho bạn hơn 10 triệu học viên, thu nhập, danh tiếng và hơn thế nữa</p>
				<p>
					@if (Auth::guest())
						<a 
						href="{{ route('idvg.login',['uri'=>base64_encode(Request::url())]) }}" 
						class="btn btn-primary btn-become">Đăng ký trở thành giảng viên (<small>miễn phí</small>)</a>
					@else
						<a href="{{ route('become.teacher',['module' => 'add']) }}" class="btn btn-primary btn-become">Đăng ký trở thành giảng viên (<small>miễn phí</small>)</a>
					@endif
				</p>

				<div class="row">
					<div class="col-md-10 col-md-offset-1 step_become">
						<h4>{!! config("app.name") !!} hoạt động như thế nào</h4>
						<ul class="nav list-unstyled">
						    <li>
						    	<p class="icon-all icon-u-lag"></p>
						    	<p>Bạn đăng ký trở thành giảng viên tại {!! config("app.name") !!}</p>
						    </li>
						    <li>
						    	<p class="icon-all icon-camera-lg"></p>
						    	<p>{!! config("app.name") !!} hỗ trợ bạn sản xuất bài giảng miễn phí</p>
						    </li>
						    <li>
						    	<p class="icon-all icon-loa-lg"></p>
						    	<p>Bài giảng của bạn được {!! config("app.name") !!} quảng bá tới hơn 10 triệu học viên</p>
						    </li>
						    <li>
						    	<p class="icon-all icon-dola-lg"></p>
						    	<p>Bạn nhận <strong>35% - 70%</strong> doanh thu từ bài giảng hàng tháng</p>
						    </li>
						</ul>
					</div>
				</div>
			</section>
		@endif
	</div>
@endsection