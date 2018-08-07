@extends('frontend.teacher.library.layout')

@section('after-scripts-end')
  {!! HTML::script('frontend/js/library.js') !!}
@stop

@section('sub_content')
  <div class="panel wiget_box">
    <div class="panel-heading">
      <h3 class="panel-title">{{$video->vid_title}}</h3>
    </div>
    <div class="panel-body ">
      <div class="col-md-12">
        <video width="100%" height="300px" controls>
          <source src="{{route('api.video.stream',['id' => $video->id])}}" type="video/mp4">
          Your browser does not support the video tag.
        </video>
      </div>
      <div class="col-md-12">
        {!! Form::open(['method' => 'post', 'class' => 'form form-group-sm', 'files' => true]) !!}
        <div class="form-group clearfix {{ $errors->has('vid_title') ? 'has-error' : '' }}">
          <label class="col-md-2 control-label">{{trans('common.name')}}</label>
          <div class="col-md-10">
            {!! Form::text('vid_title', $video->vid_title, ['class' => 'form-control', 'placeholder' => trans('common.name')]) !!}
            {!! $errors->first('vid_title', '<label class="control-label" for="inputError">:message</label>') !!}
          </div>
        </div>
        <div class="form-group clearfix {{ $errors->has('vid_description') ? 'has-error' : '' }}">
          <label class="col-md-2 control-label">{{trans('common.description')}}</label>
          <div class="col-md-10">
            {!! Form::textarea('vid_description',  $video->vid_description, ['class' => 'form-control', 'rows' => 5, 'placeholder' => trans('common.description')]) !!}
            {!! $errors->first('vid_description', '<label class="control-label" for="inputError">:message</label>') !!}
          </div>
        </div>
        <div class="form-group clearfix {{ $errors->has('vid_subtitle') ? 'has-error' : '' }}">
          <label class="col-md-2 control-label">{{trans('common.subtitle')}}</label>
          <div class="col-md-10">
            {!! Form::file('vid_subtitle',  ['class' => 'form-control', 'placeholder' => trans('common.subtitle')]) !!}
            {!! $errors->first('vid_subtitle', '<label class="control-label" for="inputError">:message</label>') !!}
            @if($video->base_sub_path != "")
              <a href="{{$video->get_sub_link()}}">{{basename($video->base_sub_path)}}</a>
              @endif
          </div>
        </div>
        <div class="form-group text-center clearfix ">
          <button type="submit" class="btn btn-default" type="submit">{{trans('common.save')}}</button>
          <button type="reset" class="btn btn-default" type="submit">{{trans('common.reset')}}</button>
          <a class="btn btn-default" href="{{route('teacher.my_library.video', ['id' => $video->id])}}">{{trans('common.back')}}</a>
        </div>
        <div class="clearfix"></div>
        <ul>
          <li>
            Ngày tạo : {{$video->created_at}}
          </li>
          <li>
            Người tạo : {{$video->user()->first()->name}}
          </li>
        </ul>
        {!! Form::close() !!}
      </div>
    </div>
  </div>
@endsection