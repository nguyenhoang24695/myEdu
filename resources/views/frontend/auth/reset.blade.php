@extends('frontend.layouts.default')

@section('content')
<div class="container">
	<div class="row mt_15 register">
		<div class="col-md-8 col-md-offset-2">
			<div class="panel panel-default">
				<div class="panel-body">
					<div class="col-md-8 col-md-offset-2">
						<div class="login_logo">
	                        <p class="hide"><a href="{{ url('/') }}" class="logo logo-small"></a></p>
	                        <p class="title">Thiết lập lại mật khẩu</p>
	                    </div>
	                    
	                    {!! Form::open(['to' => 'password/reset', 'class' => 'edus-login', 'role' => 'form']) !!}

						<input type="hidden" name="token" value="{{ $token }}">

						<div class="form-group">
							{!! Form::input('email', 'email', old('email'), ['class' => 'form-control','placeholder' => 'Nhập địa chỉ email']) !!}
						</div>

						<div class="form-group">
							{!! Form::input('password', 'password', null, ['class' => 'form-control','placeholder' => 'Nhập mật khẩu']) !!}
						</div>

						<div class="form-group">
							{!! Form::input('password', 'password_confirmation', null, ['class' => 'form-control','placeholder' => 'Nhập lại mật khẩu']) !!}
						</div>

						<button type="submit" class="btn btn-primary send_login">Thiết lập</button>

						{!! Form::close() !!}
						
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection