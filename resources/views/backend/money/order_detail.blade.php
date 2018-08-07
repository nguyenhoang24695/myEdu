@extends ('backend.layouts.master')

@section ('title', 'Report tài chính tài khoản doanh thu toàn hệ thống')

@section('after-scripts-end')
  {{--{!! HTML::script('backend/js/searchindex.js') !!}--}}
@endsection

@section('page-header')
  <h1>
    Chi tiết đơn hàng
  </h1>
@endsection

@section ('breadcrumbs')
  <li><a href="{!!route('backend.dashboard')!!}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
  <li class=""><a href="{{route('backend.money.orders.list')}}"><i class="fa fa-file-text"></i> Danh sách đơn hàng  </a></li>
  <li class="active"> Đơn hàng </li>
@stop

@section('content')
  <div class="box">
    <div class="box-header with-border">
      <h3 class="box-title">Chi tiết đơn hàng {{$order->code}}</h3>
    </div>
    <div class="box-body">
      <ul class="list-group">
        <li class="list-group-item">
          <div class="row">
            <div class="col-sm-6">
              Mã đơn hàng
            </div>
            <div class="col-sm-6 text-bold">
              {{$order->code}}
            </div>
          </div>
        </li>
        <li class="list-group-item">
          <div class="row">
            <div class="col-sm-6">
              Trạng thái đơn hàng
            </div>
            <div class="col-sm-6 text-bold">
              {{$order->status_string}}
            </div>
          </div>
        </li>
        <li class="list-group-item">
          <div class="row">
            <div class="col-sm-6">
              Người bán
            </div>
            <div class="col-sm-6 text-bold">
              {{$order->sellingUser->name}}
            </div>
          </div>

        </li>
        <li class="list-group-item">
          <div class="row">
            <div class="col-sm-6">
              Người mua
            </div>
            <div class="col-sm-6 text-bold">
              {{$order->buyingUser->name}}
            </div>
          </div>

        </li>
        <li class="list-group-item">
          <div class="row">
            <div class="col-sm-6">
              Trị giá
            </div>
            <div class="col-sm-6 text-bold">
              {{human_money($order->item_price)}}
            </div>
          </div>

        </li>
        <li class="list-group-item">
          <div class="row">
            <div class="col-sm-6">
              Loại giao dịch
            </div>
            <div class="col-sm-6 text-bold">
              {{$order->type_string}} - {{$order->item_name}}
            </div>
          </div>
        </li>
        <li class="list-group-item">
          <div class="row">
            <div class="col-sm-6">
              Thời gian tạo
            </div>
            <div class="col-sm-6 text-bold">
              {{$order->created_at}}
            </div>
          </div>
        </li>
        <li class="list-group-item">
          <div class="row">
            <div class="col-sm-12">
              <b>Nội dung đơn hàng</b>
              @if($order->item_type == 'App\Models\BankPayment')
                @include('backend.money.partial.preview_bank_payment', ['bank_payment' => $order->getItemObject()])
              @elseif($order->item_type == 'App\Models\Course')
                @include('backend.money.partial.preview_course', ['course' => $order->getItemObject()])
              @elseif($order->item_type == 'App\Models\MobileCard')
                @include('backend.money.partial.preview_mobile_card', ['mobile_card' => $order->getItemObject()])
              @endif
            </div>
          </div>

        </li>
        <li class="list-group-item">
          <div class="row">
            <div class="col-sm-12">
              <b>
                Giao dịch phát sinh
              </b>
              @include('backend.money.partial.transaction_table', ['transactions' => $order->innerTransactions, 'sortable' => false])
            </div>
          </div>

        </li>
      </ul>
    </div>
  </div>
  @if($action == 'approve')
  <div class="box">
    {!! Form::open(['name' => 'bank_exchange_confirm_form', 'method' => 'post']) !!}
    <div class="box-header with-border">
      <h3 class="box-title">Xác nhận xử lý</h3>
    </div>
    <div class="box-body">
      <div class="form-group">
        <label>Mã giao dịch</label>
        {!! Form::text('transaction_id', null, ['class' => 'form-control']) !!}
      </div>
      <div class="form-group">
        <label>Ghi chú</label>
        {!! Form::textarea('other_info', null, ['class' => 'form-control', 'rows' => 3]) !!}
      </div>
      Nhập mã giao dịch nhận được từ ngân hàng nếu là giao dịch chuyển khoản ngân hàng.
      <button type="submit" class="btn btn-danger">Xác nhận</button>
    </div>
    {!! Form::close() !!}
  </div>
  @elseif($action == 'reject')
  <div class="box">
    <div class="box-header with-border">
      <h3 class="box-title">Hủy giao dịch</h3>
    </div>
    <div class="box-body">
      {!! Form::open(['name' => 'cancel_order_form', 'method' => 'post']) !!}
      <p>
        Nếu lựa chọn hủy giao dịch, giao dịch sẽ bị hủy và không tự động thực hiện các tác vụ khi người dùng thực
        hiện các bước tiếp theo để hoàn thành(nếu có) giao dịch.
      </p>
      <button class="btn btn-default">Hủy giao dịch</button>
      {!! Form::close() !!}
    </div>
  </div>
  @else
    <div class="box">
      {!! Form::open(['name' => 'bank_exchange_confirm_form', 'method' => 'post']) !!}
      <div class="box-header with-border">
        <h3 class="box-title">Ghi chú</h3>
      </div>
      <div class="box-body">
        <div class="form-group">
          <label>Ghi chú</label>
          {!! Form::textarea('note', $order->note, ['class' => 'form-control', 'rows' => 3]) !!}
        </div>
        Ghi chú đơn hàng
        <button type="submit" class="btn btn-danger">Lưu</button>
      </div>
      {!! Form::close() !!}
    </div>
  @endif
@endsection