@extends('frontend.layouts.default')

@section('content')
<div class="container">
	<div class="row register mt_15">
		<div class="col-md-8 col-md-offset-2">
			<div class="panel panel-default">
				<div class="panel-body">
					<div class="col-md-8 col-md-offset-2">
						<div class="login_logo">
                            <p class="hide"><a href="{{ url('/') }}" class="logo logo-small"></a></p>
                            <p class="title">Quên mật khẩu</p>
                        </div>

	                    {!! Form::open(['to' => 'password/email', 'class' => 'edus-login', 'role' => 'form']) !!}

							<div class="form-group al_left">
                            @if (session('status'))
                                <div class="alert alert-success">
                                    {{ session('status') }}
                                </div>
                            @endif
                                
                                {!! Form::input('email', 'email', old('email'), ['class' => 'form-control input_login','placeholder' => 'Nhập email bạn đã đăng ký']) !!}
                                {!! $errors->first('email', '<span class="label label-danger">:message</span>') !!}
                            </div>

							<button type="submit" class="btn btn-primary waves-effect waves-light send_login">Gửi</button>

							<div class="form-group foot_login">
                                <div class="row">
                                  <div class="col-md-6"><a href="{{ url('password/email') }}" class="pull-left">Quên mật khẩu</a></div>
                                  <div class="col-md-6"><a href="{{ url('auth/register') }}" class="pull-right">Đăng nhập</a></div>
                                </div>
                            </div>

						{!! Form::close() !!}
					</div>
				</div><!-- panel body -->

            </div><!-- panel -->

        </div><!-- col-md-8 -->

    </div><!-- row -->
</div>
@endsection