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
									<span>Điều khoản sử dụng</span>
								</h4>
							</div>
							<div class="panel-body">
                <div>

                  <p  ><span >Về tài khoản sử dụng:
Khi đăng ký tài khoản, người sử dụng (NSD) phải cung cấp đầy đủ và chính xác
thông tin về Tên, Email, Số điện thoại... Đây là những thông tin bắt buộc liên
quan tới việc hỗ trợ NSD trong quá trình sử dụng dịch vụ tại <b >myedu.com.vn</b>. <span >Vì vậy khi
có những rủi ro, mất mát sau này, <b >myedu.com.vn</b>
chỉ tiếp nhận những trường hợp điền đúng và đầy đủ những thông tin trên.</span>
Những trường hợp điền thiếu thông tin hoặc thông tin sai sự thật sẽ không được
giải quyết. Những thông tin này sẽ được dùng làm căn cứ để hỗ trợ giải quyết.</span></p>

                  <p  ><span >Mật khẩu của tài khoản
(MKTK): Sau khi thanh toán, Trong phần quản lý tài khoản, đối với một tài khoản,
NSD sẽ có một mật khẩu. Mật khẩu được sử dụng để đăng nhập vào các website và
các dịch vụ trong hệ thống của <b >myedu.com.vn</b>.
<span >NSD có trách nhiệm phải tự mình bảo quản mật khẩu, nếu mật khẩu
bị lộ ra ngoài dưới bất kỳ hình thức nào, <b >myedu.com.vn</b>
sẽ không chịu trách nhiệm về mọi tổn thất phát sinh.</span></span></p>

                  <p  ><span >Tuyệt đối không sử dụng
bất kỳ chương trình, công cụ hay hình thức nào khác để can thiệp vào các khóa học
của <b >myedu.com.vn</b>. Mọi <span >vi</span>
phạm khi bị phát hiện sẽ bị xóa tài khoản và có thể xử lý theo quy định của
pháp luật.</span></p>

                  <p  ><span >Nghiêm cấm việc phát
tán, truyền bá hay cổ vũ cho bất kỳ hoạt động nào nhằm can thiệp, phá hoại hay
xâm nhập vào dữ liệu của các khóa học trong hệ thống của <b >myedu.com.vn</b>. Nghiêm cấm việc sử dụng <span >chung</span>
tài khoản. Việc trên 2 người cùng sử dụng <span >chung</span> một
tài khoản khi bị phát hiện sẽ bị xóa tài khoản ngay lập tức.</span></p>

                  <p  ><span ><span >Nghiêm cấm việc phát
tán nội dung các bài học trên hệ thống của <b >myedu.com.vn</b>
ra bên ngoài.</span></span><span > Mọi <span >vi</span> phạm khi bị phát hiện sẽ bị xóa
tài khoản và có thể xử lý theo quy định của pháp luật về việc vi phạm bản quyền.</span></p>

                  <p  ><span >Không được có bất kỳ
hành <span >vi</span> nào nhằm đăng nhập trái phép hoặc tìm cách
đăng nhập trái phép cũng như gây thiệt hại cho hệ thống máy chủ của <b >myedu.com.vn</b>. Mọi hành <span >vi</span>
này đều bị xem là những hành vi phá hoại tài sản của người khác và sẽ bị tước bỏ
mọi quyền lợi đối với tài khoàn cũng như sẽ bị truy tố trước pháp luật nếu cần
thiết.</span></p>

                  <p  ><span >Khi giao tiếp với người
dùng khác trong hệ thống dịch vụ của <b >myedu.com.vn</b>,
NSD không được quấy rối, chửi bới, làm phiền hay có bất kỳ hành vi thiếu văn
hoá nào đối với người khác. <span >Tuyệt đối nghiêm cấm việc xúc phạm,
nhạo báng người khác dưới bất kỳ hình thức nào (nhạo báng, chê bai, kỳ thị tôn
giáo, giới tính, sắc tộc….).</span></span></p>

                  <p  ><span >Tuyệt đối nghiêm cấm mọi
hành <span >vi</span> mạo nhận hay cố ý làm người khác tưởng lầm
mình là một người sử dụng khác trong hệ thống dịch vụ của <b >myedu.com.vn</b>. Mọi hành <span >vi</span> vi phạm sẽ bị xử lý
hoặc xóa tài khoản.</span></p>

                  <p  ><span >Khi phát hiện những vi
phạm như vi phạm bản quyền, hoặc những lỗi vi phạm quy định khác, <b >myedu.com.vn</b> có quyền sử dụng những thông
tin mà NSD cung cấp khi đăng ký tài khoản để chuyển cho Cơ quan chức năng giải
quyết theo quy định của pháp luật.</span></p>

                  <p  ><span >Trong những trường hợp
bất khả kháng như chập điện, hư hỏng phần cứng, phần mềm, hoặc do thiên <span >tai .v.v</span>. NSD phải chấp nhận những thiệt hại nếu có.</span></p>

                  <p  ><span >Tuyệt đối nghiêm cấm mọi
hành <span >vi</span> tuyên truyền, chống phá và xuyên tạc chính quyền,
thể chế chính trị, và các chính sách của Nhà nước... Trường hợp phát hiện,
không những bị xóa bỏ tài khoản mà chúng tôi còn có thể cung cấp thông tin của
NSD đó cho các cơ quan chức năng để xử lý <span >theo</span> pháp luật.</span></p>

                  <p  ><span ><span >Tuyệt đối không bàn luận
về các vấn đề chính trị, kỳ thị tôn giao, kỳ thị sắc tộc.</span></span><span > Không có những hành
vi, thái độ làm tổn hại đến uy tín của các sản phẩm, dịch vụ, khóa học trong hệ
thống <b >myedu.com.vn</b> dưới bất kỳ hình thức
nào, phương thức nào. Mọi <span >vi</span> phạm sẽ bị tước bỏ mọi
quyền lợi liên quan đối với tài khoản hoặc xử lý trước pháp luật nếu cần thiết.
Mọi thông tin cá nhân của NSD sẽ được chúng tôi bảo mật, không tiết lộ ra
ngoài. Chúng tôi không bán hay trao đổi những thông tin này với bất kỳ một bên
thứ ba nào khác. Như trên đã nói, mọi thông tin đăng ký của NSD sẽ được bảo mật,
nhưng trong trường hợp cơ quan chức năng yêu cầu, <span >chúng</span>
tôi sẽ buộc phải cung cấp những thông tin này cho các cơ quan chức năng.</span></p>

                  <p  ><b ><span >myedu.com.vn</span></b><span > có toàn quyền xóa, sửa chữa hay thay đổi các dữ
liệu, thông tin tài khoản của NSD trong các trường hợp người đó <span >vi</span> phạm những qui định kể trên mà không cần sự đồng ý của
người sử dụng.</span></p>

                  <p  ><b ><span >myedu.com.vn</span></b><span > có thể thay đổi, bổ sung hoặc sửa chữa thỏa
thuận này bất cứ lúc nào và sẽ công bố rõ trên Website hoặc các kênh truyền
thông chính thức khác của <b >myedu.com.vn</b>.</span></p>

                  <p ><o:p>&nbsp;</o:p></p>

                </div>
							</div>
						</div>
					</section>
				</div>
			</div>

		</div>
	</div>
@endsection