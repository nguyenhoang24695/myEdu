@extends('frontend.layouts.default')

@section('content')
	<div class="container-fluid breadcrumb-parter text-center">
		<div class="text-welcome">
			<h4>Tham gia chương trình Partnership của myedu.com.vn để tăng thu nhập thụ động hấp dẫn nhất tại Việt Nam</h4>
			<small>Hàng ngàn Partner của <b>myedu.com.vn</b> đã thành công từ những khách hàng đầu tiên của họ</small>
		</div>
	</div>
	<div class="container main-info-partner">
		<div class="row">
			<div class="col-sm-7 col-md-7 partner-info-left">
			@if (isset($module) && $module == "success")
				<h2>CHÚC MỪNG BẠN ĐÃ ĐĂNG KÝ THÀNH CÔNG</h2>
				<div class="media partner-done">
					<div class="media-left">
				      <span class="done"></span>
				    </div>
				    <div class="media-body">
				      <p>Chúng tôi sẽ duyệt yêu cầu của bạn trong vòng 02(Hai) ngày làm việc.</p>
				      <p>Nếu bạn không nhận được phản hồi từ <b>MyEdu</b>, thì vui lòng liên hệ để được trợ giúp</p>
				      <p><a href="{{ url('/') }}">Quay lại trang chủ</a></p>
				    </div>
				</div>
			@else
				<h2>Bạn vui lòng chia sẻ cho chúng tôi các thông tin sau</h2>
				<form 
				action="{{ route('partner.register') }}" 
				method="POST"
				id="partnership_register_form"
				accept-charset="utf-8">
					<ol class="info_register">
						<li>
							Bạn sẽ giới thiệu các khóa học tại <b>myedu.com.vn</b> bằng phương thức nào?
							<div class="checkbox">
								<label>
									{!! Form::checkbox("marketing_method[]", "marketing_mouth") !!}
									Truyền miệng
								</label>
							</div>
							<div class="checkbox">
								<label>
									{!! Form::checkbox("marketing_method[]", "marketing_website") !!}
									Website
								</label>

								<div class="info-website hidden">
									{!! Form::url('address_website', null, ['class' => 'form-control', 'placeholder' => 'Địa chỉ website chia sẻ']) !!}
									{!! Form::text('views_website', null, ['class' => 'form-control', 'placeholder' => 'Lượt truy cập/ngày của website']) !!}
									@if($errors->has('address_website'))
										<div class="text-warning"> {{$errors->first('address_website')}} </div>
									@endif
									@if($errors->has('views_website'))
										<div class="text-warning"> {{$errors->first('views_website')}} </div>
									@endif
								</div>
								</div>
							<div class="checkbox">
								<label>
									{!! Form::checkbox("marketing_method[]", "marketing_social") !!}
									Mạng xã hội như (Facebook,Google,Twitter ...)
								</label>

								<div class="info-website hidden">
									{!! Form::text('address_social', null, ['class' => 'form-control', 'placeholder' => 'Địa chỉ']) !!}
									@if($errors->has('address_social'))
										<div class="text-warning"> {{$errors->first('address_social')}} </div>
									@endif
								</div>
							</div>
							<div class="checkbox">
								<label>
									{!! Form::checkbox("marketing_method[]", "marketing_ads") !!}
									Qua quảng cáo như (Display, Google Adwwords ...)
								</label>
							</div>
							<div class="checkbox">
								<label>
									{!! Form::checkbox("marketing_method[]", "marketing_other") !!}
									Khác
								</label>

								<div class="info-website hidden">
									{!! Form::text('marketing_other_detail', null, ['class' => 'form-control', 'placeholder' => 'Chi tiết']) !!}
									@if($errors->has('marketing_other_detail'))
										<div class="text-warning"> {{$errors->first('marketing_other_detail')}} </div>
									@endif
								</div>
							</div>
							@if($errors->has('marketing_method'))
								<div class="text-warning"> {{$errors->first('marketing_method')}} </div>
							@endif
						</li>
						<li>Bạn biết tới chương trình Partnership qua kênh nào
							<div class="checkbox ">
								{!! Form::select('access', [
                'Bạn bè' => 'Bạn bè', 'Mạng xã hội' => 'Mạng xã hội', 'Tại myedu.com.vn' => 'Tại myedu.com.vn', 'Khác' => 'Khác'
                ], null, ['class' => 'form-control option-channel', 'style' => 'max-width: 290px;']) !!}
							</div>
							<div class="send_info">
								<button type="submit" class="btn btn-primary">Đăng ký</button>
							</div>
						</li>
				</ol>
				{!! Form::hidden("user_id", auth()->user()->id) !!}
				{!! csrf_field() !!}
				</form>
			@endif

			</div>
			<div class="col-sm-5 col-md-5 partner-info-right">
				<a href="{{ url('/') }}">
					<img src="{{ url('frontend/img/myedu/grap_partner.png') }}" alt="myedu.com.vn" class="img-responsive">
				</a>
			</div>
		</div>
	</div>
@endsection

@section('after-scripts-end')
	<script type="text/javascript">
		$(document).ready(function(){
			$('#partnership_register_form').find('div.checkbox').each(function(i,j){
				var $this = $(this);
				$this.find('input:checkbox').change(function(){
					if(this.checked){
						$this.find('div.info-website').removeClass('hidden');
					}else{
						$this.find('div.info-website').addClass('hidden');
					}
				}).trigger('change');
			});
		});

	</script>
@endsection