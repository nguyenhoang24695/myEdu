@extends('frontend.layouts.default', ['has_videojs' => true])

@section('after-styles-end')
  {!! HTML::style('frontend/plugin/summernote/summernote.css') !!}
  {!! HTML::style('frontend/plugin/select2/select2.css') !!}
  {!! HTML::style('frontend/plugin/select2/select2-bootstrap.css') !!}
@endsection

@section('before-scripts-end')
  {!! HTML::script('frontend/plugin/simple_ajax_upload.js') !!}
  {!! HTML::script('frontend/plugin/summernote/summernote.js') !!}
  {!! HTML::script('frontend/plugin/jquery_sortable.js') !!}
  {!! HTML::script('frontend/plugin/select2/select2.min.js') !!}
  @endsection

@section('after-scripts-end')
  {!! HTML::script('frontend/js/teacher/course/template.js') !!}
  {!! HTML::script('frontend/js/teacher/course/edit_lecture_plugin.js') !!}
  {!! HTML::script('frontend/js/teacher/course/sortable.js') !!}
  {!! HTML::script('frontend/js/teacher/course/edit_content.js') !!}
  {!! HTML::script('frontend/js/teacher/course/quizzes.js') !!}
  @endsection

@section('content')
  <div class="container mr_top_3">
    <div class="row">

      @include('frontend.includes.course_building_sidebar')

      <div class="col-sm-9 col-md-9  profile-private main main_lecture">
        <div class="wrap_main">
          <section>
            <div class="panel unibee-box">
              <div class="panel-heading">
                <h3 class="panel-title">Nội dung giảng dạy</h3>
              </div>
              <div class="panel-body no-padding">
              </div>
            </div>

            @include('frontend.includes.course_tiny_info')

            <div class="panel">
              <div class="panel-body" id="build_course_content">
                <ol class="content_list my_sortable" data-id="{{$course->id}}">
                  @foreach($course_contents as $a_content)
                    @if($a_content->get_type() == config('course.content_types.section'))
                      <li class="a_section content_item" id="ct-{{$a_content->id}}" data-id="{{$a_content->id}}">
                        @include('frontend.teacher.course.building.a_section_view', ['content' => $a_content->getContent()])
                      </li>
                    @elseif($a_content->get_type() == config('course.content_types.lecture'))
                      <li class="a_lecture content_item" id="ct-{{$a_content->id}}" data-id="{{$a_content->id}}">
                        @include('frontend.teacher.course.building.a_lecture_view', ['content' => $a_content->getContent(), 'course_content' => $a_content])
                      </li>
                    @elseif($a_content->get_type() == config('course.content_types.quizzes'))
                    <li class="a_quizzes content_item" id="ct-{{$a_content->id}}" data-id="{{$a_content->id}}">
                      @include('frontend.teacher.course.building.a_quizzes_view', ['content' => $a_content->getContent(), 'course_content' => $a_content])
                    </li>
                    @endif
                  @endforeach
                </ol>
                <div class="new_lecture_form a_lecture closed">
                  @include('frontend.teacher.course.building.a_lecture_form',['lecture_id' => 0, 'lecture_name' => ''])
                </div>
                <div class="new_section_form a_section closed">
                  @include('frontend.teacher.course.building.a_section_form', [])
                </div>
                <div class="new_quizzes_form a_lecture a_quizzes closed">
                  @include('frontend.teacher.course.building.a_quizzes_form', [])
                </div>
                <div class="">
                  <div class="row">
                    <div class="col-xs-6">
                      <button id="add_lecture_button" class="btn btn-default btn-block">
                        + Thêm bài học
                      </button>
                    </div>
                    <div class="col-xs-6">
                      <button id="add_quizzes_button" class="btn btn-default btn-block">
                        + Thêm bài kiểm tra
                      </button>
                    </div>
                  </div>
                  <div class="row" style="padding-top:10px;">
                    <div class="col-xs-12">
                      <button id="add_section_button" class="btn btn-primary btn-block">
                        + Thêm chương
                      </button>
                    </div>
                  </div>
                </div>
                @include('includes.partials.import_youtube')
              </div>
            </div>

          </section><!-- /section -->
        </div>
      </div>
    </div>
  </div> <!-- /container -->
@endsection