<h4 class="recharge-method-title">Chuyển khoản</h4>
<div class="form-group">
  <select name="my_bank_card" class="form-control bs_select" data-style="btn-select">
    <option value=""> Chọn ngân hàng </option>
    @foreach(config('money.'.config("app.id").'.bank_cards') as $k => $v)
      @if (isset($v['account']) && $v['account'] != "" && $v['name'] != "")
        <option value="{{$k}}" title="{{$v['bank_name']}}"
                data-content="<div class='bank-in-select'><img height='34' width='50' src='{{url($v['logo'])}}'/> {{$v['bank_name']}}</div>">
          {{$v['bank_name']}}
        </option>
      @endif
    @endforeach
  </select>
</div>

<div class="form-group" data-toggle="" data-placement="bottom" title="Nhập số tiền">
  <input class="form-control number_mark_up" data-field=".bank_exchange_amount" placeholder="Số tiền"/>
  <input type="hidden" class="form-control bank_exchange_amount" name="bank_exchange[amount]" placeholder="Số tiền"/>
</div>
<div class="form-group" data-toggle="" data-placement="bottom" title="Nhập họ tên">
  <input class="form-control" name="bank_exchange[name]" value="{{$user->full_name}}" placeholder="Họ tên"/>
</div>
<div class="form-group" data-toggle="" data-placement="bottom" title="Nhập số điện thoại">
  <input class="form-control" name="bank_exchange[phone]" value="{{$user->phone}}" placeholder="Số điện thoại"/>
</div>
<div class="form-group" data-toggle="" data-placement="bottom" title="Nhập email">
  <input class="form-control" name="bank_exchange[email]" value="{{$user->email}}" placeholder="Email"/>
</div>
<div class="form-group" data-toggle="" data-placement="bottom" title="Nhập địa chỉ">
  <input class="form-control" name="bank_exchange[address]" value="{{$user->address}}" placeholder="Địa chỉ"/>
</div>
  {{--<div class="col-sm-6">--}}
    {{--<div class="row">--}}
      {{--<label>Chọn ngân hàng</label>--}}
      {{--<div class="bank-list">--}}
        {{--@foreach(config('money.bank_cards') as $k => $v)--}}
          {{--<div class="bank-card pull-left sys_card" data-bank-card="{{$k}}">--}}
            {{--<img class="img-bank" id="32"--}}
                 {{--alt="{{$k}}"--}}
                 {{--src="{{$v['logo']}}"--}}
                 {{--title="{{$v['name']}}">--}}
          {{--</div>--}}
          {{--@endforeach--}}
        {{--<input type="hidden" name="my_bank_card" id="my_bank_card"/>--}}
      {{--</div>--}}
    {{--</div>--}}
  {{--</div>--}}