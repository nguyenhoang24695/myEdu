@extends('frontend.layouts.studying')

@section('after-styles-end')
  {!! HTML::style('frontend/plugin/full_height.css') !!}
@endsection

@section('after-scripts-end')
  {!! HTML::script('frontend/js/active.js') !!}
@endsection

@section('content')
  <div class="row-sm-height">
    <div class="col-sm-6 col-md-8 col-lg-9 lecture-preview col-sm-height col-sm-top">
      <div class="row preview-container inside-full-height">
        
        <div class="top-control">
          <a href="{!! $course->get_public_view_link() !!}"> <span class="icom-mail-reply icon-next-back"></span> Quay lại</a>
          @if($prev_content)
            <a href="{{$course->get_default_studying_link($prev_content->id)}}">
              <span class="icom-upload icon-next-back"></span> Quay lại bài trước
            </a>
          @endif
        </div>

        <div class="lecture_content full-parent">
          @if($lecture && $lecture->hasContent())
            @if($lecture->getPrimaryData() )
              @if( $lecture->getPrimaryData()->get_media_type() == 'video' )
                @include('frontend.student.course.content.video', ['video' => $lecture->getPrimaryData(), 'cover_image' => $course->get_cached_image('cc_video_cover'), 'has_secondary' => $lecture->hasSecondaryData()])
              @endif
            @endif
            @if( $lecture->getSecondaryData() && $lecture->getSecondaryData()->get_media_type() == 'document')
              @include('frontend.student.course.content.document', ['document' => $lecture->getSecondaryData(), 'has_primary' => $lecture->hasPrimaryData()])
            @endif
          @elseif(!empty($quizzes))
            @include('frontend.student.course.content.quizzes', ['quizzes' => $quizzes, 'course_contents' => $course_contents, 'viewing_content' => $viewing_content])
          @elseif($section)
            @include('frontend.student.course.content.section', ['section' => $section, 'course_contents' => $course_contents, 'viewing_content' => $viewing_content])
          @elseif($viewing_content->external_sources)
            <?php \Log::alert($viewing_content->external_sources); ?>
            @include('frontend.student.course.content.external_source', ['external_sources' => $viewing_content->external_sources])
          @else
            @include('frontend.student.course.content.nothing')
          @endif

        </div>
        <div class="bottom-control">
          <a href="javascript:void(0);">
            <input type="checkbox" checked value="bật" class="js-switch js-check-change" />
           Tự động chuyển</a>

          @if($next_content)
            <a href="{{$course->get_public_studying_link($next_content->id)}}">
              <span class="icom-download2 icon-next-back"></span> Đến bài tiếp theo
            </a>
          @endif
          @if($link_course_building)
            &nbsp;&nbsp;<a href="{{$link_course_building}}" class=""> <i class="fa fa-edit"></i> {{trans('common.edit')}}</a>
          @endif
        </div>
      </div>
    </div>
    <div class="col-sm-6 col-md-4 col-lg-3 lecture-resource col-sm-height  col-sm-top">
      <span class="lecture-resource-toggle fa-angle-double-right"></span>

      <div class="row inside-full-height">
        <!-- Nav tabs -->
        <ul class="nav nav-tabs nav-justified nav-tabs-unibee bg-while studying_tabs studying-public" role="tablist">
          <li role="presentation" class="active">
            <a href="#lectures_list" aria-controls="lectures_list" role="tab" data-toggle="tab">
              <span class="icom-navicon-round"></span>
            </a>
          </li>
          <li role="presentation">
            <a href="#lecture_attachments" id="toggle_lecture_attachments" aria-controls="lecture_attachments" role="tab" data-toggle="tab">
              <span class="icom-info-circle"></span>
            </a>
          </li>
        </ul>

        <!-- Tab panes -->
        <div class="tab-content wiget_list_lecture public-content">
          <div role="tabpanel" class="tab-pane active" id="lectures_list">
            <ul class="list-lecture-item-success list-unstyled">
              <?php $lecture_count = 0; ?>
              @foreach($course_contents as $content)
                @if($content->get_type() == config('course.content_types.section'))
                  <li class="item-group">
                    <h4>{{$content->get_title()}}</h4>
                    <small>{{$content->get_sub_title()}}</small>
                  </li>
                @elseif($content->get_type() == config('course.content_types.lecture'))
                  <li class="{{ ($content->id == $viewing_content->id) ? 'active':'' }}">
                    <a
                      href="{{$content->accessPrivacy() == 'public' || $course->cou_price < 1 ? $course->get_public_studying_link($content->id) : route('idvg.login',['uri'=>base64_encode($course->get_public_view_link())])}}">
                      <span>
                        {{$content->get_title()}}
                        @if($content->accessPrivacy() != 'public' && $course->cou_price > 0)
                          <i class="fa fa-lock" title="Bài học không miễn phí, mua khóa học để xem đầy đủ"></i>
                        @endif
                      </span>
                      @if($content->getContent()->primary_data_type == config('course.lecture_types.video'))
                        <i class="glyphicon glyphicon-film"></i> <i class="time">{{human_timesize($content->getContent()->primary_data_length, false,"")}}</i>
                      @elseif($content->getContent()->primary_data_type == config('course.lecture_types.audio'))
                        <i class="glyphicon glyphicon-music"></i> <i class="time hidden">5 trang</i>
                      @endif
                      @if($content->getContent()->secondary_data_type == config('course.lecture_types.document'))
                        <span class="icom-file-text"></span>
                      @endif
                      {{-- @if($content->external_sources()->exists()) --}}
                      {{--<i class="fa fa-external-link"></i> --}}
                      {{--@endif--}}
                    </a>

                    <div class="icon-lec-success">
                      <span style="width: 0px;" id="cc_status_{{$content->id}}"></span>
                      <?php $lecture_count++; ?>
                      <i>{{$lecture_count < 10 ? '0' . $lecture_count : $lecture_count}}</i>
                    </div>
                  </li>
                @elseif($content->get_type() == config('course.content_types.quizzes'))
                  <li class="{{ ($content->id == $viewing_content->id) ? 'active':'' }}">
                    <a
                      href="{{$content->accessPrivacy() == 'public' || $course->cou_price < 1 ? $course->get_public_studying_link($content->id) : route('idvg.login',['uri'=>base64_encode($course->get_public_view_link())])}}">

                      <span>
                        {{$content->get_title()}}
                        @if($content->accessPrivacy() != 'public' && $course->cou_price > 0)
                          <i class="fa fa-lock" title="Bài học không miễn phí, mua khóa học để xem đầy đủ"></i>
                        @endif
                      </span>
                      <i class="fa fa-check-square"></i>
                    </a>
                    <div class="icon-lec-success">
                      <span style="width: 0px;" id="cc_status_{{$content->id}}"></span>
                        <?php $lecture_count++; ?>
                      <i>{{$lecture_count < 10 ? '0' . $lecture_count : $lecture_count}}</i>
                    </div>
                  </li>
                @endif
              @endforeach
            </ul>
          </div>
          <div role="tabpanel" class="tab-pane" id="lecture_attachments">
            @include('frontend.student.course.content.lecture_info', ['course_content' => $viewing_content])
            <div class="col-xs-12">
              <h4>Thông tin khóa học</h4>
              <h4>{!! $course->cou_title !!}</h4>
              <h4 class="text-primary">Giá : {!! human_money($course->cou_price) !!}</h4>
              <div>
                {!! $course->cou_summary !!}
              </div>
              <h4>Tags</h4>
              <div>
                {{$course->course_tags}}
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

@endsection
