<h4 class="recharge-method-title">Nạp thẻ điện thoại</h4>
<div class="form-group form-card-providers">
  @foreach(config('money.'.config("app.id").'.validated_card_provider') as $card_provider)
    <label>
      <img src="{{url('frontend/img/common/logo_' . mb_strtolower($card_provider) . '.png')}}"/>
      <input type="radio" name="mobile_card_provider" value="{{$card_provider}}"/>
    </label>
  @endforeach
</div>
<div class="form-group">
  <input type="number" class="form-control" name="mobile_card_pin" placeholder="Nhập mã thẻ"/>
</div>
<div class="form-group">
  <input type="number" class="form-control" name="mobile_card_serial" placeholder="Số serial"/>
</div>