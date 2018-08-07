@extends('frontend.layouts.default')

@section('after-styles-end')
@endsection

@section('after-scripts-end')
  {!! HTML::script('frontend/js/profile.js') !!}
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
            <div class="panel unibee-box">
              <div class="panel-heading">
                <h3 class="panel-title">Hướng dẫn thanh toán đơn hàng</h3>
              </div>
              <div class="panel-body no-padding">
                <div class="panel panel-default">
                  <div class="panel-body">
                    @if($bank_payment->gate == 'manual')
                    <p>
                      <b>B1:</b> Các bạn chuyển khoản vào tài khoản ngân hàng của {!! config('custom_info.payment.info.name') !!} với thông tin sau:
                      <p class="small">(Có thể sử dụng thẻ ATM tại cây ATM hoặc đến trực tiếp phòng giao dịch của ngân hàng)</p>
                    </p>
                    <table class="table table-hover table-bordered">
                      <tr>
                        <td>
                          Ngân hàng
                        </td>
                        <th>
                          {{$bank_payment->bank_name}}
                        </th>
                      </tr>
                      <tr>
                        <td>
                          Chủ tài khoản
                        </td>
                        <th>
                          {{$bank_payment->bank_account_name}}
                        </th>
                      </tr>
                      <tr>
                        <td>
                          Số tài khoản
                        </td>
                        <th>
                          {{$bank_payment->bank_account_number}}
                        </th>
                      </tr>
                      <tr>
                        <td>
                          Số tiền
                        </td>
                        <th>
                          {{human_money($order->item_price, '0 đ')}}
                        </th>
                      </tr>
                      <tr>
                        <td>
                          Nội dung chuyển tiền
                        </td>
                        <th>
                          Thanh toán giao dịch {{$order->code}} trên {!! config("app.name") !!}
                        </th>
                      </tr>
                    </table>
                    <p>
                      <b>B2:</b> Nhắn tin theo cú pháp: <b>{{$order->code}}</b> Gửi <b>{!! config('common.'.config("app.id").'.contact.phone') !!}</b> để thông báo đã Nạp tiền
                    </p>
                    @elseif($bank_payment->gate == 'bao_kim')
                      <table class="table table-bordered">
                        <thead>
                          <tr>
                            <th colspan="2"> Thông tin giao dịch </th>
                          </tr>
                        </thead>
                        <tbody>
                        <tr>
                          <th>
                            Số tiền
                          </th>
                          <td>
                            {{human_money($bank_payment->price, '0 đ')}}
                          </td>
                        </tr>
                        </tbody>
                      </table>
                    <p>Các bạn thanh toán theo link sau, liên kết sau được bảo đảm bằng Bảo Kim</p>
                    <p>
                      <a class="btn btn-primary" href="{{$bank_payment->bank_payment_link}}">Thanh toán</a>
                    </p>
                    <p>Phí giao dịch ngân hàng thu: Thẻ ATM: 1.760 VND + 1.1% Thẻ Visa/Master/JBC: 5.000VND + 2.4%.</p>
                    <p>Sau khi các bạn thanh toàn thành công, hệ thống sẽ tự động cập nhật vào tài khoản cho quý khách(có thể sau 5-10 phút).</p>
                    <p>Nếu gặp lỗi vui lòng xem hướng dẫn tại đây Mọi thắc mắc vui lòng liên hệ: {!! config('common.'.config("app.id").'.contact.telephone') !!}</p>

                    @endif
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