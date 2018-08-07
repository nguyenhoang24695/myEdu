@if($bank_payment->gate == 'bao_kim')
  <table class="table table-bordered">
    <tr>
      <td>Cổng thanh toán</td>
      <td>
        Bảo Kim
      </td>
    </tr>
    <tr>
      <td>Số tiền</td>
      <td>{{human_money($bank_payment->price, '0đ')}}</td>
    </tr>
    <tr>
      <td>Mã giao dịch</td>
      <td>{{$bank_payment->transaction_id}}</td>
    </tr>
    <tr>
      <td>ID ngân hàng khách hàng lựa chọn</td>
      <td>{{$bank_payment->bank_id}}</td>
    </tr>
    <tr>
      <td>Ngân hàng khách hàng lựa chọn</td>
      <td>{{$bank_payment->bank_name}}</td>
    </tr>
    <tr>
      <td>Liên kết thanh toán/ Hướng dẫn thanh toán</td>
      <td>{{$bank_payment->bank_payment_link}}</td>
    </tr>
    <tr>
      <td>Tên người chuyển</td>
      <td>{{$bank_payment->payer_name}}</td>
    </tr>
    <tr>
      <td>Email người chuyển</td>
      <td>{{$bank_payment->payer_email}}</td>
    </tr>
    <tr>
      <td>Số điện thoại người chuyển</td>
      <td>{{$bank_payment->payer_phone_no}}</td>
    </tr>
    <tr>
      <td>Địa chỉ người chuyển</td>
      <td>{{$bank_payment->payer_address}}</td>
    </tr>
    <tr>
      <td>Ghi chú</td>
      <td>{{$bank_payment->other_info}}</td>
    </tr>
  </table>

@elseif($bank_payment->gate == 'manual')
  <table class="table table-bordered">
    <tr>
      <td>Cổng thanh toán</td>
      <td>
        Manual
      </td>
    </tr>
    <tr>
      <td>Số tiền</td>
      <td>{{human_money($bank_payment->price, '0đ')}}</td>
    </tr>
    <tr>
      <td>Mã giao dịch</td>
      <td>{{$bank_payment->transaction_id}}</td>
    </tr>
    <tr>
      <td>Ngân hàng nhận thanh toán</td>
      <td>{{$bank_payment->bank_name}} ({{$bank_payment->bank_short_name}})</td>
    </tr>
    <tr>
      <td>TK nhận thanh toán</td>
      <td>{{$bank_payment->bank_account_name}}</td>
    </tr>
    <tr>
      <td>Số TK nhận thanh toán</td>
      <td>{{$bank_payment->bank_account_number}}</td>
    </tr>
    <tr>
      <td>Liên kết thanh toán/ Hướng dẫn thanh toán</td>
      <td>{{$bank_payment->bank_payment_link}}</td>
    </tr>
    <tr>
      <td>Tên người chuyển</td>
      <td>{{$bank_payment->payer_name}}</td>
    </tr>
    <tr>
      <td>Email người chuyển</td>
      <td>{{$bank_payment->payer_email}}</td>
    </tr>
    <tr>
      <td>Số điện thoại người chuyển</td>
      <td>{{$bank_payment->payer_phone_no}}</td>
    </tr>
    <tr>
      <td>Địa chỉ người chuyển</td>
      <td>{{$bank_payment->payer_address}}</td>
    </tr>
    <tr>
      <td>Ghi chú</td>
      <td>{{$bank_payment->other_info}}</td>
    </tr>
  </table>
@endif