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
         <div class="notification-list">
              
              @if ($notifications->count() == 0)
                <div class="noti-null text-center">
                  <span>Chưa có thông báo nào</span>
                </div>
              @else
                @foreach($notifications as $notification)
                  <div class="item-notify">
                    <a  href="{!! $notification->url_detail() !!}" 
                        rel="nofollow" 
                        class="{{ ($notification->read == 0) ? 'is-read':'' }}">
                      <div class="img-use-noti">
                        <img src="{{ $notification->getObjImage() }}" >
                      </div>
                      <div class="noti-content">
                        <p class="subject">{!! $notification->subject !!}</p>
                        <p class="time">{{ $notification->sent_at }}</p>
                      </div>
                    </a>
                    <div class="option">
                      @if ($notification->read == 0)
                        <span class="icom-check-circle read is_mark"
                              data-toggle="tooltip" 
                              data-placement="top"
                              data-container="body"
                              data-pk = "{{ $notification->id }}"
                              data-type = "read"
                              title="Đánh dấu đã đọc"></span>
                      @endif
                      <span class="icom-times-circle remove is_mark"
                            data-toggle="tooltip" 
                            data-placement="top"
                            data-container="body"
                            data-pk = "{{ $notification->id }}"
                            data-type = "remove"
                            title="Xóa thông báo"></span>
                    </div>
                  </div>
                @endforeach
              @endif
         </div>
         <div class="paginate">
           {!! $notifications->render() !!}
         </div>
      </div>
  </div>
</section>