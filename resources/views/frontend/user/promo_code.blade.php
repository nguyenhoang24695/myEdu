@extends('frontend.layouts.default')

@section('after-scripts-end')
  {!! HTML::script('frontend/js/profile.js') !!}
@endsection

@section('content')
<div class="container">
	<div class="row">
		<div class="col-md-3 aside unibee-aside">
	        @include('frontend.user.includes.aside')
	    </div>

	    <div class="col-md-9 profile-private">
	    	<div class="wrap_main">
	    		<section>
	    			<div class="panel unibee-box">
				      <div class="panel-heading notifi-head">
				        <h3 class="panel-title">
				            <span class="title">Mã giới thiệu của bạn</span>
				        </h3>
				      </div>
				      <div class="panel-body no-padding"></div>
				  	</div>
	    		</section>
			  	<div class="panel discount">
			    	<div class="panel-body">
			    		<form action="{{ route('frontend.code.update') }}" method="POST">
			    			<div class="input-group">
							  <span class="input-group-addon" onclick="copyToClipboard('promo_code')" >
							  <i class="fa fa-clipboard"></i> Copy mã code</span>
							  <input 
							  type="text" 
							  class="form-control code"
							  id="promo_code" 
							  value="{{ $code_info->code }}" 
							  maxlength="6" 
							  name="code"  
							  {{ ($code_info->total_edit_code == 1) ? "disabled":"" }}>
							  @if ($code_info->total_edit_code == 0)
								  <input 
								  type="hidden" 
								  name="id" 
								  value="{{ $code_info->id }}">
								  <span class="input-group-addon save-edit-code">
									  <button type="submit" class="btn">
									  	<i class="fa fa-floppy-o"></i>
									  	Lưu
									  </button>
								  </span>
								  {!! csrf_field() !!}
							  @endif
							</div>
			    		</form>
			    		@if ($code_info->total_edit_code == 0)
			    			<small>Để dễ nhớ, bạn được thay đổi mã code 1 lần duy nhất.</small>
			    		@endif

			    		@if ($is_partner)
			    		
						<h3>Cấu hình % Chiết khẩu cho khóa học do bạn giới thiệu.</h3>
						<table class="table">
							<thead>
								<tr>
									<th>Nội dung</th>
									<th>Khóa học của bạn</th>
									<th>Khóa học khác</th>
								</tr>
							</thead>
							<tbody>
								<tr class="row-first">
									<td>% Bạn đang được hưởng</td>
									<td>
										<input 
										type="text" 
										class="form-control enjoy_2"
										d-val="{{ $discount_owner }}" 
										value="{{ $discount_owner }}%"
										disabled>
									</td>
									<td>
										<input 
										type="text" 
										class="form-control enjoy_1"
										d-val="{{ $code_info->discount_max }}" 
										value="{{ $code_info->discount_max }}%"
										disabled>
									</td>
								</tr>
								<tr>
									<td>% Chiết khấu cho người sử dụng mã</td>
									<td>
										<input 
										type="text" 
										class="form-control discount_2 edit_discount"
										value="{{ $code_info->discount_2 }}%">

										<div class="input-group" style="max-width: 200px;display: none">
											<input 
											type="number" 
											class="form-control num_discount"
											code="{{ $code_info->code }}"
											d-type=2 
											value="{{ $code_info->discount_2 }}" 
											>
											<span class="input-group-addon save">
												<i 
												class="fa fa-floppy-o "
												data-toggle="tooltip" 
												data-placement="top" 
												title="Lưu"></i>
											</span>
											<span 
											class="input-group-addon canel">
												<i 
												class="fa fa-times-circle"
												data-toggle="tooltip" 
												data-placement="top" 
												title="Hủy"></i>
											</span>
										</div>

									</td>
									<td>
										<input 
										type="text" 
										class="form-control edit_discount"
										value="{{ $code_info->discount_1 }}%">

										<div class="input-group" style="max-width: 200px;display: none">
											<input 
											type="number" 
											class="form-control num_discount"
											code="{{ $code_info->code }}"
											d-type=1 
											value="{{ $code_info->discount_1 }}" 
											>
											<span class="input-group-addon save">
												<i 
												class="fa fa-floppy-o "
												data-toggle="tooltip" 
												data-placement="top" 
												title="Lưu"></i>
											</span>
											<span 
											class="input-group-addon canel">
												<i 
												class="fa fa-times-circle"
												data-toggle="tooltip" 
												data-placement="top" 
												title="Hủy"></i>
											</span>
										</div>

									</td>
								</tr>
								<tr>
									<td>% Còn lại bạn được hưởng</td>
									<td>
										<input 
										type="text" 
										class="form-control after_discount_2"
										value="{{ $discount_of_owner_after_discount_2 }}%" 
										disabled>
									</td>
									<td>
										<input 
										type="text" 
										class="form-control after_discount_1"
										value="{{ $discount_of_owner_after_discount_1 }}%" 
										disabled>
									</td>
								</tr>
							</tbody>
						</table>
						@else
							<h3>Mã giảm giá của bạn được giảm <strong style="color: #ffaa00">{{ $code_info->discount_max }}%</strong> cho các khóa học</h3>

							<p>Để nâng cấp mã Code và tăng thu nhập hàng tháng tại {{config('app.url')}} Hãy tham gia chương trình Partership của {{config('app.url')}}</p>
                    		<a href="{{ route('partner.info') }}">Khám phá chi tiết ngay</a>
						@endif
			    	</div>
			    </div>
	    	</div>
	    </div>

	</div>
</div>
@endsection