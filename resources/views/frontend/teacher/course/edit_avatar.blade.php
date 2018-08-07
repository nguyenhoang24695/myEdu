@extends('frontend.layouts.default', ['has_videojs' => true])

@section('after-styles-end')
  {!! HTML::style('frontend/css/lecture.css') !!}
@endsection

@section('after-scripts-end')
  {!! HTML::script('frontend/plugin/simple_ajax_upload.js') !!}
  {!! HTML::script('frontend/js/teacher/course/edit_avatar.js') !!}
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
                <h3 class="panel-title">Ảnh đại diện</h3>
              </div>
              <div class="panel-body no-padding">
              </div>
            </div>
            @include('frontend.includes.course_tiny_info')

            <div class="panel">
              <div class="panel-body ">
                <p>Mô tả trực quan hơn nội dung khóa học của bạn</p>
                <input type="hidden" id="course_id" value="{{$course->id}}"/>
                <div id="dopable_zone" style="border: 1px solid #f1f1f1; height: 200px; width: 100%;" class="text-center">
                  <br/><br/><br/>
                  <p><i class="fa fa-image fa-3x"></i> </p>
                  Kéo ảnh vào đây hoặc <button id="browse_button" class="btn btn-primary btn-xs">Chọn file</button>
                  <div id="error_message_zone" class="text-danger" style="display: none;">
                    {{trans('validation.upload_notice',
                              ['size' => config('flysystem.max_size.image')/1024/1024 . "MB",
                               'exts' => implode(',',config('flysystem.exts.image'))])}}
                  </div>
                  <div>
                    <span id="selected_file_name"></span><span id="selected_file_size"></span><span id="process_percent"></span>
                  </div>
                </div>
                <br/>
                <button type="submit" id="save_avatar" class="btn btn-primary btn_save ">{{trans('common.save')}}</button>


              </div>
            </div>

          </section> <!-- /section -->

        </div>
      </div>
    </div>
  </div> <!-- /container -->
@endsection