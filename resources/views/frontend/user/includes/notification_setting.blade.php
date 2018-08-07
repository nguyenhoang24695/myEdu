<section>
	<div class="panel unibee-box">
      <div class="panel-heading notifi-head">
        <h3 class="panel-title">
            <span class="title">Cài đặt thông báo</span>
            <a href="{{ url('/dashboard/notification') }}" title="Danh sách thông báo">
              <span class="icom-cog"></span>
              Danh sách thông báo
            </a>
        </h3>
      </div>
      <div class="panel-body no-padding"></div>
  	</div>
  	<div class="panel">
  		<div class="panel-body">
  			<div class="setting">
  				<div class="notification_setting list-unstyled">
              
              <div class="form-group">
                <label class="title"><i class="fa fa-bell-o"></i>Nhận thông báo.</label>
              </div>
              @if ($notify_setting->count() >0)
                @foreach ($notify_setting as $setting)
                  @if ($setting->notify_type == 'message')
                    <div class="checkbox">
                      <label class="checkbox txt-enable">
                        <input 
                        type="checkbox"
                        data-type = "message"
                        data-status = "enable_profile"
                        class="input_setting" 
                        {{ ($setting->enable_profile == 1) ? 'checked':'' }} > Nhận thông báo từ BQT
                      </label>
                    </div>
                  @endif
                @endforeach
              @else
                <div class="checkbox">
                  <label class="checkbox txt-enable">
                    <input 
                    type="checkbox"
                    data-type = "message"
                    data-status = "enable_profile"
                    class="input_setting" 
                    checked> Nhận thông báo từ BQT
                  </label>
                </div>
              @endif

              <div class="form-group">
                <label class="title"><i class="icom-envelope-o"></i>Nhận Email.</label>
              </div>
              @if ($notify_setting->count() >0)
                @foreach ($notify_setting as $setting)
                  @if ($setting->notify_type == 'message')
                    <div class="checkbox">
                      <label class="checkbox txt-enable">
                        <input 
                        type="checkbox"
                        data-type = "message"
                        data-status = "enable_email"
                        class="input_setting" 
                        {{ ($setting->enable_email == 1) ? 'checked':'' }} > Nhận thông báo từ BQT
                      </label>
                    </div>
                  @endif
                @endforeach
              @else
                <div class="checkbox">
                  <label class="checkbox txt-enable">
                    <input 
                    type="checkbox"
                    data-type = "message"
                    data-status = "enable_email"
                    class="input_setting"
                    checked > Nhận thông báo từ BQT
                  </label>
                </div>
              @endif
              <div class="form-group form-group-btn">
                <button type="submit" class="btn btn-primary btn-sm ">Lưu cài đặt</button>
              </div>

          </div>
  			</div>
  		</div>
  	</div>
</section>