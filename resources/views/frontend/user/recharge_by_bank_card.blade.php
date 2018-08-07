<h4 class="recharge-method-title">Thẻ ATM ngân hàng (không chấp nhận Visa)</h4>
{{--<div class="form-group" data-toggle="" data-placement="bottom" title="Số tiền cần nạp">
  <select name="bk_id" class="form-control bs_select" data-style="btn-select">
    <option> Chọn ngân hàng </option>
    <optgroup label="Thẻ quốc tế">
      @foreach($credit_cards as $card)
        <option value="{{$card['id']}}"
                title="{{$card['name']}}"
                data-content="<div class='bank-in-select'><img height='34' width='50' src='{{$card['logo_url']}}'/> {{$card['name']}}</div>">
          {{$card['name']}}
        </option>
      @endforeach
    </optgroup>
    <optgroup label="Thẻ ATM nội địa">
      @foreach($local_cards as $card)
        <option value="{{$card['id']}}"
                title="{{$card['name']}}"
                data-content="<div class='bank-in-select'><img height='34' width='50' src='{{$card['logo_url']}}'/> {{$card['name']}}</div>">
          {{$card['name']}}
        </option>
      @endforeach
    </optgroup>
  </select>
</div>--}}
<div class="form-group" data-toggle="" data-placement="bottom" title="Số tiền cần nạp">
  <input class="form-control number_mark_up" data-field=".bank_direct_amount" placeholder="Số tiền"/>
  <input type="hidden" class="form-control bank_direct_amount" name="bank_direct[amount]" placeholder="Số tiền cần nạp"/>
</div>
<div class="form-group" data-toggle="" data-placement="bottom" title="Họ tên">
  <input class="form-control" name="bank_direct[name]" value="{{$user->full_name}}" placeholder="Họ tên"/>
</div>
<div class="form-group" data-toggle="" data-placement="bottom" title="Số điện thoại">
  <input class="form-control" name="bank_direct[phone]" value="{{$user->phone}}" placeholder="Số điện thoại"/>
</div>
<div class="form-group" data-toggle="" data-placement="bottom" title="Email">
  <input class="form-control" name="bank_direct[email]" value="{{$user->email}}" placeholder="Email"/>
</div>
<div class="form-group" data-toggle="" data-placement="bottom" title="Địa chỉ">
  <input class="form-control" name="bank_direct[address]" value="{{$user->address}}" placeholder="Địa chỉ"/>
</div>
