@extends('frontend.layouts.studying')


@section('after-styles-end')
  {!! HTML::style('frontend/plugin/bootstrap3-editable/css/bootstrap-editable.css') !!}
  {!! HTML::style('frontend/plugin/full_height.css') !!}
  {!! HTML::style('frontend/plugin/quiz/css/quiz.css') !!}
@endsection

@section('after-scripts-end')
  {!! HTML::script('frontend/plugin/bootstrap3-editable/js/bootstrap-editable.js') !!}
  {!! HTML::script('frontend/plugin/quiz/js/quiz.js') !!}
  {!! HTML::script('frontend/js/student/studying.plugins.js') !!}
  {!! HTML::script('frontend/js/student/studying.js') !!}
  {!! HTML::script('frontend/js/active.js') !!}
  <script>
    $(function () {
        $('#quizzes').slickQuiz({
          json: jQuery.parseJSON(questionJson),
          preventUnanswered: true,
          events : {
            onCompleteQuiz: function(option){
              if(require_quiz){
                if(option.questionCount == option.score){
                  //Đánh dấu trạng thái
                  $.post(view_status_log_quiz, {content_id: current_content_id, view_token: view_token}, function(data){
                  });
                }
              } else {
                //Đánh dấu trạng thái
                $.post(view_status_log_quiz, {content_id: current_content_id, view_token: view_token}, function(data){
                });
              }
            }
          }
        });
    });
  </script>
@endsection

