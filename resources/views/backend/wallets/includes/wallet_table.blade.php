<table class="table table-striped">
  <thead>
  <tr>
    {{--<th>--}}
    {{--STT--}}
    {{--</th>--}}
    <th>
      {!! make_sort_link($wallets, $requesting, ['key' => 'id'], 'Mã TK ') !!}
    </th>
    <th>
      {!! make_sort_link($wallets, $requesting, ['key' => 'name'], ' Họ tên ') !!}
    </th>
    <th>
      {!! make_sort_link($wallets, $requesting, ['key' => 'email'], 'Email') !!}
    </th>
    <th>
      {!! make_sort_link($wallets, $requesting, ['key' => 'phone'], 'Số điện thoại') !!}
    </th>
    <th>
      {!! make_sort_link($wallets, $requesting, ['key' => 'created_at'], 'Thời gian tạo') !!}
    </th>
    <th>
      {!! make_sort_link($wallets, $requesting, ['key' => 'primary_wallet'], 'Số dư TK doanh thu') !!}
    </th>
    <th>
      {!! make_sort_link($wallets, $requesting, ['key' => 'secondary_wallet'], 'Số dư TK mua KH') !!}
    </th>
    <th>
      Tương tác
    </th>
  </tr>
  </thead>
  <tbody>
  @foreach($wallets as $wallet)
    <tr>
      {{--<td align="right">{{$wallet->id}}</td>--}}
      <td align="right">{{$wallet->id}}</td>
      <td>{{$wallet->full_name or $wallet->name}}</td>
      <td>{{$wallet->email}}</td>
      <td>{{$wallet->phone}}</td>
      <td>{{$wallet->created_at->format('d-m-Y')}}</td>
      <td align="right">{{$wallet->primaryAmount('view', 'đ')}}</td>
      <td align="right">{{$wallet->secondaryAmount('view', 'đ')}}</td>
      <td>{{$wallet->id}}</td>
    </tr>
  @endforeach
  </tbody>

</table>
<div class="pagination">
  {!! $wallets->appends(\Request::query())->render() !!}
  <form name="jump_to_page" class="">
    {!! make_hidden_fields(\Request::except('page')) !!}
    <input name="page" placeholder="Trang" class="" />
    <input type="submit" value="Go">
  </form>
</div>