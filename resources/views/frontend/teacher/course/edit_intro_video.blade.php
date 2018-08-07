@extends('frontend.layouts.default', ['has_videojs' => true])

@section('after-styles-end')
@endsection

@section('after-scripts-end')
  {!! HTML::script('frontend/plugin/simple_ajax_upload.js') !!}
  {!! HTML::script('frontend/js/teacher/course/edit_intro_video.js') !!}
@endsection

@section('content')
  <div class="container mr_top_3" xmlns="http://www.w3.org/1999/html">
    <div class="row">
      @include('frontend.includes.course_building_sidebar')
      <div class="col-sm-9 col-md-9 profile-private main main_lecture">
        <div class="wrap_main">

          <section>
            <div class="panel unibee-box">
              <div class="panel-heading">
                <h3 class="panel-title">Video giới thiệu</h3>
              </div>
              <div class="panel-body no-padding">
              </div>
            </div>
            @include('frontend.includes.course_tiny_info')

            <div class="panel">
              <div class="panel-body ">
                <p>Video mô tả nhanh nội dung, quá trình học khóa học, giúp học sinh dễ dàng quyết định có nên học khóa học này hay không.</p>
                <input type="hidden" id="course_id" value="{{$course->id}}"/>
                @if(isset($video))
                  @include('includes.partials.response_video_player', ['player_id' => 'video_preview_' . $video->id, 'cover_img' => $video->thumbnail_link('original'), 'stream_link' => $streams, 'autoplay' => false])
                @endif

                <div class="panel wiget_box">
                  <div class="panel-body ">
                    <div class="row">
                      {!! Form::open(['name' => 'frm_add_course',
                      'class' => 'form-horizontal',
                      'method' => 'post',
                      'url' => route('teacher.my_library.add_video_intro'),
                      'files' => true]) !!}
                      <div class="col-xs-12">

                        <div class="form-group {{ $errors->has('tmp_file') ? 'has-error' : '' }}">
                          <label class="col-md-2 control-label">{{trans('common.file')}}</label>
                          <div class="col-md-10">
                            <a id="browse_file_upload" class="btn btn-default btn-xs">{{trans('common.select_file')}}</a>
                          </div>

                          <div class="col-md-offset-2">
                            <div class="container-fluid">
                              <div class="progress" style="width:100%;">
                                <div id="upload_percent" class="progress-bar" role="progressbar" aria-valuenow="0"
                                     aria-valuemin="0" aria-valuemax="100" style="width:0%">
                                  <span class="" id="file_upload_percent"></span>
                                </div>
                              </div>
                              <div>Đã chọn : <span id="upload_file_name">{{old('file_name')}}</span></div>
                              <div id="upload_notice" class="has-error" style="display: {{ $errors->has('tmp_file') ? 'block' : 'none' }}">
                                <label class="control-label">
                                  {{trans('validation.upload_notice',
                                              ['size' => config('flysystem.max_size.video')/1024/1024 . "MB",
                                               'exts' => implode(',',config('flysystem.exts.video'))])}}
                                </label>
                              </div>
                            </div>
                          </div>
                        </div>

                        {!! Form::hidden('media_id', null, ['id' => 'media_id']) !!}
                        <div class="text-right">
                          {!! Form::submit(trans('common.save'), ['class' => 'btn btn-default', 'id' => 'save_media_button','disabled' => 'disabled']) !!}
                        </div>
                      </div>
                      {!! Form::close() !!}
                    </div><!-- row -->
                  </div>
                </div>
              </div>
            </div>

          </section> <!-- /section -->

        </div>
      </div>
    </div>
  </div> <!-- /container -->
@endsection