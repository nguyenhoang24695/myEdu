<section>
	<div class="panel unibee-box">
      <div class="panel-heading notifi-head">
        <h3 class="panel-title">
            <span class="title">Thông báo của bạn</span>
            <a href="{{ url('/dashboard/notification_setting') }}" title="Cài đặt nhận thông báo từ Unibee">
              <span class="icom-cog"></span>
              Tùy chỉnh
            </a>
        </h3>
      </div>
      <div class="panel-body no-padding"></div>
	</div>

	<div class="panel">
		<div class="panel-body">
			 <div class="notification-detail">
			 	<div class="item-notify">
			 		<div class="noti-content">
	                    <h4 class="subject">{!! $notify_detail->subject !!}</h4>
	                    <p class="time">{{ $notify_detail->sent_at }}</p>
	                    <div class="body" style="margin: 10px 0px 0px 0px">
	                    	{!! $body_detail !!}
	                    </div>
	                </div>
			 	</div>
			 </div>
		</div>
	</div>
</section>