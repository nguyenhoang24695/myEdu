<table class="table table-striped table-bordered">
  <tr>
    <th>
      UOID
    </th>
    <th>
      Loại đơn hàng
    </th>
    <th>
      Giá trị
    </th>
    <th>
      Người bán
    </th>
    <th>
      Người mua
    </th>
    <th>
      Cập nhật
    </th>
    <th>
      S/A
    </th>
  </tr>
  @foreach($orders as $order)
    <tr>
      <td>
        <a href="{{route('backend.money.orders.detail', ['order_id' => $order->id])}}"
           class="{{$order->note ? 'text-bold' : ''}}"
           data-toggle='tooltip'
           data-html="true"
           data-placement="right"
           title="{{nl2br($order->note)}}">
          {{$order->id}}
        </a>
      </td>
      <td>{{$order->type_string . " - " . $order->item_name}}({{$order->payment_method}})</td>
      <td class="text-danger">{{human_money($order->item_price, '0 đ')}}</td>
      <td class="text-bold text-info">{{$order->sellingUser->name}}</td>
      <td class="text-bold text-info">{{$order->buyingUser->name}}</td>
      <td title="Created {{$order->created_at}}">{{$order->updated_at}}</td>
      <td>
        <div class="btn-group">
          <button type="button" class="btn btn-xs btn-default">{{$order->status_string}}</button>
          <button type="button" class="btn btn-xs btn-primary dropdown-toggle" data-toggle="dropdown">
            <span class="sr-only">Action</span>
            <span class="caret"></span>
          </button>
          <ul class="dropdown-menu" role="menu">
            @if($order->status == \App\Core\Money\Utils\Constant::PENDING_ORDER)
            <li>
              <a href="{{route('backend.money.orders.detail', ['order_id' => $order->id, 'action' => 'approve'])}}">
                Xác nhân
              </a>
            </li>
            <li>
              <a href="{{route('backend.money.orders.detail', ['order_id' => $order->id, 'action' => 'reject'])}}">
                Hủy
              </a>
            </li>
            @elseif($order->status == \App\Core\Money\Utils\Constant::APPROVED_ORDER)
              <li>
                <a href="{{route('backend.money.orders.detail', ['order_id' => $order->id, 'action' => 'revert'])}}">
                  Revert
                </a>
              </li>
            @endif
          </ul>
        </div>
      </td>
    </tr>
  @endforeach
</table>