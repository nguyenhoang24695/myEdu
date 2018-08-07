@extends ('backend.layouts.master')

@section ('title', 'Quản lý thông tin đăng ký Partner')

@section('page-header')
    <h1>
        Quản lý thông tin đăng ký Partner
    </h1>
@endsection

@section ('breadcrumbs')
    <li><a href="{!!route('backend.dashboard')!!}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
    <li class="active">Quản lý thông tin đăng ký Partner</li>
@stop

@section('content')
    
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title">Danh sách.</h3>
        </div>
        <div class="box-body">
            <div class="row">
                <div class="col-sm-12">
                    <table class="table table-striped table-bordered table-hover">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Thành viên</th>
                            <th>Thông tin đăng ký</th>
                            <th class="visible-lg">Ngày đăng ký</th>
                            <th style="text-align: center;">Tạo mã code</th>
                            <th style="text-align: center;width: 70px;">Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                            @foreach ($partners as $partner)
                                <tr>
                                    <td>{!! $partner->id !!}</td>
                                    <td>
                                        <a 
                                        target="_blank" 
                                        href="{{ $partner->user ? $partner->user->showLinkProfile() : '' }}">{{ $partner->user ? $partner->user->name : 'Không rõ' }}</a>
                                    </td>
                                    <td>
                                        <h5>Phương thức giới thiệu khóa học</h5>
                                        <p>{{ ($partner->marketing_mouth != "") ? '+ '.$partner->marketing_mouth : "" }}</p>
                                        <p>{{ ($partner->marketing_website != "") ? '+ '.$partner->marketing_website : "" }}</p>
                                        @if ($partner->address_website != "")
                                            <p style="margin-left: 10px">- Địa chỉ website : <a href="{{ $partner->address_website }}"> {{ $partner->address_website }}</a></p>
                                        @endif
                                        <p style="margin-left: 10px">{{ ($partner->views_website != "") ? '- Lượt truy cập/Ngày: '.$partner->views_website : "" }}</p>
                                        <p>{{ ($partner->marketing_social != "") ? '+ '.$partner->marketing_social : "" }}</p>
                                        @if ($partner->marketing_social != "")
                                            <p style="margin-left: 10px">- Địa chỉ : <a href="{{ $partner->address_social }}"> {{ $partner->address_social }}</a></p>
                                        @endif
                                        <p>{{ ($partner->marketing_ads != "") ? '+ '.$partner->marketing_ads : "" }}</p>
                                        <p>{{ ($partner->marketing_other != "") ? '+ '.$partner->marketing_other : "" }}</p>
                                        @if ($partner->marketing_other != "")
                                            <p style="margin-left: 10px">- Chi tiết :  {{ $partner->marketing_other_detail }} </p>
                                        @endif
                                        <h5>Biết tới chường trình Partnership qua kênh</h5>
                                        <p>{{ '+ '.$partner->access }}</p>
                                    </td>
                                    <td class="visible-lg">{!! $partner->created_at !!}</td>
                                    <td style="text-align: center;" >
                                        @if ($partner->active == 0)
                                            <a href="{{ route('backend.partner.active', $partner->id) }}">Tạo mã code</a>
                                        @else
                                            <span class="label label-success">Đã tạo mã</span>
                                        @endif
                                    </td>
                                    <td style="text-align: center">
                                        <a href="{{ route('backend.partner.destroy', $partner->id) }}" class="btn btn-xs btn-danger btn-delete-action">
                                                <i class="fa fa-trash" data-toggle="tooltip" data-placement="top" title="" data-original-title="Xóa thông tin đăng ký"></i></a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <!--Phân trang-->
            @include('backend.includes.pagination', ['data' => $partners, 'appended' => ['search' => Request::get('search')]])
            <div class="clearfix"></div>
        </div>
    </div>
@stop