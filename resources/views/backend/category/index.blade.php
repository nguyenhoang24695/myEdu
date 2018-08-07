@extends ('backend.layouts.master')

@section ('title', trans('admin.category_manage'))

@section('after-styles-end')
@endsection

@section('after-scripts-end')
  {!! HTML::script('backend/plugin/select2/js/select2.js') !!}
  {!! HTML::script(asset('backend/js/category_manage.js')) !!}
@endsection

@section('page-header')
  <h1>
    {{trans('admin.category_manage')}}
    <small>{{trans('common.list')}}</small>
  </h1>
@endsection

@section ('breadcrumbs')
  <li><a href="{!!route('backend.dashboard')!!}"><i class="fa fa-dashboard"></i> {{trans('admin.dashboard')}}</a></li>
  <li>{{trans('admin.category_manage')}}</li>
@stop

@section('content')
  <div class="margin-bottom">
    <form class="form-inline" name="category_search" method="post" action="">
      <input type="hidden" name="_token" value="{{ csrf_token() }}">
      <div class="form-group">
        <label class="sr-only">
          {{trans('common.status')}}
        </label>
        <select name="is_active" class="form-control">
          <option value="-1">{{trans('common.all')}}</option>
          <option {{request('is_active') == '1' ? "selected" : ""}} value="1">{{trans('common.is_active')}}</option>
          <option {{request('is_active') == '0' ? "selected" : ""}} value="0">{{trans('common.is_inactive')}}</option>
        </select>
      </div>
      <div class="form-group">
        <label class="sr-only" for="exampleInputEmail3">{{trans('common.keyword')}}</label>
        <input type="text" class="form-control" name="kw" value="{{request('kw')}}" placeholder="{{trans('common.keyword')}}">
      </div>
      <button class="btn btn-default">
        {{trans('common.search')}}
      </button>
    </form>
  </div>
  <div class="box">
    <div class="box-header with-border">
      <h3 class="box-title">
        {{trans('admin.category_manage')}}
      </h3>
      <div class="box-tools">
        <a class="btn btn-default" href="{{route('backend.category.create')}}"><i class="fa fa-plus"></i></a>
      </div>
    </div>
    <div class="box-body">
      <table id="category_list" class="table table-bordered">
        <thead>
        <tr>
          <th>{{trans('common.name')}}</th>
          <th class="text-center">{{trans('common.count_course_in_category')}}</th>
          <th class="text-center">Hot</th>
          <th class="text-center">{{trans('common.is_active')}}</th>
          <th class="text-center">{{trans('common.action')}}</th>
        </tr>
        </thead>
        <tbody>
        @for ($i = 0; $i < $total_categories; $i++)
          <tr id="row_{{$categories[$i]->id}}">
            <td>
              <a href="{{route('backend.category_detail', ['id' => $categories[$i]->id])}}">
                @if($is_searching)
                  {{$categories[$i]->cat_title}}
                @else
                  {{$tree_categories[$categories[$i]->id]}}
                @endif
              </a>

            </td>
            <td class="text-right">
              {{$categories[$i]->course_count}}
            </td>
            <td class="text-right">
              @if($categories[$i]->hot == 1)
                <span class="label label-success">ON</span>
              @else
                <span class="label label-default">OFF</span>
              @endif
            </td>
            <td class="text-right">
              <a class="toggle_status" href="javascript:void(0);" data-status="{{$categories[$i]->cat_active}}" data-link="{{route('backend.category.toggle_status', ['id' => $categories[$i]->id])}}">
                <span style="color: {{$categories[$i]->cat_active > 0 ? 'green' : 'red'}}"
                      class="glyphicon glyphicon-ok-sign"></span>
              </a>
            </td>
            <td class="text-right">

              @if(!$is_searching)
                <a href="javascript:void(0);" class="category-move btn btn-xs btn-default"
                   data-link="{{route('backend.category.check_moving',['id' => $categories[$i]->id, 'direction' => 'left'])}}">
                  <i class="fa fa-arrow-left" data-toggle="tooltip" data-placement="top" title="" data-original-title="LEFT"></i>
                </a>
                <a href="javascript:void(0);" class="category-move btn btn-xs btn-default"
                   data-link="{{route('backend.category.check_moving',['id' => $categories[$i]->id, 'direction' => 'up'])}}">
                  <i class="fa fa-arrow-up" data-toggle="tooltip" data-placement="top" title="" data-original-title="UP"></i>
                </a>
                <a href="javascript:void(0);" class="category-move btn btn-xs btn-default"
                   data-link="{{route('backend.category.check_moving',['id' => $categories[$i]->id, 'direction' => 'down'])}}">
                  <i class="fa fa-arrow-down" data-toggle="tooltip" data-placement="top" title="" data-original-title="DOWN"></i>
                </a>
                <a href="javascript:void(0);" class="category-move btn btn-xs btn-default"
                   data-link="{{route('backend.category.check_moving',['id' => $categories[$i]->id, 'direction' => 'right'])}}">
                  <i class="fa fa-arrow-right" data-toggle="tooltip" data-placement="top" title="" data-original-title="RIGHT"></i>
                </a>
              @endif

              <a href="{{route('backend.category.create', ['cat_id' => $categories[$i]->id])}}" class="btn btn-xs btn-default">
                <i class="fa fa-plus" data-toggle="tooltip" data-placement="top" title="" data-original-title="ADD"></i>
              </a>
              <a href="{{route('backend.category.edit', ['id' => $categories[$i]->id])}}" class="btn btn-xs btn-default">
                <i class="fa fa-pencil" data-toggle="tooltip" data-placement="top" title="" data-original-title="EDIT"></i>
              </a>
              <a href="javascript:void(0);" class="btn btn-xs btn-danger category-delete-button"
                 data-link="{{route('backend.category.delete',['id'=>$categories[$i]->id])}}" >
                <i class="fa fa-trash" data-toggle="tooltip" data-placement="top" title="" data-original-title="DELETE"></i>
              </a>
              {{--EDIT/DELETE/UP/DOWN/ADD_CHILD--}}
            </td>
          </tr>
        @endfor
        </tbody>
      </table>
    </div>
  </div>


  <div class="clearfix"></div>
  <div id="delete_confirm" class="modal fade modal-default" tabindex="1" data-width="760">
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
      <h3>{{trans('common.delete')}}</h3>
    </div>
    <div class="modal-body">
      <div class="row-fluid">
        <div class="span6">
          {{trans('common.delete')}}?
        </div>
      </div>
    </div>
    <div class="modal-footer">
      <button type="button" data-dismiss="modal" class="btn btn-default">{{trans('common.cancel')}}</button>
      <button type="button" class="btn btn-primary">{{trans('common.delete')}}</button>
    </div>
  </div>
@stop