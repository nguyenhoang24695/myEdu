@extends('frontend.teacher.library.layout')

@section('sub_content')
  <div class="panel wiget_box">
    <div class="panel-heading">
      <h3 class="panel-title">{{trans('course.add_audio')}}</h3>
    </div>
    <div class="panel-body ">
      <div class="row">
        {!! Form::open(['name' => 'frm_add_audio', 'class' => 'form-horizontal', 'method' => 'post', 'url' => route('teacher.my_library.add_audio')]) !!}
        <div class="col-xs-12">

          <div class="form-group {{ $errors->has('tmp_file') ? 'has-error' : '' }}">
            <label class="col-md-2 control-label">{{trans('common.file')}}</label>
            <div class="col-md-10">
              <a id="browse_file_upload" class="btn btn-default btn-xs">{{trans('common.select_file')}}</a>
            </div>
            <div class="col-md-offset-2" id="">
              <div class="container-fluid">
                <div class="progress">
                  <div id="upload_percent" class="progress-bar" role="progressbar" aria-valuenow="0"
                       aria-valuemin="0" aria-valuemax="100" style="width:0%">
                    <span class="" id="file_upload_percent"></span>
                  </div>
                </div>
                <div>Đã chọn : <span id="upload_file_name">{{old('file_name')}}</span></div>
                <div id="upload_notice" class="has-error" style="display: {{ $errors->has('tmp_file') ? 'block' : 'none' }}">
                  <label class="control-label">
                    {{trans('validation.upload_notice',
                                ['size' => config('flysystem.max_size.audio')/1024/1024 . "MB",
                                 'exts' => implode(',',config('flysystem.exts.audio'))])}}
                  </label>
                </div>
              </div>
            </div>
          </div>
          {!! Form::hidden('media_id', null, ['id' => 'media_id']) !!}
          <div class="form-group {{ $errors->has('aud_title') ? 'has-error' : '' }}">
            <label class="col-md-2 control-label">{{trans('common.name')}}</label>
            <div class="col-md-10">
              {!! Form::text('aud_title', null, ['class' => 'form-control', 'placeholder' => trans('common.name')]) !!}
              {!! $errors->first('aud_title', '<label class="control-label" for="inputError">:message</label>') !!}
            </div>
          </div>

          <div class="form-group {{ $errors->has('aud_description') ? 'has-error' : '' }}">
            <label class="col-md-2 control-label">{{trans('common.description')}}</label>
            <div class="col-md-10">
              {!! Form::textarea('aud_description', null, ['class' => 'form-control', 'rows' => 5, 'placeholder' => trans('common.description')]) !!}
              {!! $errors->first('aud_description', '<label class="control-label" for="inputError">:message</label>') !!}
            </div>
          </div>
          <div class="text-right">
            {!! Form::submit(trans('common.save'), ['class' => 'btn btn-default', 'id' => 'save_media_button','disabled' => 'disabled']) !!}
          </div>
        </div>
        {!! Form::close() !!}
      </div><!-- row -->
    </div>
  </div>
@endsection

@section('after-scripts-end')
  {!! HTML::script('frontend/plugin/simple_ajax_upload.js') !!}
  {!! HTML::script('frontend/js/teacher/add_audio.js') !!}
@endsection