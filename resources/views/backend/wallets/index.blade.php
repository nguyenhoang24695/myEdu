@extends('backend.layouts.master')

@section ('title', 'Toàn bộ tài khoản')

@section('page-header')
  <h1>
    Tài khoản tiền
  </h1>
@endsection

@section ('breadcrumbs')
  <li><a href="{!!route('backend.dashboard')!!}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
  <li class="active">Quản lý tài khoản</li>
@stop

@section('content')

  <div class="box">
    <div class="box-header">
      <h4>Danh sách tài khoản tiền người dùng</h4>
    </div>
    <div class="box-body">
      {!! Form::open(['name' => 'wallet_filter_form', 'method' => "get"]) !!}
      <div class="col-xs-6 col-md-3">
        <input class="form-control" name="user_code" value="{{\Request::query('user_code')}}" placeholder="Mã tài khoản" />
      </div>
      <div class="col-xs-6 col-md-3">
        <input class="form-control" name="user_email" value="{{\Request::query('user_email')}}" placeholder="Email" />
      </div>
      <div class="col-xs-6 col-md-3">
        <input class="form-control" name="user_phone" value="{{\Request::query('user_phone')}}" placeholder="Số điện thoại" />
      </div>
      <div class="col-xs-6 col-md-3">
        <input class="btn btn-primary" type="submit" value="Tìm kiếm" />
      </div>
      {!! Form::close() !!}
      <?php $requesting = \Request::all(); ?>
      @include('backend.wallets.includes.wallet_table', ['wallets' => $wallets])
    </div>
  </div>
@endsection