<table class="table table-bordered">
  <tr>
    <td>Tên khóa học</td>
    <td>
      <a href="{{$course->get_public_view_link()}}" target="_blank">
        {{$course->cou_title}}
      </a>
    </td>
  </tr>
  <tr>
    <td>Giá hiện tại(có thể khác giá hóa đơn)</td>
    <td>
      {{human_money($course->cou_price, '0đ')}}
    </td>
  </tr>
  <tr>
    <td>Người tạo</td>
    <td>
      {{$course->user->name}}
    </td>
  </tr>
</table>