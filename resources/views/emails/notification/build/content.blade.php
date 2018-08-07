{{-- Nội dung gửi email và notify --}}
@if ($tem_type == Config::get('notification.template.user.register_teacher.successful.key'))
	
	<p>
		Bạn đã đăng ký trở thành giảng viên tại {!! config('app.name') !!} thành công. Chúng tôi sẽ duyệt yêu cầu của bạn trong 2 ngày làm việc. 
		Nếu sau 2 ngày làm việc, bạn không nhận được bất cứ phàn hồi nào từ chúng tôi thì vui lòng liên hệ tới BQT để được hỗ trợ. 
	</p>
	<p>Trân trọng.</p>

@elseif ($tem_type == Config::get('notification.template.user.register_teacher.active.key'))
	
	<p>Xin chúc mừng bạn đã trở thành giảng viên tại {!! config('app.name') !!}.</p>
	<p>
		Hãy tạo khóa học đầu tiên để chia sẻ tới cộng đồng, chúng tôi sẽ đem đến cho bạn 10 triệu học viên, thu nhập, danh tiếng và hơn thế nữa. 
	</p>
	<p>Tạo khóa học đầu tiên <a style="color: #ffaa00;text-decoration: none;" href="{{route('teacher.add_new_course')}}" rel="nofollow">tại đây</a>.</p>
	<p>Trân trọng.</p>

@elseif ($tem_type == Config::get('notification.template.user.register_teacher.deactive.key'))
	
	<p>
		Chúng tôi rất tiếc khi thông báo yêu cầu trở thành giảng viên của bạn tại {!! config('app.name') !!} chưa được duyệt 
	</p>
	<p>Lý do: <strong>{{ (isset($reason) ? $reason : '') }}. </strong></p>
	<p>
		Xem lại thông tin đăng ký của bạn <a style="color: #ffaa00;text-decoration: none" href="{{ route('become.teacher').'?module=add' }}" rel="nofollow">tại đây</a>. 
	</p>
	<p>Trân trọng.</p>

@elseif ($tem_type == Config::get('notification.template.user.register_teacher.delete.key'))
	
	<p>Chúng tôi rất tiếc khi thông báo chức danh giáo viên tại {!! config('app.name') !!} của bạn đã bị hủy.</p>
	<p>
		Lý do: <strong>{{ (isset($reason) ? $reason : '') }}. </strong>
		Vui lòng liên hệ với BQT {!! config('app.name') !!}.
	</p>
	<p>Trân trọng.</p>

@elseif ($tem_type == Config::get('notification.template.user.partner.successful.key'))
	
	<p>
		Bạn đã được xét duyệt trở thành Partner của {!! config('app.name') !!}. Hãy tạo thu nhập đầu tiên bằng cách chia sẻ các khóa học hữu ích cho cộng đồng.
	</p>
	<p>Trân trọng.</p>

@elseif ($tem_type == Config::get('notification.template.course.active.key'))

	<p>
		Khóa học: <strong>{{ $course->cou_title }}</strong> của bạn đã được duyệt thành công.
		Hãy tạo thêm khóa học để chia sẻ tới cộng đồng, chúng tôi sẽ đem đến cho bạn 10 triệu học viên, thu nhập, danh tiếng và hơn thế nữa.
	</p>
	<p>Tạo khóa học <a style="color: #ffaa00;text-decoration: none" href="{{route('teacher.add_new_course')}}" rel="nofollow">tại đây</a>.</p>
	<p>Trân trọng.</p>

@elseif ($tem_type == Config::get('notification.template.course.public.key'))

	<p>
		Bạn đã đăng khóa học thành công.
		Chúng tôi sẽ duyệt khóa học của bạn trong 2 ngày làm việc. Nếu sau 2 ngày làm việc, bạn không nhận được bất cứ phàn hồi nào từ chúng tôi thì vui lòng liên hệ tới BQT để được hỗ trợ.
	</p>
	<p>Trân trọng.</p>

@elseif ($tem_type == Config::get('notification.template.course.delete.key'))

	<p>
		Chúng tôi rất tiếc khi thông báo khóa học: <strong>{{ $course->cou_title }}</strong> của bạn tại {!! config('app.name') !!} đã bị xóa
		Lý do: 
		Nếu bạn có bất kỳ thắc mắc nào, xin vui lòng liên hệ BQT để được hỗ trợ.
	</p>
	<p>Trân trọng.</p>

