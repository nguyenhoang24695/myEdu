@extends ('backend.layouts.master')

@section ('title', 'Tags')

@section('page-header')
  <h1>
    {{trans('common.list') . ' :: TAGS '}}
  </h1>
@endsection

@section('after-scripts-end')
  {!! HTML::script('backend/js/tag.js') !!}
  @endsection

@section ('breadcrumbs')
  <li><a href="{!!route('backend.dashboard')!!}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
  <li class="active">{{trans('common.list') . ' :: TAGS '}}</li>
@stop

@section('content')
  <div class="margin-bottom">
    Filter
  </div>
  <div class="box">
    <div class="box-body">
      {!! Form::open(['method' => 'post', 'class' => 'form']) !!}
      <div class="form-group">
        <label>Từ khóa</label>
        {!! Form::text('keyword', \Request::input('keyword', ''), ['class' => 'form-control']) !!}
      </div>
      <div class="text-center">
        <button class="btn btn-primary">{{trans('common.search')}}</button>
      </div>
      {!! Form::close() !!}
    </div>

  </div>
  <div class="box">
    <div class="box-body">
      <div class="row">
        <div class="col-sm-12">
          <table class="table table-striped table-bordered table-hover" style="font-size: 80%;">
            <thead>
            <tr>
              <th>Tên</th>
              <th>Slug</th>
              <th>Count</th>
              <th>Suggested</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($tags as $tag)
              <tr>
                <td><span id="tag_content_{{$tag->id}}">{{$tag->name}}</span>
                  <a href="javascript:void(0);" class="edit_tag_button" data-id="{{$tag->id}}"
                     data-slug="#tag_slug_{{$tag->id}}"
                     data-content="#tag_content_{{$tag->id}}">
                    <i class="fa fa-pencil"></i>
                  </a>
                </td>
                <td><span id="tag_slug_{{$tag->id}}">{{$tag->slug}}</span>
                  <a href="javascript:void(0);" class="update_tag_slug_button" data-id="{{$tag->id}}"
                     data-slug="#tag_slug_{{$tag->id}}"
                     data-content="#tag_content_{{$tag->id}}">
                    <i class="fa fa-refresh"></i>
                  </a>
                </td>
                <td>
                  <a href="{{route('backend.tags.detail', ['id' => $tag->id])}}">
                    {{$tag->count}}
                  </a>
                </td>
                <td>{{$tag->suggest}}</td>
              </tr>
            @endforeach
            </tbody>
          </table>
        </div>
      </div>
      <!--Phân trang-->
      @if(\Request::input('keyword', '') == '')
      {!! $tags->render() !!}
      @endif
      <div class="clearfix"></div>
    </div>
  </div>
@stop