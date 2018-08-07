<table class="table table-bordered">
  <tr>
    <td>Cổng thanh toán</td>
    <td>
      {{$mobile_card->gate}}
    </td>
  </tr>
  <tr>
    <td>Loại thẻ</td>
    <td>
      {{$mobile_card->provider}}
    </td>
  </tr>
  <tr>
    <td>Mệnh giá thẻ</td>
    <td>
      {{human_money($mobile_card->price)}}
    </td>
  </tr>
  <tr>
    <td>Pin</td>
    <td>
      {{$mobile_card->pin}}
    </td>
  </tr>
  <tr>
    <td>Serial</td>
    <td>
      {{$mobile_card->serial}}
    </td>
  </tr>
  <tr>
    <td>Phần trăm nhà mạng</td>
    <td>
      {{$mobile_card->discount}}
    </td>
  </tr>
  <tr>
    <td>Số tiền thực tế vào hệ thống</td>
    <td>
      {{human_money($mobile_card->real_price)}}
    </td>
  </tr>

  <tr>
    <td>Trạng thái</td>
    <td>
      {{$mobile_card->status_string}}
    </td>
  </tr>
</table>