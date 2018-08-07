@extends('frontend.layouts.default')

@section('after-styles-end')
@endsection

@section('after-scripts-end')
  {!! HTML::script('frontend/js/profile.js') !!}
  {!! HTML::script('frontend/js/financial.js') !!}
  {!! HTML::script('frontend/js/financial_recharge_plugin.js') !!}
@endsection

@section('content')

  <div class="container">
    <div class="row">
      <div class="col-md-3 aside unibee-aside">
        @include('frontend.user.includes.aside')
      </div>
      <div class="col-md-9 profile-private">
        <div class="wrap_main">
          <section>
            <div class="panel panel-money">
              <div class="panel-heading">
                <h4 class="panel-title">
                  <span>Quản lý tài chính</span>
                </h4>
              </div>
              @if (session('status'))
                <div class="alert alert-success">
                  {{ session('status') }}
                </div>
              @endif

              <div class="panel-body">
                <div class="grey-box clearfix">
                  <div class="col-sm-4">
                    <span class="text">Doanh thu</span>
                  </div>
                  <div class="col-sm-4 dola-info">
                    <span class="icon-dollar pull-left"></span>
                    <span class="text">{{number_format($user->primary_wallet)}} <small>VND</small></span>
                  </div>
                  <div class="col-sm-4">
                    <div class="group-btn">
                      <a href="" class="btn btn-sm btn-outline">Chuyển tiền</a>
                      <a href="" class="btn btn-sm btn-outline">Rút tiền</a>
                    </div>
                  </div>
                </div>

                <div class="grey-box clearfix">
                  <div class="col-sm-4">
                    <span class="text">Số dư mua khóa học</span>
                  </div>
                  <div class="col-sm-4 dola-info">
                    <span class="icon-dollar pull-left"></span>
                    <span class="text">{{number_format($user->secondary_wallet)}} <small>VND</small></span>
                  </div>
                  <div class="col-sm-4">
                  <div class="group-btn">
                    <a onclick="recharge_modal_form.modal('show');" href="javascript:void(0);" class="btn btn-sm btn-outline">Nạp tiền</a>
                  </div>
                  </div>
                </div>

                @if ($code_info)
                  <div class="promo-code">
                    @if ($code_info->active == 1)
                      <h4>Mã giảm giá của bạn: 
                      <a href="{{ route('frontend.code.detail',['code'=>$code_info->code]) }}"><strong>{{ $code_info->code }}</strong></a>
                      <i 
                      class="fa fa-question-circle"
                      data-toggle="tooltip" 
                      data-placement="bottom" 
                      title="Bất kỳ ai khi sử dụng mã này sẽ được giảm giá khi mua khóa học tại {{config('app.url')}}"></i></h4>
                      @if ($check_partner)
                        <p>Giảm giá <strong>{{ $code_info->discount_2 }}%</strong> cho các khóa học của chính bạn tạo</p>
                        <p>Giảm giá <strong>{{ $code_info->discount_1 }}%</strong> cho các khóa học không phải của bạn</p>
                        <p>Để thay đổi % chiết khẩu bạn vui lòng <a href="{{ route('frontend.code.detail',['code'=>$code_info->code]) }}">Xem chi tiết</a> </p>
                      @else
                        <p>Giảm giá {{ $code_info->discount_max }}% cho các khóa học</p>
                        <p>Không những được giảm giá mà còn được thưởng.</p>
                        <p>Hãy tham gia chương trình Partership của {{config('app.url')}} để nâng cấp mã Code và tăng thu nhập hàng tháng tại {{config('app.url')}}</p>
                        <a href="{{ route('partner.info') }}">Khám phá chi tiết ngay</a>
                      @endif
                    @else
                      
                      <div class="alert alert-warning" role="alert">
                        <p>Mã Code giới thiệu của bạn đang tạm dừng hoạt động. Bạn vui lòng liên hệ BQT để kích hoạt lại.</p>
                      </div>
                    @endif
                  </div>
                @endif

              </div>
            </div>
            <div class="panel panel-primary">
              <div class="panel-heading">
                <h4 class="panel-title">
                  Lịch sử biến động số dư
                </h4>
              </div>
              <div class="panel-body">
                <ul class="nav nav-tabs" role="tablist">
                  <li role="presentation" class="">
                    <a href="#primary_wallet_report" id="primary_wallet_report_control" aria-controls="primary_wallet_report" role="tab" data-toggle="tab">
                      <span class="hidden-xs">Tài khoản doanh thu</span>
                      <span class="hidden-sm hidden-md hidden-lg">TKDT</span>
                    </a>
                  </li>
                  <li role="presentation" class="">
                    <a href="#secondary_wallet_report" id="secondary_wallet_report_control" aria-controls="secondary_wallet_report" role="tab" data-toggle="tab">
                      <span class="hidden-xs">Tài khoản mua khóa học</span>
                      <span class="hidden-sm hidden-md hidden-lg">TK Mua KH</span>
                    </a>
                  </li>
                  <li role="presentation" class="">
                    <a href="#wait_process_report" id="wait_process_report_control" aria-controls="wait_process_report" role="tab" data-toggle="tab">
                      <span class="hidden-xs">Chờ xử lý</span>
                      <span class="hidden-sm hidden-md hidden-lg">Chờ XL</span>
                    </a>
                  </li>
                </ul>

                <!-- Tab panes -->
                <div class="tab-content no-border">
                  <div role="tabpanel" class="tab-pane active" id="primary_wallet_report">
                    @include('frontend.user.includes.transaction_filter')
                    <div class="table-transaction" id="primary_transaction_list">

                    </div>
                  </div>
                  <div role="tabpanel" class="tab-pane" id="secondary_wallet_report">
                    @include('frontend.user.includes.transaction_filter')
                    <div class="table-transaction" id="secondary_transaction_list">

                    </div>
                  </div>
                  <div role="tabpanel" class="tab-pane" id="wait_process_report">
                    <div class="table-transaction" id="wait_process_list">
                      <table class="table table-striped table-bordered">
                        <tr>
                          <th>Mã đơn hàng</th>
                          <th>Loại đơn đơn hàng</th>
                          <th>Trạng thái</th>
                          <th>Giá</th>
                          <th>Ngày tạo</th>
                        </tr>
                        @foreach($wait_process_orders as $order)
                          <tr>
                            <td><a href="{{$order->make_guide_payment_link()}}">{{$order->code}}</a></td>
                            <td>{{$order->type_string}} - {{$order->item_name}}</td>
                            <td>{{$order->status_string}}</td>
                            <td class="text-right">{{human_money($order->item_price, '0 đ')}}</td>
                            <td>{{$order->created_at}}</td>
                          </tr>
                          @endforeach

                      </table>
                    </div>
                  </div>
                </div>
              </div>
            </div>

          </section>

        </div>
      </div>
    </div>
  </div>
@endsection