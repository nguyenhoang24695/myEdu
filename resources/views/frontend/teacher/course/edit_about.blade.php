@extends('frontend.layouts.default', ['has_videojs' => true])

@section('after-styles-end')
  {!! HTML::style('frontend/css/lecture.css') !!}
@endsection

@section('after-scripts-end')
  {!! HTML::script('frontend/js/teacher/course/edit_object.js') !!}
@endsection

@section('content')
  <div class="container mr_top_3">
    <div class="row">
      @include('frontend.includes.course_building_sidebar')
      <div class="col-sm-9 col-md-9 main main_lecture">
        <div class="wrap_main">

          <section>
            <div class="panel unibee-box">
              <div class="panel-heading">
                <h3 class="panel-title">Thông tin tóm tắt</h3>
              </div>
              <div class="panel-body no-padding">
              </div>
            </div>
            @include('frontend.includes.course_tiny_info')

            <div class="line_pattern"></div>

            <div class="panel wiget_box">
              <div class="panel-heading">
                <h3 class="panel-title">Giới thiệu về khóa học</h3>
                <p>Giúp học sinh thấy được lợi ích đạt được khi tham gia khóa học</p>
              </div>
              <div class="panel-body ">
                {!! Form::open(['class' => 'obj_course form-horizontal', 'method' => 'post', 'id' => 'frm_edit_summary']) !!}
                <div class="form-group {{ $errors->has('introduction') ? 'has-error' : '' }}">
                  <label class="col-md-3 control-label">
                    {{trans('course.introduction')}}
                  </label>
                  <div class="col-md-9">
                    {!! Form::textarea('introduction', $course->introduction,
                      ['class' => 'form-control', 'placeholder' => trans('course.introduction'), 'rows' => 5]) !!}
                    {!! $errors->first('introduction', '<label class="control-label" for="inputError">:message</label>') !!}
                  </div>
                </div>

                <button type="submit" class="btn_edus btn_edus_primary btn_save">{{trans('common.save')}}</button>
                </form>
              </div>
            </div>

          </section> <!-- /section -->

        </div>
      </div>
    </div>
  </div> <!-- /container -->
@endsection