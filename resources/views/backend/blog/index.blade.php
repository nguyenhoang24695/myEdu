@extends ('backend.layouts.master')

@section ('title', 'Quản lý Blog')

@section('page-header')
    <h1>
        Quản lý Blog
    </h1>
@endsection

@section ('breadcrumbs')
    <li><a href="{!!route('backend.dashboard')!!}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
    <li class="active">Quản lý Blog</li>
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
              <option {{ ($module == "list") ? "selected":"" }} value="{{ route('blog.module',['module'=>'list']) }}">Tất cả</option>
              <option {{ ($module == "pending") ? "selected":"" }} value="{{ route('blog.module',['module'=>'pending']) }}">Chờ duyệt</option>
              <option {{ ($module == "active") ? "selected":"" }} value="{{ route('blog.module',['module'=>'active']) }}">Đã duyệt</option>
              <option {{ ($module == "delete") ? "selected":"" }} value="{{ route('blog.module',['module'=>'delete']) }}">Đã xóa</option>
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
                      <input type="text" name="search" class="form-control input-sm pull-right" placeholder="Nhập tiêu đề">
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
                            <th>Tiêu đề</th>
                            <th class="visible-lg">Được tạo</th>
                            <th class="visible-lg">Cập nhật</th>
                            <th style="text-align: center;width: 90px;">Trạng thái</th>
                            <th style="text-align: center;width: 20px;">Hot</th>
                            <th style="text-align: center;width: 20px;">Active</th>
                            <th style="text-align: center;width: 70px;">Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                            @foreach ($blog as $key => $value)
                                <tr>
                                    <td>{!! $value->id !!}</td>
                                    <td>{!! $value->blo_title !!}</td>
                                    <td class="visible-lg">{!! $value->created_at->diffForHumans() !!}</td>
                                    <td class="visible-lg">{!! $value->updated_at->diffForHumans() !!}</td>
                                    <td style="text-align: center;">
                                        @if ($value->public == 1)
                                            <span class="label label-success">Công khai</span>
                                        @elseif ($value->public == 2)
                                            <span class="label label-info">Ẩn danh</span>
                                        @else
                                            <span class="label label-default">Riêng tư</span>
                                        @endif
                                    </td>
                                    <td style="text-align: center;" >
                                        @if ($module != "delete")
                                            <input value="{{ $value->hot }}" url="{{ route('blog.hot', $value->id) }}" type="checkbox" class="js-switch {{ ($value->public == 1) ? 'js-check-change':'js-check-disabled' }}" />
                                        @endif
                                    </td>
                                    <td style="text-align: center;" >
                                        @if ($module != "delete")
                                            <input value="{{ $value->blo_active }}" url="{{ route('blog.active', $value->id) }}" type="checkbox" class="js-switch {{ ($value->public == 1) ? 'js-check-change':'js-check-disabled' }}" />
                                        @endif
                                    </td>
                                    <td style="text-align: center">
                                        @if ($module != "delete")
                                            <a href="{{ route('backend.blog.destroy', $value->id) }}" class="btn btn-xs btn-danger btn-delete-action">
                                                <i class="fa fa-trash" data-toggle="tooltip" data-placement="top" title="" data-original-title="Xóa"></i>
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
            @include('backend.includes.pagination', ['data' => $blog, 'appended' => ['search' => Request::get('search')]])
            <div class="clearfix"></div>
        </div>
    </div>
@stop