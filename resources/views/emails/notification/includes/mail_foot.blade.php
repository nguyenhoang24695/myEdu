<div style="height: 40px; width: 100%; display: block"></div>

<div style="background: url({{config('app.url')}}/frontend/img/email/bgmail.png);width: 100%;min-height: 185px;text-align: center;">
	
	<div style="width: 100%;display: block;font-size: 15px;font-weight: bold;float: left;margin: 20px 0px;color: #fff">
		<div style="width: 50%;text-align: right;float: left;">
			<span style="margin-right: 10px"><span style="background: url({{config('app.url')}}/frontend/img/email/email_phone.png);width: 16px;height: 24px;display: inline-block;vertical-align: middle;margin-right: 5px;"></span>{{config('common.myedu.contact.telephone')}} </span>
		</div>
		<div style="width: 50%;text-align: left;float: left;">
			<span style="margin-left: 10px"><span style="background: url({{config('app.url')}}/frontend/img/email/email_box.png);width: 20px;height: 20px;display: inline-block;vertical-align: bottom;margin-right: 5px;color:#fff"></span>
				<a href="mailto:{{config('common.myedu.contact.email')}}" style="color: #fff;text-decoration: none;">{{config('common.myedu.contact.email')}}</a>
			</span>
		</div>
	</div>

	<div style="color: #fff;float: left;width: 100%;line-height: 22px">
		<p style="font-size: 15px;margin: 0;font-weight: bold;margin-bottom: 5px;">{{config('app.url')}} - Website giáo dụng trực tuyến chất lượng nhất Việt Nam</p>
		<p style="font-size: 13px;margin: 0;margin-bottom: 5px;">Nếu bạn không muốn nhận email, hãy suy nghĩ và <a style="color: #333;text-decoration: none" href="{{config('app.url')}}/dashboard/notification_setting" >Click vào đây</a> để từ chối</p>
		<p style="font-size: 13px;margin: 0">{{config('app.url')}} © 2016</p>
	</div>

	<div style="float: left;width: 100%;">
		<a href="#" style="background: url({{config('app.url')}}/frontend/img/email/email_social.png);width: 35px;height: 35px;background-position: 0px 7px;background-repeat: no-repeat;display: inline-block;"></a>
		<a href="#" style="background: url({{config('app.url')}}/frontend/img/email/email_social.png);width: 35px;height: 35px;background-position: -40px 7px;background-repeat: no-repeat;display: inline-block;"></a>
		<a href="#" style="background: url({{config('app.url')}}/frontend/img/email/email_social.png);width: 35px;height: 35px;background-position: -90px 7px;background-repeat: no-repeat;display: inline-block"></a>
	</div>
	
</div>