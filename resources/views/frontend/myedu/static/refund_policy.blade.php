@extends('frontend.layouts.default')

@section('content')
	<div class="container">
		<div class="row">
			<div class="col-md-3 aside unibee-aside">
				@include('frontend.'.config("app.id").'.includes.sidebar_static')
			</div>

			<div class="col-md-9 profile-private">
				<div class="wrap_main">
					<section>
						<div class="panel panel-money">
							<div class="panel-heading">
								<h4 class="panel-title">
									<span>Chính sách hoàn học phí</span>
								</h4>
							</div>
							<div class="panel-body">
                <h4>Chính sách hoàn học phí</h4>

                <p>MyEdu cam kết mang đến cho học viên những khóa học chất lượng & dịch vụ hỗ trợ tốt nhất thông qua các giờ học trên myedu.com.vn</p>

                <p>Chúng tôi có quy trình hoàn trả học phí nếu như có đơn đề nghị (trong vòng 30 ngày kể từ khi học viên thanh toán học phí và thời lượng hoàn thành khóa học chưa quá 50%) từ quý học viên như sau:</p>

                <h4>Quy trình hoàn học phí</h4>

                <img style="margin: 0 auto" class="img-responsive" src="{{ url('support/ubclass/refund.png') }}" >

							</div>
						</div>
					</section>
				</div>
			</div>

		</div>
	</div>
@endsection