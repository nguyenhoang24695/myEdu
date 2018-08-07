@extends ('backend.layouts.master')

@section ('title', 'Quản lý mã code')

@section('page-header')
    <h1>
        Quản lý mã code
    </h1>
@endsection

@section ('breadcrumbs')
    <li><a href="{!!route('backend.dashboard')!!}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
    <li class="active">Quản lý mã code</li>
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
              <option {{ ($module == "list") ? "selected":"" }} 
              value="{{ route('backend.code.module',['module'=>'pending']) }}">Đang hoạt động</option>
              <option {{ ($module == "pause") ? "selected":"" }} 
              value="{{ route('backend.code.module',['module'=>'pause']) }}">Tạm ngừng</option>
              <option {{ ($module == "deleted") ? "selected":"" }} 
              value="{{ route('backend.code.module',['module'=>'deleted']) }}">Đã xóa</option>
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
                      <input type="text" name="search" class="form-control input-sm pull-right" placeholder="Nhập mã code">
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
                            <th>Mã code</th>
                            <th>% Chiết khấu</th>
                            <th>Số người sử dụng</th>
                            <th>Tổng tiền</th>
                            <th>Chủ sở hữu</th>
                            <th class="visible-lg">Được tạo</th>
                            <th class="visible-lg">Cập nhật</th>
                            <th style="text-align: center;width: 20px;">Active</th>
                            <th style="text-align: center;width: 70px;">Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                            @foreach ($all_code as $code)
                                <tr>
                                    <td>{!! $code->id !!}</td>
                                    <td>{!! $code->code !!}</td>
                                    <td>
                                    	<p>Chiết khấu (MAX) : <strong>({{ $code->discount_max }}%)</strong></p>
                                    	<p>Chiết khấu (1) : <strong>({{ $code->discount_1 }}%)</strong></p>
                                    	<p>Chiết khấu (2) : <strong>({{ $code->discount_2 }}%)</strong></p>
                                    </td>
                                    <td>{{ $code->used_count }}</td>
                                    <td>{{ number_format($code->total_money) }}</td>
                                    <td>
                                    	<a 
                                    	target="_blank" 
                                    	href="{{ $code->user ? $code->user->showLinkProfile() : '' }}">{{ $code->user ? $code->user->name : 'Không rõ' }}</a>
                                    </td>
                                    <td class="visible-lg">{!! $code->created_at !!}</td>
                                    <td class="visible-lg">{!! $code->updated_at !!}</td>
                                    <td style="text-align: center;" >
                                        @if ($module != "deleted")
                                            <input value="{{ $code->active }}" url="{{ route('backend.code.active', $code->id) }}" type="checkbox" class="js-switch js-check-change" />
                                        @endif
                                    </td>
                                    <td style="text-align: center">
                                        @if ($module != "deleted")
                                            <a href="{{ route('backend.code.destroy', $code->id) }}" class="btn btn-xs btn-danger btn-delete-action">
                                                <i class="fa fa-trash" data-toggle="tooltip" data-placement="top" title="" data-original-title="Xóa mã code"></i>
                                            </a>
                                        @else
                                            <a href="{{ route('backend.code.restore', $code->id) }}" class="btn btn-xs btn-info">
                                                <i class="fa fa-refresh" data-toggle="tooltip" data-placement="top" title="" data-original-title="Khôi phục mã code"></i>
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
            @include('backend.includes.pagination', ['data' => $all_code, 'appended' => ['search' => Request::get('search')]])
            <div class="clearfix"></div>
        </div>
    </div>
@stop