@elseif ($tem_type == Config::get('notification.template.course.noactive.key'))

	<p>
		Chúng tôi rất tiếc khi thông báo khóa học: <strong>{{ $course->cou_title }}</strong> của bạn chưa được duyệt.
		Bạn vui lòng cập nhật lại thông tin để BQT duyệt lại <a style="color: #ffaa00;text-decoration: none" href="{{route('teacher.build_course', ['id' => $course->id])}}" rel="nofollow">tại đây</a>.
	</p>
	<p>Trân trọng.</p>

@elseif ($tem_type == Config::get('notification.template.course.buy.key'))

	<p>
		Bạn đã đăng ký nhập học thành công khóa học
		<strong>{{ $course->cou_title }}</strong>
		Học phí: <strong>{{ $price }}</strong> VND
		Nếu có bất kỳ thắc mắc gì trong quá trình học, bạn vui lòng đặt câu hỏi tại phần thảo luận để được làm sáng tỏ. 
	</p>
	<p>Chúc bạn sớm đạt được mục tiêu trong học tập.</p>
	<p>Trân trọng.</p>

@elseif ($tem_type == Config::get('notification.template.course.register.key'))

	<p>
		Học viên: {{ $buyer->name }} vừa đăng ký tham gia khóa học: <strong>{{ $course->cou_title }}</strong> của bạn.
	</p>
	<p>Học phí bạn nhận được: <strong>{{ $price }}</strong> VND</p>
	<p>Để học viên có trải nghiệm tốt hơn, bạn vui lòng dành ít thời gian để chăm sóc học viên.</p>
	<p>Trân trọng.</p>

@elseif ($tem_type == Config::get('notification.template.course.invite.key'))

	<p>Chúng tôi vui mừng thông báo bạn {{ $buyer->name }} đã đăng ký mua khóa học bằng mã code {{ $pro_code }} của bạn.</p>
	<p>Doanh thu thưởng bạn nhận được: <strong>{{ $price }}</strong> VND</p>
	<p>Để tăng thêm doanh thu thưởng, bạn đừng quên tiếp tục giới thiệu các khóa học hữu ích tới bạn bè.</p>
	<p>Nếu bạn có bất kỳ thắc mắc nào, xin vui lòng liên hệ BQT để được hỗ trợ.</p>
	<p>Trân trọng.</p>

@elseif ($tem_type == Config::get('notification.template.course.review.key'))

	<p>
		Khóa học <strong>{!! $course->cou_title !!}</strong> của bạn được đánh giá.
	</p>
	<p>Đánh giá : <strong>{{ $review->rating }}</strong> sao</p>
	<p>Nội dung : <strong><em>{{ $review->rev_content }}</em></strong></p>

@elseif ($tem_type == Config::get('notification.template.money.recharge.successful.key'))

	<p>
		Bạn đã nạp tiền vào tài khoản {!! config('app.name') !!} thành công.
	</p>
	<p>Số tiền nạp: <strong>{{ $price }}</strong> VND</p>
	<p>Trân trọng.</p>

@elseif ($tem_type == Config::get('notification.template.discussions.question.key'))

	<p>
		Học viên đã gửi những thảo luận tại khóa học <strong><a href="{{ route('frontend.course.registered_view',['slug' => $course->slug]) }}" target="_blank">{{ $course->cou_title }}</a></strong> của bạn
		<em>{{ $discussions->content }}</em>
	</p>
	<p>Để học viên có trải nghiệm tốt hơn, bạn vui lòng dành ít thời gian để chăm sóc học viên.</p>
	<p>Trân trọng.</p>

@elseif ($tem_type == Config::get('notification.template.discussions.reply.key'))

	<p>
		{{ $obj_sender->name }} đã trả lời thảo luận <strong>{!! $discussions->content !!}</strong> tại khóa học <strong><a href="{{ route('frontend.course.registered_view',['slug' => $course->slug]) }}" target="_blank">{{ $course->cou_title }}</a></strong>
	</p>
	<p>Để cuộc thảo luận hấp dẫn hơn, bạn vui lòng dành ít thời gian để phản hồi lại.</p>
	<p>Trân trọng.</p>

@elseif ($tem_type == Config::get('notification.template.discussions.like.key'))

	<p>
		Thảo luận <strong>{!! $discussions->content !!}</strong> của bạn trong khóa học <strong><a href="{{ route('frontend.course.registered_view',['slug' => $course->slug]) }}" target="_blank">{{ $course->cou_title }}</a></strong> đã được yêu thích.
	</p>
	<p>Trân trọng.</p>

@endif
