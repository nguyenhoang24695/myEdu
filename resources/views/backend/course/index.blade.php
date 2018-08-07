@extends ('backend.layouts.master')

@section ('title', 'Quản lý Khóa học')

@section('page-header')
    <h1>
        Quản lý Khóa học
    </h1>
@endsection

@section ('breadcrumbs')
    <li><a href="{!!route('backend.dashboard')!!}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
    <li class="active">Quản lý Khóa học</li>
@stop

@section('content')
    <div class="margin-bottom">
        <form class="form-inline">
          <input type="hidden" name="_token" value="{{ csrf_token() }}">
          <div class="form-group">
            <label class="sr-only">
              Trạng thái
            </label>
            <select class="form-control fillter_width_url">
              <option {{ ($module == "list") ? "selected":"" }} value="{{ route('backend.course.module',['module'=>'list']) }}">Tất cả</option>
              <option {{ ($module == "pending") ? "selected":"" }} value="{{ route('backend.course.module',['module'=>'pending']) }}">Chờ duyệt</option>
              <option {{ ($module == "active") ? "selected":"" }} value="{{ route('backend.course.module',['module'=>'activated']) }}">Đã duyệt</option>
              <option {{ ($module == "delete") ? "selected":"" }} value="{{ route('backend.course.module',['module'=>'deleted']) }}">Đã xóa</option>
            </select>
          </div>          
        </form>
    </div>
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title">Danh sách.</h3>
            <div class="box-tools">
                <form method="GET" action="">
                    <div class="input-group" style="width: 200px;">
                      <input type="text" name="search" class="form-control input-sm pull-right" placeholder="Nhập tên khóa học">
                      <div class="input-group-btn">
                        <button type="submit" class="btn btn-sm btn-default"><i class="fa fa-search"></i></button>
                      </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="box-body">
            <div class="row">
                <div class="col-sm-12">
                    <table class="table table-striped table-bordered table-hover">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Tên khóa học</th>
                            <th>Thông tin</th>
                            <th class="visible-lg">Được tạo</th>
                            <th class="visible-lg">Cập nhật</th>
                            <th style="text-align: center;width: 20px;">Active</th>
                            <th style="text-align: center;width: 70px;">Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                            @foreach ($course_list as $key => $value)
                                <tr>
                                    <td>{!! $value->id !!}</td>
                                    <td>
                                        <a target="_blank" href="{{ $value->get_public_view_link() }}">{!! $value->cou_title !!}</a>
                                    </td>
                                    <td>
                                        <p><small>Giá : <strong style="color: #f4645f">{{ ($value->cou_price > 0) ? number_format($value->cou_price).' VND':"Miễn phí" }}</strong></small></p>
                                    	<p><small>Danh mục : <a href="{{ route('category.show',['id'=>$value->category->id,'title'=>str_slug($value->category->cat_title,'-')]) }}" target="_blank">{{ $value->category->cat_title }}</a></small></p>
                                    	<p><small>Người tạo : <a target="_blank" href="{{ $value->user->showLinkProfile() }}">{{ $value->user->name }}</a></p>
                                        <p><small>Lượt xem : {{ $value->cou_views }}</small></p>
                                        <p><small>Đánh giá : {{ $value->rating }}</small></p>
                                    	  <p><a href="{{route('backend.course.update_counter', ['id' => $value->id])}}">{{trans('common.update')}}</a></p>
                                    </td>
                                    <td class="visible-lg">{!! $value->created_at->diffForHumans() !!}</td>
                                    <td class="visible-lg">{!! $value->updated_at->diffForHumans() !!}</td>
                                    <td style="text-align: center;" >
                                        @if ($module != "delete")
                                            <input value="{{ $value->cou_active }}" url="{{ route('backend.course.active', $value->id) }}" type="checkbox" class="js-switch {{ ($value->public_status == 1) ? 'js-check-change':'js-check-disabled' }}" />
                                        @endif
                                        
                                    </td>
                                    <td style="text-align: center">
                                        @if ($module != "delete")
                                            <a href="{{ route('backend.course.destroy', $value->id) }}" class="btn btn-xs btn-danger btn-delete-action">
                                                <i class="fa fa-trash" data-toggle="tooltip" data-placement="top" title="" data-original-title="Xóa"></i>
                                            </a>
                                            <a href="{{route('teacher.build_course', ['id' => $value->id, 'action' => config('course.build_actions.editContent')])}}" class="btn btn-xs btn-primary">
                                                <i class="fa fa-pencil" data-toggle="tooltip" data-placement="top" title="" data-original-title="Sửa"></i>
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <!--Phân trang-->
            @include('backend.includes.pagination', ['data' => $course_list, 'appended' => ['search' => Request::get('search')]])
            <div class="clearfix"></div>
          <div class="">
            <a href="{{route('backend.course.update_lecture_data_length')}}">Cập nhật lecture data length</a>
          </div>
        </div>
    </div>
@stop