<?php $sortable = isset($sortable) ? $sortable : false; ?>
<table class="table table-bordered table-hover table-hover table-striped table-responsive">
  <tr>
    <th>
      ID
    </th>
    <th>
      Người gửi
    </th>
    <th>
      Người nhận
    </th>
    <th>
      @if($sortable)
        {!! make_sort_link($transactions, $requesting, ['key' => 'wallet_type'], 'Loại tài khoản') !!}
      @else
        loại tài khoản
      @endif
    </th>
    <th>
    @if($sortable)
      {!! make_sort_link($transactions, $requesting, ['key' => 'amount'], 'Số tiền') !!}
    @else
      Số tiền
    @endif
    </th>
    <th>

      @if($sortable)
        {!! make_sort_link($transactions, $requesting, ['key' => 'order_code'], 'Mã đơn hàng') !!}
      @else
        Mã đơn hàng
      @endif

    </th>
    <th>
      @if($sortable)
        {!! make_sort_link($transactions, $requesting, ['key' => 'updated_at'], 'Cập nhật') !!}
      @else
        Cập nhật
      @endif

    </th>
  </tr>
  @foreach($transactions as $_transaction)
    <tr>
      <td>{{$_transaction->id}}</td>
      <td class="text-info" title="Sau giao dịch {{human_money($_transaction->from_acc_remain, '0đ')}}">
        {{$_transaction->from_acc > 0 ? $_transaction->fromUser->email : 'System'}}</td>
      <td class="text-info" title="Sau giao dịch {{human_money($_transaction->to_acc_remain, '0đ')}}">
        {{$_transaction->to_acc > 0 ? $_transaction->toUser->email : 'System'}}</td>
      <td>{{trans('money.wallet_types_short.' . $_transaction->acc_type)}}</td>
      <td class="text-right">{{human_money($_transaction->amount, '0đ')}}</td>
      <td><a href='{{route('backend.money.orders.detail', ['order_id' => $_transaction->order->id])}}'>{{$_transaction->order->code}}</a></td>
      <td title="Khởi tạo {{$_transaction->created_at}}">{{$_transaction->updated_at}}</td>
    </tr>
  @endforeach
</table>
@if($transactions instanceof \Illuminate\Pagination\Paginator)
<div class="pagination">
  {!! $transactions->render() !!}
  <form name="jump_to_page" class="">
    {!! make_hidden_fields(\Request::except('page')) !!}
    <input name="page" placeholder="Trang" class="" />
    <input type="submit" value="Go">
  </form>
</div>
@endif