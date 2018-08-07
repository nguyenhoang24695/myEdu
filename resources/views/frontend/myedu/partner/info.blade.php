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
				<h2>Hoa hồng hấp dẫn</h2>
				<ul class="media-list info-diskcount">
				  <li class="media">
				    <div class="media-left">
				      <p class="diskcount">
				      	<strong >10<small>%</small></strong>
				      </p>
				    </div>
				    <div class="media-body">
				      <p>Ngay sau khi bạn đăng ký và trở thành Partner level Standard.
								Ngoài ra, bạn nhận 30% hoa hồng khi bạn giới thiệu khóa học của chính bạn.</p>
				    </div>
				  </li>

				  <li class="media">
				    <div class="media-left">
				      <p class="diskcount">
				      	<strong >20<small>%</small></strong>
				      </p>
				    </div>
				    <div class="media-body">
				      <p>
								Ngay sau khi bạn giới thiệu được 2 người bạn đăng ký khóa học có phí và bạn sẽ được nâng lên
								Partner level Gold. Ngoài ra, bạn vẫn nhận 30% hoa hồng khi giới thiệu khóa học của chính bạn.
							</p>
				    </div>
				  </li>

				  <li class="media">
				    <div class="media-left">
				      <p class="diskcount">
				      	<strong >30<small>%</small></strong>
				      </p>
				    </div>
				    <div class="media-body">
				      <p>
								Ngay sau khi bạn giới thiệu được 4 người bạn đăng ký khóa học trả phí và bạn sẽ được nâng lên Partner
								level Diamond. Ngoài ra, bạn vẫn nhận 30% hoa hồng khi giới thiệu khóa học của chính bạn.
							</p>
				      <p>
				      @if (Auth::guest())
				      	<a
									href="{{ route('idvg.login',['uri'=>base64_encode(Request::url())]) }}"
									class="btn btn-primary btn-add-become btn-partner-register">Đăng ký ngay</a>
				      @else
				      	<a href="{{ route('partner.register') }}"
									 class="btn btn-primary btn-add-become btn-partner-register">Đăng ký ngay</a>
				      @endif
				      </p>
				    </div>
				  </li>

				</ul>

				<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
				  <div class="panel panel-partner">
				    <div class="panel-heading" role="tab" id="headingOne">
				      <h4 class="panel-title">
				        <a class="collapse_action"
									 role="button" data-toggle="collapse"
									 data-parent="#accordion"
									 href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
				          <span class="fa fa-plus icon-info"></span> Chi tiết chương trình
				        </a>
				      </h4>
				    </div>
				    <div id="collapseOne" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
				      <div class="panel-body">
								<h4>Xin chào các bạn thành viên.</h4>
								<p>Lời đầu tiên BQT xin chân thành cảm ơn sự ủng hộ, quý mến của các thành viên trong giai đoạn vừa qua, 
									đặc biệt trong giai đoạn mà <b>myedu.com.vn</b> có nhiều khó khăn.</p>
								<p>Như các bạn đã biết, <b>myedu.com.vn</b> là một nền tảng hỗ trợ các nhà phát triển nội dung phát hành các khóa
									học trực tuyến, sự phát triển của <b>myedu.com.vn</b> phụ thuộc phần lớn là nhờ sự ủng hộ của cộng đồng của các
									bạn thành viên, của các nhà sản xuất nội dung. Kế thừa tiếp tục những thành tựu mà <b>myedu.com.vn</b> đã đạt
									được, BQT xin tiếp tục giới thiệu Chương trình Partnership nhằm kết nối và cùng hỗ trợ phát triển đẩy
									mạnh khóa học tới cộng đồng, giúp các nhà phát triển nội dung có thêm động lực cống hiến và các Partner
									có thể gia tăng tối ưu thu nhập từ mạng lưới quan hệ của mình.</p>
								<p><b>Chi tiết chương trình, BQT tóm lược như sau:</b>
								<p>Tại mỗi khóa học có phí, BQT và nhà phát triển nội dung quyết định cắt ra từ 10% - 30% doanh thu
									từ khóa học để tri ân các Partner, tỷ lệ phụ thuộc theo cấp bậc Partner sau đây:</p>
								<ul>
									<li><b class="text-warning">Standard:</b> Đây là level mà các Partner sẽ đạt được ngay sau khi đăng ký
										trở thành Partner và được
										hưởng 10% doanh thu từ các khóa học do Partner giới thiệu, bên cạnh đó các Partner có thể chiết khấu
										cho các học viên của mình trong 10% doanh thu Partner được hưởng để kích thích quyết định tham dự
										khóa học của học viên.</li>
									<li><b class="text-warning">Gold:</b> Đây là level được <b><b>myedu.com.vn</b></b> đánh giá là Partner
										tiềm năng khi giới thiệu được 2 người bạn
										trả phí đầu tiên và được hưởng 20% doanh thu từ các khóa học do Partner giới thiệu, bên cạnh đó các
										Partner có thể chiết khấu cho học viên của mình trong 20% doanh thu Partner được hưởng để kích
										thích quyết định tham dự khóa học của học viên.</li>
									<li><b class="text-warning">Diamond:</b> Đây là level được <b>myedu.com.vn</b> đánh giá là Partner thân
										thiết khi giới thiệu được 5 người
										bạn trả phí đầu tiên và được hưởng 30% doanh thu từ các khóa học do Partner giới thiệu, bên cạnh
										đó các Partner có thể chiết khấu cho học viên của mình trong 30% doanh thu được hưởng để kích
										thích quyết định tham dự khóa học của thành viên.</li>
								</ul>
										<p><i>Chương trình hiện vẫn trong giai đoạn thử nghiệm nên có thể gặp nhiều thiếu sót vì vậy BQT hy
								vọng nhận được những ý kiến đóng góp xây dựng từ các bạn để thay đổi và cải tiến hoàn thiện hơn nữa.</i></p>
								<p>Xin chân thành cảm ơn các bạn và chúc chúng ta thành công.</p>
								<p><b>Trân trọng.</b></p>
				      </div>
				    </div>
				  </div>
				  <div class="panel panel-partner">
				    <div class="panel-heading" role="tab" id="headingTwo">
				      <h4 class="panel-title">
				        <a class="collapsed collapse_action"
									 role="button" data-toggle="collapse"
									 data-parent="#accordion"
									 href="#collapseTwo"
									 aria-expanded="false"
									 aria-controls="collapseTwo">
				          <span class="fa fa-plus icon-info"></span> Cách thức hoạt động
				        </a>
				      </h4>
				    </div>
				    <div id="collapseTwo" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">
				      <div class="panel-body">
									<p>
										<i>+ Bước 1:</i> Bạn đăng ký trở thành Partner với <b>myedu.com.vn</b>
									</p>
									<p>
										<i>+ Bước 2:</i> <b>myedu.com.vn</b> duyệt yêu cầu của bạn trong 2 (hai) ngày làm việc và cung cấp cho bạn mã Code giới thiệu và mở tính năng tạo link giới thiệu tại mỗi khóa học cho bạn.
									</p>
									<p>
										<i>+ Bước 3:</i> Bạn thay đổi mã Code theo sở thích của bạn và cấu hình % bạn chiết khấu cho học viên nếu cần thiết.
									</p>
									<p>
										<i>+ Bước 4:</i> Bạn sử dụng mã Code hoặc Link giới thiệu tặng cho bạn bè, người dùng … của bạn qua bất kỳ kênh nào.
									</p>
									<p>
										<i>+ Bước 5:</i> Người dùng sử dụng mã Code hoặc truy cập qua Link do bạn giới thiệu và mua khóa học.
									</p>
									<p>
										<i>+ Bước 6:</i> <b>myedu.com.vn</b> cộng sang tài khoản của bạn số tiền tương ứng với % bạn được hưởng tại khóa học.
									</p>
									<p>
										<i>+ Bước 7:</i> Bạn rút tiền về tài khoản Ví điện tử Bảo Kim để nhận tại tài khoản ngân hàng hàng tháng.
									</p>

							</div>
				    </div>
				  </div>
				  <div class="panel panel-partner">
				    <div class="panel-heading" role="tab" id="headingThree">
				      <h4 class="panel-title">
				        <a class="collapsed collapse_action"
									 role="button" data-toggle="collapse"
									 data-parent="#accordion"
									 href="#collapseThree" aria-expanded="false"
									 aria-controls="collapseThree">
				          <span class="fa fa-plus icon-info"></span> Câu hỏi thường gặp
				        </a>
				      </h4>
				    </div>
				    <div id="collapseThree" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree">
				      <div class="panel-body">
								<p class="text-info">Khi nào thì Partner được rút tiền và rút qua kênh nào?</p>
								<p>
									<i>MyEdu trả lời:</i> <b>myedu.com.vn</b> sẽ chuyển tiền về ví điện tử Bảo Kim (Trùng với tài khoản của bạn tại <b>myedu.com.vn</b>)
									vào ngày 1-5 tháng tiếp theo khi: Tài khoản của bạn có số dư từ 100,000 VND và bạn tạo lệnh rút tiền.
									Sau khi tiền được chuyển về Bảo Kim, bạn có thể rút về bất kỳ ngân hàng nào mà bạn muốn.</p>
								<p class="text-info">Chương trình Partnership của <b>myedu.com.vn</b> có tin cậy không?</p>
								<p>
									<i>MyEdu trả lời:</i> <b>myedu.com.vn</b> là dự án về giáo dục, mọi tính năng sản phẩm của <b>myedu.com.vn</b>
									đều lấy chữ tín và đạo đức là cốt lõi phát triển.</p>
								<p class="text-info">Chương trình có thực sự hiệu quả không?</p>
								<p>
									<i>MyEdu trả lời:</i>  Hiệu quả của Chương trình còn phụ thuộc vào nhiều yếu tố, trong đó có yếu tố từ
									Partner, chỉ cần các bạn cố gắng thì hiệu quả sẽ đến với bạn.
								</p>
								<p class="text-info">Những ai nên tham gia chương trình này?</p>
								<p>
									<i>MyEdu trả lời:</i> Tất cả các bạn đều tham gia được, đặc biệt là các bạn đang có khóa học, có thương hiệu cá nhân,
									có nhiều bạn bè, có blog, có khả năng thuyết phục …
								</p>
								<p class="text-warning">
									Nếu bạn có thêm câu hỏi khác, xin vui lòng liên hệ với BQT để được giải đáp.
								</p>
								<p>
									Xin chân thành cảm ơn các bạn.
								</p>

				      </div>
				    </div>
				  </div>

				</div>
			</div>
			<div class="col-sm-5 col-md-5 partner-info-right">
				<a href="{{ url('/') }}">
					<img src="{{ url('frontend/img/myedu/grap_partner.png') }}" alt="myedu.com.vn" class="img-responsive">
				</a>
			</div>
		</div>
	</div>
@endsection