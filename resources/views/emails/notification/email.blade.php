<div style="width: 800px; background-color: #fff; font-family: arial; padding: 50px 0;">
	
	<div style="padding: 30px;font-size: 14px;line-height: 20px">
		@include('emails.notification.includes.mail_head')
		<p ><strong>{{ $obj_user->name }}</strong> thân mến.</p>
		@include('emails.notification.build.content')
	</div>
	@include('emails.notification.includes.mail_foot')
	
</div>