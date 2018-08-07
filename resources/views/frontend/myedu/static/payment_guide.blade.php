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
                  <span>Hướng dẫn thanh toán</span>
                </h4>
              </div>
              <div class="panel-body">
                <div>

                  <h4><a name="_dbvayxmvs4gx"></a><b><span>CÁCH THỨC MUA KHÓA HỌC</span></b></h4>

                  <p><span>Trước tiên, bạn hãy tạo một tài khoản cho mình bằng cách
chọn <b>“Đăng kí”</b></span></p>

                  <p>
                    <img class="img-responsive" src="{{ url('support/ubclass/dk.png') }}" >
                  </p>

                  <p><span><span>Bạn có thể đăng kí bằng Facebook, Google + hoặc tạo tài
khoản trên hệ thống.</span></span><span> <span>Trong
các phương thức này, Facebook và Google + được xem là cách thức đăng kí đơn giản
nhất.</span></span></p>

                  <p>
                    <img style="margin: 0 auto" class="img-responsive" src="{{ url('support/ubclass/frm_dk.png') }}" >
                  </p>

                  <p><b><span>B1:</span></b><span> Sau khi tạo được tài khoản cho mình, bạn hãy click vào
khóa học bạn muốn tham dự</span></p>

                  <p>
                    <img style="margin: 0 auto" class="img-responsive" src="{{ url('support/ubclass/course_demo.png') }}" >
                  </p>

                  <p><b><span>B2:</span></b><span> Click vào <b>“Tham dự
khóa học”</b></span></p>

                  <p>
                    <img style="margin: 0 auto" class="img-responsive" src="{{ url('support/ubclass/public_course.png') }}" >
                  </p>

                  <p><span><span>Lúc này bạn sẽ thấy giao diện hiện lên Giá khóa học, giảm
giá và giá sau giảm giá.</span></span></p>

                  <p>
                    <img style="margin: 0 auto" class="img-responsive" src="{{ url('support/ubclass/buy_course.png') }}" >
                  </p>

                  <p><span>Vào những khoảng thời gian ưu đãi học phí, MyEdu sẽ có mã
chiết khấu học phí hoặc bạn có thể có được mã này nếu bạn là một là một khách
hàng thân thiết của MyEdu, bạn nhập mã và click <b>“Áp dụng”</b>, học phí sẽ được tự động chiết khấu. <span>Lúc
này học phí bạn cần hoàn tất chính là học phí sau chiết khấu.</span></span></p>

                  <p><b><span>B3:</span></b><span> Sau khi đã hoàn tất thủ tục ở <b>B2</b>, bạn click vào <b>nạp tiền
mua khóa học</b>, lúc này hệ thống sẽ có giao diện sau</span></p>

                  <p>
                    <img style="margin: 0 auto" class="img-responsive" src="{{ url('support/ubclass/pop_up_nap_tien.png') }}" >
                  </p>

                  <p><b><span>B4:</span></b><span> Lựa chọn phương thức thanh toán</span></p>

                  <p><span>Theo như giao diện, bạn sẽ nhìn thấy có 3 cách thức thanh
toán: <b><i>Thẻ điện thoại</i></b>, <b><i>thẻ ngân hàng</i></b>, <b><i>chuyển khoản</i></b>.</span></p>

                  <p><b><span>1. Đối với hinh thức
thẻ điện thoại</span></b></p>

                  <p><span><span>• Bạn Chọn loại thẻ và nhập mã thẻ và số sê-ri vào ô trống
và chọn nút <b>Nạp tiền</b>.</span></span></p>

                  <p><span><span>• Nếu bạn có nhiều thẻ cào, bạn có thể lặp lại thao tác
này nhiều lần.</span></span></p>

                  <p><i><span>Phí nạp thẻ 0% cho nhà
mạng (VD: nạp thẻ 100.000đ sẽ được cộng 100.000đ vào tài khoản)</span></i></p>

                  <p><span>Do vậy, khi lựa chọn hình thức này, <span>số</span>
tiền thẻ bạn cần nạp sẽ lớn hơn học phí của khóa học.</span></p>

                  <p><b><span>2. Đối với hình thức
thẻ ngân hàng</span></b></p>

                  <p><span><span>Điều kiện để sử dụng hình thức này là tài khoản thẻ của bạn
cần đăng kí dịch vụ thanh toán online qua thẻ ATM.</span></span></p>

                  <p><span><span>Một lưu ý cho bạn là Internet Banking và ATM Online là 2 dịch
vụ hoàn toàn khác nhau.</span></span></p>

                  <p><span>Internet Banking nghĩa là bạn đăng nhập vào website ngân
hàng bên bạn (với tên đăng nhập và mật khẩu do ngân hàng cấp) để bạn tiến hành
lập giao dịch chuyển khoản.</span></p>

                  <p><span>Còn ATM Online thì bạn chỉ cần điền một số thông tin ghi
