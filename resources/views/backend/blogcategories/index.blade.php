@extends ('backend.layouts.master')

@section ('title', 'Quản lý danh mục Blog')

@section('page-header')
    <h1>
        Quản lý danh mục Blog
    </h1>
@endsection

@section ('breadcrumbs')
    <li><a href="{!!route('backend.dashboard')!!}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
    <li class="active">Quản lý danh mục Blog</li>
@stop

@inject('showcate','App\Http\Controllers\backend\BlogCategoriesController')

@section('content')
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title">Danh sách.</h3>
            <div class="box-tools">
                <form method="GET" action="">
                    <div class="input-group" style="width: 200px;">
                      <input type="text" name="search" class="form-control input-sm pull-right" placeholder="Nhập tên danh mục">
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
                            <th>Tên danh mục</th>
                            <th>Danh mục cha</th>
                            <th style="text-align: center;width: 20px;">Active</th>
                            <th style="text-align: center;width: 70px;">Actions</th>
                        </tr>
                        </thead>
                        <tbody>

                            @foreach ($blogcate as $key => $value)
                                <tr>
                                    <td>{!! $value->id !!}</td>
                                    <td>{!! $value->blc_title !!}</td>
                                    <td>
                                    {{ $showcate->show($value->blc_parent_id) }}
                                    </td>
                                    <td style="text-align: center;" >
                                        <input value="{{ $value->blc_active }}" url="{{ route('blogcate.active', $value->id) }}" type="checkbox" class="js-switch js-check-change" />
                                    </td>
                                    <td style="text-align: center">
                                        <a href="{{ route('blogcate.edit', $value->id) }}" class="btn btn-xs btn-default">
                                            <i class="fa fa-pencil" data-toggle="tooltip" data-placement="top" title="" data-original-title="Sửa"></i>
                                        </a>
                                        <a href="{{ route('blogcate.destroy', $value->id) }}" class="btn btn-xs btn-danger btn-delete-action">
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
            @include('backend.includes.pagination', ['data' => $blogcate, 'appended' => ['search' => Request::get('search')]])
            <div class="clearfix"></div>
        </div>
    </div>
@stop