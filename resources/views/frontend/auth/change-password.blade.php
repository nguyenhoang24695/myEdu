@extends('frontend.layouts.default')

@section("after-styles-end")
    {!! HTML::style('frontend/css/login.css') !!}
@endsection

@section('content')
<div class="container">
  <div class="row register mt_15">
    <div class="col-md-8 col-md-offset-2">
      <div class="panel panel-default">
        <div class="panel-body">
          <div class="col-md-8 col-md-offset-2">
            <div class="login_logo">
                <p class="hide"><a href="{{ url('/') }}" class="logo logo-small"></a></p>
                <p class="title">Thay đổi mật khẩu</p>
            </div>

            {!! Form::open(['route' => ['password.change'], 'class' => 'edus-login']) !!}

              <div class="form-group al_left">
                {!! Form::input('password', 'old_password', null, ['class' => 'form-control input_login','placeholder '=>'Mật khẩu cũ']) !!}
                {!! $errors->first('password', '<span class="label label-danger">:message</span>') !!}
              </div>

              <div class="form-group al_left">
                {!! Form::input('password', 'password', null, ['class' => 'form-control input_login','placeholder'=>'Mật khẩu mới']) !!}
                {!! $errors->first('password', '<span class="label label-danger">:message</span>') !!}
              </div>

              <div class="form-group al_left">
                {!! Form::input('password', 'password_confirmation', null, ['class' => 'form-control input_login','placeholder'=>'Nhập lại mật khẩu mới']) !!}
                {!! $errors->first('password', '<span class="label label-danger">:message</span>') !!}
              </div>

              <button type="submit" class="btn btn-primary waves-effect waves-light send_login">Lưu lại</button>

            {!! Form::close() !!}
          </div>
          
        </div>
        <!-- panel body -->

      </div>
      <!-- panel -->

    </div>
    <!-- col-md-8 -->

  </div>
  <!-- row -->
</div>
@endsection