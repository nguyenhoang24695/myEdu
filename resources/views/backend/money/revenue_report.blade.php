@extends ('backend.layouts.master')

@section ('title', 'Report tài chính tài khoản doanh thu toàn hệ thống')

@section('after-scripts-end')
  {{--{!! HTML::script('backend/js/searchindex.js') !!}--}}
@endsection

@section('page-header')
  <h1>
    Report tài chính tài khoản doanh thu toàn hệ thống
  </h1>
@endsection

@section ('breadcrumbs')
  <li><a href="{!!route('backend.dashboard')!!}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
  <li class="active">Revenue report</li>
@stop

@section('content')
  <div class="row">
    <div class="col-md-6">
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title"> Tài khoản đối ứng </h3>
        </div>
        <div class="box-body">
          <ul class="list-group">
            <li class="list-group-item">
              <h4>{{$revenue_acc->email}}</h4>
              <p>{{$revenue_acc->primary_wallet}} VNĐ</p>
            </li>
          </ul>
        </div>
      </div>
    </div>
    <div class="col-md-6">
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title"> Tài khoản hệ thống khác </h3>
        </div>
        <div class="box-body">
          @foreach($system_users as $card_user)
            @if($card_user)
            <li class="list-group-item">
              <h4> {{$card_user->email}} </h4>
              <p> {{$card_user->primary_wallet}} VNĐ </p>
            </li>
            @endif
          @endforeach
        </div>
      </div>
    </div>
  </div>


  <div class="box">
    <div class="box-header with-border">
      <h3 class="box-title">10 đơn hàng mới nhất</h3>
    </div>
    <div class="box-body">
      Đây là các đơn hàng tương đương với một giao dịch mức người dùng : mua khóa học, chuyển tiền, nạp thẻ, ...
      @include('backend.money.partial.order_table', ['orders' => $orders])
      <div>
        <a href="{{route('backend.money.orders.list')}}" class="btn btn-xs btn-primary">Xem toàn bộ</a>
      </div>
    </div>
  </div>
  <div class="box">
    <div class="box-header with-border">
      <h3 class="box-title">20 giao dịch mới nhất</h3>
    </div>
    <div class="box-body">
      Đây là các giao dịch mức hệ thống, liệt kê dòng tiền từ các tài khoản thực tế chạy trên hệ thống
      @include('backend.money.partial.transaction_table', ['transactions' => $transactions])
      <div>
        <a href="" class="btn btn-xs btn-primary">Xem toàn bộ</a>
      </div>
    </div>
  </div>
@endsection