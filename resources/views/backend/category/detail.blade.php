@extends ('backend.layouts.master')

@section ('title', trans('admin.category_manage'))

@section('page-header')
    <h1>
        {{trans('admin.category_manage')}}
        <small>{{trans('common.list')}}</small>
    </h1>
@endsection

@section ('breadcrumbs')
    <li><a href="{!!route('backend.dashboard')!!}"><i class="fa fa-dashboard"></i> {{trans('admin.dashboard')}}</a></li>
    <li><a href="{{route('backend.category_index')}}">
            {{trans('admin.category_manage')}}
        </a>
    </li>
    <li>{{trans('common.detail')}}</li>
@stop

@section('content')
    <div class="margin-bottom">
        <a href="{{route('backend.category.course_list', ['cat_id' => $category->id])}}" class="btn btn-default">
            <span class="glyphicon glyphicon-list"></span> {{trans('admin.courses_in_category')}}</a>
        <a href="{{route('backend.category.delete',['id' => $category->id])}}" class="btn btn-default">
            <span class="glyphicon glyphicon-trash"></span>{{trans('common.delete')}}
        </a>
        <a href="{{route('backend.category.edit',['id' => $category->id])}}" class="btn btn-default">
            <span class="glyphicon glyphicon-pencil"></span>{{trans('common.edit')}}
        </a>
        <a href="{{route('backend.category.create',['cat_id' => $category->id])}}" class="btn btn-default">
            <span class="glyphicon glyphicon-plus"></span>{{trans('admin.add_child_category')}}
        </a>
    </div>
    <div class="">
        <div class="box box-default">
            <div class="box-header with-border">
                <h3 class="box-title">
                    {{$category->cat_title}}
                </h3>
            </div>
            <div class="box-header">
                <table class="table table-striped">
                    <tr>
                        <td>
                            <div class="col-sm-3 text-bold">
                                {{trans('common.name')}}
                            </div>
                            <div class="col-sm-9">
                                {{$category->cat_title}}
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="col-sm-3 text-bold">
                                {{trans('common.status')}}
                            </div>
                            <div class="col-sm-9">
                                {{$category->cat_active > 0 ? trans('common.is_active') : trans('common.is_inactive')}}
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="col-sm-3 text-bold">
                                {{trans('common.updated_at')}}
                            </div>
                            <div class="col-sm-9">
                                {{$category->updated_at}}
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="col-sm-3 text-bold">
                                {{trans('common.created_at')}}
                            </div>
                            <div class="col-sm-9">
                                {{$category->created_at}}
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="col-sm-3 text-bold">
                                {{trans('admin.category.parents')}}
                            </div>
                            <div class="col-sm-9">
                                @foreach($category_path as $parent)
                                    {{$parent->cat_title}} &gt;&gt;
                                @endforeach
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="col-sm-3 text-bold">
                                {{trans('admin.category.same_category')}}
                            </div>
                            <div class="col-sm-9">
                                @foreach($category_sibling as $sibling)
                                    <p>{{$sibling->cat_title}}</p>
                                @endforeach
                            </div>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>


    <div class="clearfix"></div>
@stop