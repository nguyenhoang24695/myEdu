@extends ('backend.layouts.master')

@section ('title', 'Report tài chính tài khoản doanh thu toàn hệ thống')

@section('after-scripts-end')
  {{--{!! HTML::script('backend/js/searchindex.js') !!}--}}
@endsection

@section('page-header')
  <h1>
    Danh sách giao dịch tiền tài khoản nội bộ
  </h1>
@endsection

@section ('breadcrumbs')
  <li><a href="{!!route('backend.dashboard')!!}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
  <li class=""><a href="{{route('backend.money.orders.list')}}"><i class="fa fa-file-text"></i> Danh sách đơn hàng  </a></li>
  <li class="active">Danh sách giao dịch</li>
@stop

@section('content')
  <div class="box">
    <div class="box-header with-border">
      <h3 class="box-title">Danh sách giao dịch tiền</h3>
    </div>
    <div class="box-body">
      <div class="row">
        {!! Form::open(['method' => 'get']) !!}
        <div class="col-xs-3 col-sm-2 col-md-2">
          <input type="text" class="form-control" name="code"
                 placeholder="Mã đơn hàng"
                 value="{{\Request::query('code')}}"/>
        </div>
        <div class="col-xs-3 col-sm-2 col-md-2">
          <input type="text" class="form-control" name="id_email"
                 placeholder="ID hoặc Email"
                 value="{{\Request::query('id_email')}}"/>
        </div>
        <div class="col-xs-3 col-sm-2 col-md-2">
          {!! Form::select('wallet_type',
          [null => 'Các loại TK', 'primary' => 'TK Doanh thu', 'secondary' => 'TK Mua khóa học'],
          \Request::query('wallet_type'),
          ['class' => 'form-control']) !!}
        </div>
        <div class="col-xs-6 col-sm-4 col-md-3">
          <button class="btn btn-default">Tìm</button>
        </div>
        {!! Form::close() !!}
      </div>
      <?php $requesting = \Request::all(); ?>
      @include('backend.money.partial.transaction_table', ['transactions' => $transactions, 'sortable' => true])
    </div>
  </div>
@endsection