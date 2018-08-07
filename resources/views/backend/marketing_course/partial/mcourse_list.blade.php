<table class="table table-bordered">
  <tr>
    <th>ID</th>
    <th>Tên</th>
    <th>Mô tả</th>
    <th>Tác giả</th>
    <th>Giá</th>
    <th>Cập nhật</th>
    <th>Action</th>
  </tr>
  @foreach($mcourses as $mcourse)
    <tr>
      <td>{{$mcourse->id}}</td>
      <td><a href="{{$mcourse->course->get_public_view_link()}}" target="_blank"> {{$mcourse->title}}</a></td>
      <td>{{$mcourse->description}}</td>
      <td>
        <a target="_blank"
          href="{{$mcourse->course->user->showLinkProfile()}}">
          {{$mcourse->course->user->name or $mcourse->course->user->fullname}}
        </a>
      </td>
      <td>{{human_money($mcourse->course->price)}}</td>
      <td>
        U:{{$mcourse->updated_at}}<br/>
        C:{{$mcourse->created_at}}
      </td>
      <td>
        <div class="btn-group-xs">
          <button class="btn btn-danger">
            Xóa
          </button>
          <button class="btn btn-xs btn-primary">
            Thống kê
          </button>
          <a class="btn btn-xs btn-info" href="{{route('backend.marketing_course.edit', ['ids' => $mcourse->id])}}">
            Sửa
          </a>
        </div>

      </td>
    </tr>
  @endforeach
</table>