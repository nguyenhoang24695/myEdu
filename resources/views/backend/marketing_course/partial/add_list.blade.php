{!! Form::open() !!}
<div>
  <button class="btn btn-xs btn-primary">Thêm các khóa đánh dấu</button>
</div>
<table class="table table-bordered">
  <tr>
    <th>{!! Form::checkbox('select_all') !!}</th>
    <th>ID</th>
    <th>Tên</th>
    <th>Tác giả</th>
    <th>Giá</th>
    <th>Cập nhật</th>
  </tr>
@foreach($courses as $course)
    <tr>
      <td>{!! Form::checkbox('ids[]', $course->id) !!}</td>
      <td>{{$course->id}}</td>
      <td><a href="{{$course->get_public_view_link()}}" target="_blank"> {{$course->cou_title}}</a></td>
      <td>{{$course->user->name or $course->user->fullname}}</td>
      <td>{{human_money($course->price)}}</td>
      <td title="Tạo : {{$course->created_at}}">
        {{$course->updated_at}}
      </td>
    </tr>
  @endforeach
</table>
<div>
  <button class="btn btn-xs btn-primary">Thêm các khóa đánh dấu</button>
</div>
{!! Form::close() !!}