@section('content')
  <div class="preview-notice">
    Bạn đang tham gia học thử khóa học này. Thời gian còn lại 12:30
  </div>
  <div class="row-sm-height">
    <div class="col-sm-6 col-md-8 col-lg-9 lecture-preview col-sm-height col-sm-top">
      <div class="row preview-container inside-full-height">
        <div class="top-control">
          <a href="{{$link_course_portal}}" class=""> <span class="icom-mail-reply icon-next-back"></span> Quay lại</a>
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
                @include('frontend.student.course.content.video',
                ['video' => $lecture->getPrimaryData(),
                'cover_image' => $course->get_cached_image('cc_video_cover'),
                'has_secondary' => $lecture->hasSecondaryData()])
              @endif
            @endif
            @if( $lecture->getSecondaryData() && $lecture->getSecondaryData()->get_media_type() == 'document')
              @include('frontend.student.course.content.document', ['document' => $lecture->getSecondaryData(), 'has_primary' => $lecture->hasPrimaryData()])
            @endif
          @elseif($quizzes)
            @include('frontend.student.course.content.quizzes', ['quizzes' => $quizzes, 'course_contents' => $course_contents, 'viewing_content' => $viewing_content])
          @elseif($section)
            @include('frontend.student.course.content.section', ['section' => $section, 'course_contents' => $course_contents, 'viewing_content' => $viewing_content])
          @elseif($viewing_content->external_sources)
            @include('frontend.student.course.content.external_source', ['external_sources' => $viewing_content->external_sources])
          @else
            @include('frontend.student.course.content.nothing')
          @endif

        </div>
        <div class="bottom-control ">
          <a href="javascript:void(0);" >
            <input type="checkbox" checked value="bật" class="js-switch js-check-change" />
           Tự động chuyển</a>

          @if($next_content)
            <a href="{{$course->get_default_studying_link($next_content->id)}}">
              <span class="icom-download2 icon-next-back"></span> Đến bài tiếp theo
            </a>
          @endif
          @if($link_course_building)
            <a href="{{$link_course_building}}" class=""> <i class="fa fa-edit"></i> {{trans('common.edit')}}</a>
          @endif
        </div>
      </div>
    </div>
    <div class="col-sm-6 col-md-4 col-lg-3 lecture-resource col-sm-height  col-sm-top">
      <span class="lecture-resource-toggle fa-angle-double-right"></span>

      <div class="row inside-full-height">
        <!-- Nav tabs -->
        <ul class="nav nav-tabs nav-justified nav-tabs-unibee bg-while studying_tabs" role="tablist">
          <li role="presentation" class="active">
            <a href="#lectures_list" aria-controls="lectures_list" role="tab" data-toggle="tab">
              <span class="icom-navicon-round"></span>
            </a>
          </li>
          <li role="presentation">
            <a href="#lecture_attachments" id="toggle_lecture_attachments" aria-controls="lecture_attachments" role="tab" data-toggle="tab">
              <span class="icom-ios-chatboxes-outline"></span>
            </a>
          </li>
          <li role="presentation" >
            <a href="#lecture_discussion" aria-controls="lecture_discussion" role="tab" data-toggle="tab">
              <span class="icom-chatbox-working"></span>
            </a>
          </li>
          <li role="presentation" >
            <a href="#lecture_notes" id="toggle-note-tab" aria-controls="lecture_notes" role="tab" data-toggle="tab">
              <span class="icom-pencil"></span>
            </a>
          </li>
        </ul>

        <!-- Tab panes -->
        <div class="tab-content wiget_list_lecture">
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
                      href="{{$course->get_default_studying_link($content->id)}}">
                      <span>{{$content->get_title()}}</span>
                      @if($content->getContent()->primary_data_type == config('course.lecture_types.video'))
                      <i class="glyphicon glyphicon-film"></i> <i class="time">{{human_timesize($content->getContent()->primary_data_length, false, "--:--")}}</i>
                      @elseif($content->getContent()->primary_data_type == config('course.lecture_types.audio'))
                        <i class="glyphicon glyphicon-music"></i> <i class="time hidden">5 trang</i>
                        @endif
                      @if($content->getContent()->secondary_data_type == config('course.lecture_types.document'))
                        <span class="icom-file-text"></span>
                      @endif
                    </a>

                    <div class="icon-lec-success">
                      <span style="width: 0px;" id="cc_status_{{$content->id}}"></span>
                        <?php $lecture_count++; ?>
                      <i>{{$lecture_count < 10 ? '0' . $lecture_count : $lecture_count}}</i>
                    </div>
                  </li>
                @elseif($content->get_type() == config('course.content_types.quizzes'))
                  <li class="{{ ($content->id == $viewing_content->id) ? 'active':'' }}">
                    <a href="{{$course->get_default_studying_link($content->id)}}">
                      <span>{{$content->get_title()}}</span>
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
            <div class="pane">
              <div class="panel-body">
                 <span class="blink_me">Loading...</span>
              </div>
            </div>
          </div>
          <div role="tabpanel" class="tab-pane" id="lecture_discussion">
            <ul class="media-list discussion-list list-unstyled">
              <li class="media">
                <div class="media-left discussion_u_info">
                  <a class="avata" href="{{ Auth::user()->showLinkProfile() }}" title="{{ Auth::user()->name }}">
                    @if(Auth::user()->avatar_path != "")
                      <img src="{{ Auth::user()->showAvatar('ua_small') }}" alt="{{ Auth::user()->name }}"
                           class="media-object img-circle img-responsive">
                    @else
                      <img src="{{ Auth::user()->showDefaultAvatar() }}" alt="{{ Auth::user()->name }}"
                           class="media-object img-circle img-responsive">
                    @endif
                  </a>
                </div>
                <div class="media-body">
                  @include('frontend.student.course.includes.comment',['course_id' => $course->id, 'content_id' => $viewing_content ? $viewing_content->id : null])
                </div>
              </li>
            </ul>

            <ul class="media-list discussion-list list-unstyled" id="list_discussion">
              @foreach ($discussion_all as $discussion)
                <li class="media">
                  <div class="media-left">
                    <a class="avata" href="{{ $discussion->user->showLinkProfile() }}"
                       title="{{ $discussion->user->name }}">
                      @if($discussion->user->avatar_path != "")
                        <img src="{{ $discussion->user->showAvatar('ua_small') }}" alt="{{ $discussion->user->name }}"
                             class="media-object img-circle img-responsive">
                      @else
                        <img src="{{ $discussion->user->showDefaultAvatar() }}" alt="{{ $discussion->user->name }}"
                             class="media-object img-circle img-responsive">
                      @endif
                    </a>
                  </div>
                  <div class="media-body">
                    <h4 class="media-heading" title="Edus365 admin">
                      <a href="{{ $discussion->user->showLinkProfile() }}">{{ $discussion->user->name }}</a>
                      <span class="time">
                        @if ($discussion->created_at->diffInDays() > 30)
                          {{ $discussion->created_at->toFormattedDateString() }}
                        @else
                          {{ $discussion->created_at->diffForHumans() }}
                        @endif
                      </span>
                    </h4>
                    <h6>{{ $discussion->title }}</h6>

                    <p class="content">{{ $discussion->content }}</p>

                    <p class="activity">
                      <span class="reply" onclick="reply(this,{{ $discussion->id }},{{ $discussion->content_id }})"><i
                          class="fa fa-reply-all"></i> Trả lời</span>
                        <span class="like" onclick="voteUp(this,{{ $discussion->id }})"><i
                            class="fa fa-thumbs-o-up"></i> Thích</span>
                      <span class="count">(<span class="numlike">{{ $discussion->vote_up }}</span>)</span>

                      @if ($discussion->report == 1)
                        <span class="report report_active" onclick="report(this,{{ $discussion->id }})"
                              data-toggle="tooltip" data-placement="top" title="Nội dung đang được kiểm duyệt"><i
                            class="fa fa-flag"></i></span>
                      @else
                        <span class="report" onclick="report(this,{{ $discussion->id }})" data-toggle="tooltip"
                              data-placement="top" title="Báo cáo vi phạm"><i class="fa fa-flag"></i></span>
                      @endif

                    </p>


                    @foreach ($discussion->children as $child)
                      <div class="media">
                        <div class="media-left">
                          <a class="avata" href="{{ $child->user->showLinkProfile() }}"
                             title="{{ $child->user->name }}">
                            @if($child->user->avatar_path != "")
                              <img src="{{ $child->user->showAvatar('ua_small') }}" alt="{{ $child->user->name }}"
                                   class="media-object img-circle img-responsive">
                            @else
                              <img src="{{ $child->user->showDefaultAvatar() }}" alt="{{ $child->user->name }}"
                                   class="media-object img-circle img-responsive">
                            @endif
                          </a>
                        </div>
                        <div class="media-body">
                          <h4 class="media-heading" title="Edus365 admin">
                            <a href="{{ $child->user->showLinkProfile() }}">{{ $child->user->name }}</a>
                            <span class="time">
                              @if ($child->created_at->diffInDays() > 30)
                                {{ $child->created_at->toFormattedDateString() }}
                              @else
                                {{ $child->created_at->diffForHumans() }}
                              @endif
                            </span>
                          </h4>

                          <p class="content">{{ $child->content }}</p>

                          <p class="activity">
                            @if ($child->report == 1)
                              <span class="report report_active" onclick="report(this,{{ $child->id }})"
                                    data-toggle="tooltip" data-placement="top"
                                    title="Nội dung đang được kiểm duyệt"><i class="fa fa-flag"></i></span>
                            @else
                              <span class="report" onclick="report(this,{{ $child->id }})" data-toggle="tooltip"
                                    data-placement="top" title="Báo cáo vi phạm"><i class="fa fa-flag"></i></span>
                            @endif
                          </p>
                        </div>
                      </div>
                    @endforeach

                  </div>
                </li>
              @endforeach
            </ul>
            {!! $discussion_all->render() !!}
          </div>
          <div role="tabpanel" class="tab-pane" id="lecture_notes">
            <div class="pane">
              <div class="panel-body">
                 <span class="blink_me">Loading...</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  @include('frontend.student.course.includes.reply',['course_id' => $course->id, 'content_id' => $viewing_content ? $viewing_content->content_id : null])

@endsection
