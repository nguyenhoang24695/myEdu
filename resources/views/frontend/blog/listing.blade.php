@extends('frontend.layouts.default')

@section('content')

<div class="container">
    <div class="row">
        <div class="col-md-3 aside unibee-aside">
            @include('frontend.user.includes.aside')
        </div>
        <div class="col-md-9 profile-private">
            <div class="wrap_main">
                <section>
                  <div class="panel unibee-box">
					<div class="panel-heading">
			        <h3 class="panel-title">Danh sách Blog</h3>
			      </div>
			      <div class="panel-body ">
			      	<a href="{{ url('blog/create') }}" class="btn btn-warning pull-left"><i class="fa fa-pencil"></i>Tạo blog mới</a>

			      	<form action="" accept-charset="utf-8" method="GET" class="frm-fillter">
                        <div class="form-group wrap_selectpicker pull-right">
                          <select class="selectpicker" onchange="window.location.href=this.value"  >
                            <option value="{{ url('/blog/listing') }}">Tùy chọn</option>
                            <option {{ (Request::get('public') == 'on') ? "selected":"" }} value="{{ url('/blog/listing').'?public=on' }}">Bài đã đăng</option>
                            <option {{ (Request::get('public') == 'off') ? "selected":"" }} value="{{ url('/blog/listing').'?public=off' }}">Bài cá nhân</option>
                          </select>
                        </div>
                      </form>
			      </div>
			   </div>
               <div class="line-pattern"></div>
                  <div class="panel">
                      <div class="panel-body ">
						<table class="table list-blog table-striped table-hover">
							<thead>
								<tr>
									<th>Tiêu đề</th>
									<th class="stt">Trạng thái</th>
									<th class="time">Ngày cập nhật</th>
									<th class="center">Sửa/Xóa</th>
								</tr>
							</thead>
							<tbody>
								@foreach ($blog as $key => $value)
									<tr>
										<td>
											{!! link_to_route('blog.show', $value->blo_title, [$value->id,str_slug($value->blo_title,'-')],['target'=>"_blank"]) !!}
										</td>
										<td>
											@if ($value->public == 1)
												<span class="label label-success">Công khai</span>
											@elseif ($value->public == 2)
												<span class="label label-info">Ẩn danh</span>
											@else
												<span class="label label-default">Riêng tư</span>
											@endif
										</td>
										<td>{{ $value->created_at }}</td>
										<td align="center">
											<a href="{{ url('/blog/'.$value->id.'/edit') }}"><i class="fa fa-pencil"></i></a>
											<a href="{{ url('/blog/'.$value->id.'/delete') }}" class="delete_blog"><i class="fa fa-trash"></i></a>
										</td>
									</tr>
								@endforeach
							</tbody>
						</table>
						<!--Phân trang-->
    					@include('frontend.includes.pagination', ['data' => $blog, 'appended' => ['search' => Request::get('search')]])
                      </div>
                  </div>
                </section>
            </div>
        </div>
    </div>
</div>

@endsection

@section('after-scripts-end')
	{!! HTML::script('frontend/js/blog.js') !!}
@endsection