trên thẻ ATM của bạn là bạn có thể thanh toán ngay chứ bạn không cần vào
website ngân hàng bên bạn.</span></p>

                  <p><span>Sau khi điền các thông tin cần thiết trong form, hệ thống
sẽ tự động chuyển bạn về giao diện sau. <span>Tại đây, bạn chọn
ngân hàng thẻ ATM bạn đang dùng để thanh toán.</span></span></p>

                  <p>
                    <img style="margin: 0 auto" class="img-responsive" src="{{ url('support/ubclass/the_ngan_hang.png') }}" >
                  </p>

                  <p><span>Sau khi chọn Ngân hàng, hệ thống sẽ dẫn bạn về trang để điền
các thông tin thẻ bạn có. Bạn cần điền đầy đủ thông tin và chọn Tiếp tục. <span>Nếu thẻ của bạn hợp lệ, bạn sẽ thanh toán thành công.</span></span>
                  </p>

                  <p>
                    <img style="margin: 0 auto" class="img-responsive" src="{{ url('support/ubclass/smart_link.png') }}" >
                  </p>

                  <p><b><span>3. Đối với hình thức
thanh toán qua chuyển khoản</span></b></p>

                  <p><span>Hiện tại MyEdu có 3 tài khoản để bạn lựa chọn chuyển khoản:
<b><i>Vietcombank</i></b>,
<b><i>Techcombank</i></b>,
<b><i>VPbank</i></b>.</span></p>

                  <p><span>Sau khi lựa chọn tài khoản thuận tiện cho giao dịch của bạn,
bạn thực hiện điền đầy đủ thông tin và chọn <b>“Nạp tiền”</b></span></p>

                  <p>
                    <img style="margin: 0 auto" class="img-responsive" src="{{ url('support/ubclass/chuyen_khoan.png') }}" >
                  </p>

                  <p><span>Hệ thống sẽ hiện lên những thông tin về tài khoản của
MyEdu và các nội dung chi tiết hướng dẫn bạn thực hiện chuyển khoản.</span></p>

                  <h4><a name="_yhdxp5oqa5mp"></a><b><span>CÁCH THỨC NẠP TIỀN MUA KHÓA HỌC</span></b></h4>

                  <p><span><span>Cách thức nạp tiền mua khóa học có khác gì so với cách nạp
tiền để thanh toán khóa học?</span></span></p>

                  <p><span><span>Cách thức nạp tiền mua khóa học được hiểu rằng bạn sẽ nạp
tiền vào tài khoản của bạn (Có vai trò như một chiếc ví điện tử) và khi bạn thực
hiện mua khóa học, bạn sẽ chi tiền từ tài khoản này để có thể mua được khóa học.</span></span></p>

                  <p><span><span>Vì vây, nạp tiền mua khóa học chỉ là bước nạp thêm tiền
vào tài khoản của bạn.</span></span><span> <span>Để
có thể học được khóa học, bạn cần thực hiện thêm thao tác mua khóa học.</span></span></p>

                  <p><span>Các thao tác nạp tiền mua khóa học rất đơn giản, bạn chỉ cần
click vào “Nạp tiền mua khóa học” ở góc phải màn hình. <span>Lúc
này hệ thống sẽ đưa ra 3 phương thức thanh toán giống như phương thức mua khóa
học, bạn chỉ cần thao tác tương tự.</span></span></p>

                  <p>
                    <img style="margin: 0 auto" class="img-responsive" src="{{ url('support/ubclass/nap_tien.png') }}" >
                  </p>

                  <p><span><span>Sau khi hoàn tất các thủ tục đăng kí khóa học, lúc này bạn
đã có thể truy cập vào các bài học không giới hạn thời gian và không mất thêm một
khoản chi phí nào.</span></span><span> Đối với khóa học trực tuyến,
bạn có thể xem đi xem lại một cách chi tiết những nội dung chưa hiểu và ôn tập
lại bất cứ khi nào bạn cần.</span></p>

                  <p><span>Nếu trong quá trình thực hiện thao tác, bạn gặp bất cứ một
vấn đề nào, bạn có thể liên hệ với MyEdu theo số hotline:<b>04 6293 9998</b> hoặc email: </span><a
                      href="mailto:support@myedu.com.vn"><span>support@myedu.com.vn</span></a><span> <span>trong giờ hành chính từ thứ 2 đến thứ 6 để được hỗ trợ kịp thời.</span></span>
                  </p>

                  <p><span><span>Một lần nữa, cảm ơn bạn đã lựa chọn khóa học của MyEdu,
chúc bạn có những giờ học thật bổ ích và hiệu quả.</span></span></p>

                  <p>
                    <o:p>&nbsp;</o:p>
                  </p>

                </div>
              </div>
            </div>
          </section>
        </div>
      </div>

    </div>
  </div>

@endsection