@extends('frontend.layouts.default')

@section('after-styles-end')
@endsection

@section('after-scripts-end')
  {!! HTML::script('frontend/js/profile.js') !!}
  {!! HTML::script('frontend/js/financial_recharge.js') !!}
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
                <h3 class="panel-title">Nạp tiền vào tài khoản </h3>
              </div>
              <div class="panel-body no-padding">
              </div>
            </div>
            {!! Form::open(['name' => 'recharge_form', 'id' => 'recharge_form', 'class' => 'form-group-sm']) !!}
            <div class="panel panel-primary">
              <div class="panel-heading">
                <h3 class="panel-title">
                  Chọn hình thức thanh toán
                </h3>
              </div>
              <div class="panel-body">
                <ul class="nav nav-tabs" role="tablist">
                  <li role="presentation" class="">
                    <a href="#recharge_by_mobile_card" id="recharge_by_mobile_card_control" aria-controls="recharge_by_mobile_card" role="tab" data-toggle="tab">
                      Thẻ điện thoại
                    </a>
                  </li>
                  <li role="presentation" class="">
                    <a href="#recharge_by_bank_card" id="recharge_by_bank_card_control" aria-controls="recharge_by_bank_card" role="tab" data-toggle="tab">
                      Thẻ ngân hàng
                    </a>
                  </li>
                  <li role="presentation" class="">
                    <a href="#recharge_by_bank_exchange" id="recharge_by_bank_exchange_control" aria-controls="recharge_by_bank_exchange" role="tab" data-toggle="tab">
                      Chuyển khoản
                    </a>
                  </li>
                </ul>
                <!-- Hidden fields -->
                <input type="hidden" name="recharge_by" id="recharge_by" value="mobile_card"/>
                <!-- Tab panes -->
                <div class="tab-content no-border">
                  <div role="tabpanel" class="tab-pane active" id="recharge_by_mobile_card"><i class="fa fa-spinner fa-spin"></i></div>
                  <div role="tabpanel" class="tab-pane" id="recharge_by_bank_card"><i class="fa fa-spinner fa-spin"></i></div>
                  <div role="tabpanel" class="tab-pane" id="recharge_by_bank_exchange"><i class="fa fa-spinner fa-spin"></i></div>
                </div>
                <div class="row">
                  <div class="col-sm-6">
                    {!! app('captcha')->display() !!}
                  </div>
                  <div class="col-sm-6">
                    <button type="submit" class="btn btn-lagre btn-primary">
                      Nạp tiền
                    </button>
                    <p>
                      Lựa chọn hình thức thanh toán, sau đó nhập thông tin và ấn nút nạp tiền để sang bước tiếp theo.
                    </p>
                  </div>
                </div>
              </div>
            </div>
            {!! Form::close() !!}
          </section>

        </div>
      </div>
    </div>
  </div>
@endsection