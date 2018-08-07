<aside class="bg-while" style="margin-top: 30px">
	<div class="list-tabs collapse navbar-collapse">
		<div class="list-group">
			<a 
			href="{{ route('payment.guide.module',['module' => 'payment-guide']) }}" 
			class="list-group-item {{ ($module == 'payment-guide') ? 'active':'' }}"><i class="fa fa-info-circle"></i> Hướng dẫn thanh toán</a>

			<a 
			href="{{ route('payment.guide.module',['module' => 'chinh-sach-hoan-hoc-phi']) }}" 
			class="list-group-item {{ ($module == 'chinh-sach-hoan-hoc-phi') ? 'active':'' }}"><i class="fa fa-info-circle"></i> Chính sách hoàn học phí</a>
			
			<a 
			href="{{ route('payment.guide.module',['module' => 'quy-che-hoat-dong']) }}" 
			class="list-group-item {{ ($module == 'quy-che-hoat-dong') ? 'active':'' }}"><i class="fa fa-info-circle"></i> Quy chế hoạt động</a>

			<a 
			href="{{ route('payment.guide.module',['module' => 'chinh-sach-bao-mat-thong-tin']) }}" 
			class="list-group-item {{ ($module == 'chinh-sach-bao-mat-thong-tin') ? 'active':'' }}"><i class="fa fa-info-circle"></i> Chính sách bảo mật thông tin</a>

			<a 
			href="{{ route('payment.guide.module',['module' => 'dieu-khoan-su-dung']) }}" class="list-group-item {{ ($module == 'dieu-khoan-su-dung') ? 'active':'' }}"><i class="fa fa-info-circle"></i> Điều khoản sử dụng</a>
		</div>
	</div>
</aside>