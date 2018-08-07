@extends('frontend.layouts.default')

@section('after-styles-end')
    {!! HTML::style('frontend/css/landingpage.css') !!}
@endsection

@section('content')
	<div class="container">
		<h1>Chọn thông tin về bạn</h1>
		<small>Chúng tôi ước tính doanh thu bạn có thể đạt được hàng tháng</small>
		<div class="row">
		  <div class="col-md-6">
		  	<form class="frm_estimates">
			  <div class="form-group">
			    <label >Bạn dạy môn</label>
			    <button type="button" class="btn_edus btn_edus_primary btn_default">Toán</button>
			    <button type="button" class="btn_edus btn_edus_primary btn_default">Văn</button>
			    <button type="button" class="btn_edus btn_edus_primary btn_default">Anh</button>
			    <select>
			    	<option value="0">Môn học khác</option>
			    	<option value="">Sử</option>
			    	<option value="">Địa</option>
			    	option
			    	option
			    </select>
			  </div>
			</form>
		  </div>

		  <div class="col-md-6">
		  	
		  </div>
		</div>
		

	</div>
@endsection