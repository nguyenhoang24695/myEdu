@extends ('backend.layouts.master')

@section ('title', trans('admin.category_manage'))

@section('page-header')
  <h1>
    {{trans('admin.category_manage')}}
    <small>{{trans('common.list')}}</small>
  </h1>
@endsection

@section ('before-styles-end')
  {!! HTML::style('css/plugin/jquery.onoff.css') !!}
  {!! HTML::style('backend/plugin/select2/css/select2.css') !!}
@stop

@section('after-scripts-end')
  {!! HTML::script('backend/plugin/select2/js/select2.js') !!}
  {!! HTML::script('backend/js/category_manage.js') !!}
@endsection

@section ('breadcrumbs')
  <li><a href="{!!route('backend.dashboard')!!}"><i class="fa fa-dashboard"></i> {{trans('admin.dashboard')}}</a></li>
  <li><a href="{{route('backend.category_index')}}">
      {{trans('admin.category_manage')}}
    </a>
  </li>
  <li>{{trans('common.add')}}</li>
@stop

@section('content')
  <form action="{{route('backend.category.create_save')}}" method="post" class="form-horizontal"  enctype="multipart/form-data">
    <input type="hidden" name="_token" value="{{csrf_token()}}" />
    <div class="margin-bottom">
      <a href="{{route('backend.category_index')}}" class="btn btn-default">
        <span class="glyphicon glyphicon-list"></span> {{trans('admin.category_manage')}}</a>
      <button type="submit" class="btn btn-default">
        <span class="glyphicon glyphicon-save"></span>{{trans('common.save')}}
      </button>
    </div>

    <div class="form-group {{ $errors->has('cat_title') ? 'has-error' : '' }}">
      <label class="col-lg-2 control-label">Category name</label>
      <div class="col-lg-10">
        {!! Form::text('cat_title', null, ['class' => 'form-control', 'placeholder' => 'Category name']) !!}
        {!! $errors->first('cat_title', '<label class="control-label" for="inputError">:message</label>') !!}
      </div>
    </div><!--form control-->

    <div class="form-group">
      <label class="col-lg-2 control-label">Parent</label>
      <div class="col-lg-10">
        {!! Form::select('parent_id', $category_list,$parent_id , ['class' => 'form-control', 'id' => 'select_category']) !!}
      </div>
    </div>

    <div class="form-group">
      <label class="col-lg-2 control-label">Ảnh đại diện</label>
      <div class="col-lg-10">
        <input type="file" name="avata_path" class="form-control">
      </div>
    </div>

    <div class="form-group">
      <label class="col-lg-2 control-label"></label>
      <div class="col-lg-10">
        <div class="checkbox">
          <label>
            <input type="checkbox" name="hot" value="1"> Hiển thị ngoài trang chủ
          </label>
        </div>
      </div>
    </div>
    <div class="clearfix"></div>

  </form>
@stop