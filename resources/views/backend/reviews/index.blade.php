@extends ('backend.layouts.master')

@section ('title', 'Quản lý Reviews')

@section('page-header')
    <h1>
        Quản lý Reviews
    </h1>
@endsection

@section ('breadcrumbs')
    <li><a href="{!!route('backend.dashboard')!!}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
    <li class="active">Quản lý Reviews</li>
@stop

@section('content')
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title">Danh sách Reviews.</h3>
            <div class="box-tools">
                
            </div>
        </div>
        <div class="box-body">
            <div class="row">
                <div class="col-sm-12">
                    <table class="table table-striped table-bordered table-hover">
                        <thead>
                        <tr>
                            <th style="width: 50px;">ID</th>
                            <th>Nội dung</th>
                            <th style="width: 250px;">Thông tin</th>
                            <th style="width: 100px;">Được tạo</th>
                            <th style="text-align: center;width: 20px;">Active</th>
                            <th style="text-align: center;width: 70px;">Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                            @foreach ($list_reviews as $key => $value)
                                <tr>
                                    <td>{!! $value->id !!}</td>
                                    <td>{!! $value->rev_content !!}</td>
                                    <td>
                                        <p><strong>Tên khóa học : </strong><a target="_blank" href="{{ $value->course->get_registered_view_link() }}">{{ $value->course->cou_title }}</a></p>
                                        <p><strong>Đánh giá : </strong>{!! $value->rating !!} Sao</p>
                                        <p><strong>Người đánh giá : </strong><a target="_blank" href="@if(isset($value->user->name)){{ $value->user->showLinkProfile() }}@endif">@if(isset($value->user->name)){{$value->user->name}}@endif</a></p>
                                    </td>
                                    <td class="visible-lg">{!! $value->created_at->diffForHumans() !!}</td>
                                    <td style="text-align: center;" >
                                        <input value="{{ $value->rev_active }}" url="{{ route('reviews.active', $value->id) }}" type="checkbox" class="js-switch js-check-change" />
                                    </td>
                                    <td style="text-align: center">
                                        <a href="{{ route('reviews.destroy', $value->id) }}" class="btn btn-xs btn-danger btn-delete-action">
                                            <i class="fa fa-trash" data-toggle="tooltip" data-placement="top" title="" data-original-title="Xóa"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <!--Phân trang-->
            @include('backend.includes.pagination', ['data' => $list_reviews, 'appended' => ['search' => Request::get('search')]])
            <div class="clearfix"></div>
        </div>
    </div>
@stop