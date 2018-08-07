<table class="table table-bordered table-striped">
  <tr>
    <th>
      Mã Giao dịch
    </th>
    <th>
      Loại giao dịch
    </th>
    <th>
      Trạng thái
    </th>
    <th>
      Số tiền
    </th>
  </tr>
  @foreach($transactions as $transaction)
  <tr>
    <td>
      <div>{{$transaction->order->code}}</div>
                            <span class="small">
                              {{$transaction->created_at->format('H:i | d/m/Y')}}
                            </span>
    </td>
    <td>
      {{$transaction->order->type_string}}
      -
      {{$transaction->order->item_name}}
    </td>
    <td>
      {{$transaction->order->status_string}}
    </td>
    <td class="text-right">
      {{human_money($transaction->amount, '0 đ')}}
      {!! $transaction->from_acc == $my_id ? "<span class='fa fa-long-arrow-down text-warning'></span>" : "<span class='fa fa-long-arrow-up text-info'></span>" !!}
    </td>
  </tr>
  @endforeach
</table>