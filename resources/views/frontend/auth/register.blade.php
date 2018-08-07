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
	                        <p class="title">Đăng ký tài khoản</p>
	                    </div>
	                    <div class="edus-login">

	                        <div class="form-group">
                                <a 
                                href="{{ route('auth.provider',['provider' => 'facebook']) }}"
                                class="form-control loginwidthfacebook"><i class="fa fa-facebook"></i><span>Đăng ký bằng facebook</span></a>
                            </div>

	                        <div class="form-group">
                                <a href="{{ route('auth.provider',['provider' => 'google']) }}" class="form-control loginwidthgoogle" ><i class="fa fa-google"></i><span>Đăng ký bằng google</span></a>
                            </div>

                            @if (getenv('IDVG_CLIENT_ID') != "")
	                            <div class="form-group">
	                                <a href="{{ route('idvg.login',['uri'=>base64_encode(Request::url())]) }}" class="form-control loginwidthvgid" >
	                                    <i class="fa fa-vgid"></i>
	                                    <span>Đăng ký bằng <strong>ID</strong> <span class="vg">vatgia</span></span>
	                                </a>
	                            </div>
                            @endif

	                    </div>
						{!! Form::open(['to' => 'auth/register', 'class' => 'edus-login', 'role' => 'form']) !!}

							<div class="form-group horizon">
	                            <h3>
	                                <span>Hoặc nhập đầy đủ thông tin dưới đây đề đăng ký</span> <hr>
	                            </h3>
	                        </div>

							<div class="form-group al_left">
								{!! Form::input('name', 'name', old('name'), ['class' => 'form-control input_login','placeholder' => 'Họ và tên']) !!}
								{!! $errors->first('name', '<span class="label label-danger">:message</span>') !!}
							</div>

							<div class="form-group al_left">
								{!! Form::input('email', 'email', old('email'), ['class' => 'form-control input_login','placeholder'=> 'Email của bạn']) !!}
								{!! $errors->first('email', '<span class="label label-danger">:message</span>') !!}
							</div>

							<div class="form-group al_left">
								{!! Form::input('password', 'password', null, ['class' => 'form-control input_login','placeholder' => 'Mật khẩu']) !!}
								{!! $errors->first('password', '<span class="label label-danger">:message</span>') !!}
							</div>

							<div class="form-group al_left">
								{!! Form::input('password', 'password_confirmation', null, ['class' => 'form-control input_login','placeholder'=>'Nhập lại mật khẩu']) !!}
								{!! $errors->first('password_confirmation', '<span class="label label-danger">:message</span>') !!}
							</div>

							<button type="submit" class="btn btn-primary waves-effect waves-light send_login">Đăng ký</button>

							<div class="form-group foot_login">
                                <div class="row">
                                  <div class="col-md-6"><a href="{{ url('password/email') }}" class="pull-left">Quên mật khẩu</a></div>
                                  <div class="col-md-6"><a href="{{ url('auth/login') }}" class="pull-right">Đăng nhập</a></div>
                                </div>
                                <div class="row">
                                    
                                </div>
                            </div>

						{!! Form::close() !!}
					</div>
					

				</div><!-- panel body -->

	         </div><!-- panel -->

	     </div><!-- col-md-8 -->

	 </div><!-- row -->
 </div>

@include('frontend.popup.developing')

@endsection