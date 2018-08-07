@extends ('backend.layouts.master')

@section ('title', 'Yêu cầu đăng ký')

@section('page-header')
    <h1>
        Danh sách đăng ký
    </h1>
@endsection

@section ('breadcrumbs')
    <li><a href="{!!route('backend.dashboard')!!}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
    <li class="active">Danh sách đăng ký</li>
@stop

@section('content')
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title">Danh sách.</h3>
        </div>
        <div class="box-body">
            <div class="row">
                <div class="col-sm-12">
                    <table class="table table-condensed table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Thông tin</th>
                                <th>Địa chỉ</th>
                                <th>Khóa học</th>
                                <th class="visible-lg">Ngày đăng ký</th>
                                <th>Mã code</th>
                                <th>Trạng thái</th>
                                <th>Tác vụ</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($listings as $listing)
                                <tr>
                                    <td>{{ $listing->id }}</td>
                                    <td>
                                        <p class="no-margin"><strong>Tên:</strong> {{ $listing->contact_name }}</p>
                                        <p class="no-margin"><strong>Email:</strong> {{ $listing->contact_email }}</p>
                                        <p class="no-margin"><strong>Điện thoại:</strong> {{ $listing->contact_phone }}</p>
                                    </td>
                                    <td>
                                        <p class="no-margin"><strong>Địa chỉ:</strong> {{ $listing->contact_address }}</p>
                                    </td>
                                    <td>
                                        <p class="no-margin"><strong>Tên:</strong> {{ $listing->course->cou_title }}</p>
                                        <p class="no-margin"><strong>Giá:</strong> {{ $listing->course->cou_price }}</p>
                                    </td>
                                    <td>{{ Carbon\Carbon::parse($listing->created_at)->format('d-m-y H:i:s') }}</td>
                                    <td>
                                        @if ($listing->code == "")
                                            <a href="{{ route('backend.cod.active_cod', $listing->id) }}">Tạo mã code</a>
                                        @else
                                            <span class="label label-success">{{ $listing->code }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($listing->active == 0)
                                            <span class="label label-warning">Chưa kích hoạt</span>
                                        @else
                                            <span class="label label-success">Đã kích hoạt</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('backend.cod.destroy', $listing->id) }}" class="btn btn-xs btn-danger btn-delete-action">
                                            <i class="fa fa-trash" data-toggle="tooltip" data-placement="top" title="" data-original-title="Xóa thông tin đăng ký"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <!--Phân trang-->
            @include('backend.includes.pagination', ['data' => $listings, 'appended' => ['search' => Request::get('search')]])
        </div>
    </div>
@stop