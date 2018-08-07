@extends('frontend.layouts.default')

@section('after-scripts-end')
  {!! HTML::script('frontend/js/student/registered_detail.js') !!}
  {!! HTML::script('frontend/js/student/studying.js') !!}
@endsection

@inject('objReviews', 'App\Http\Controllers\Frontend\ReviewsController')

@section('content')
<div class="container-fluid unibee-breadcrumb">
  <div class="container">
    <div class="row">
      <div class="col-xs-12">
        {!! Breadcrumbs::render('course_detail', $course)  !!}
        <div class="row">
          <div class="col-sm-7">
            <h1>
              {{ $course->cou_title }}
            </h1>
          </div>
          <div class="col-sm-5">
            <ul class="option list-unstyled">
              <li>
                <div class="use-info use-info-small">
                  <a title="{{$course->user->name}}" class="u-avatar pull-left" href="{{ route('profile.show',['id'=>$course->user->id,'title'=>str_slug($course->user->name,'-')]) }}">
                    <img class="img-circle img-responsive" src="{{$course->user->showAvatar()}}" alt="{{$course->user->name}}">
                  </a>
                  <p class="u-name">
                    <a href="{{ route('profile.show',['id'=>$course->user->id,'title'=>str_slug($course->user->name,'-')]) }}" title="{{$course->user->name}}">{{$course->user->name}}</a>
                    <span class="nn" title='{{ $course->user->school_name }}'>{{ ($course->user->school_name != "") ? str_limit($course->user->school_name,20) : "Đang cập nhật" }}</span>
                  </p>
                </div>
              </li>
              <li>
                <a href="#"><i class="fa fa-info-circle"></i></a>
              </li>
              <li><a href="#"><i class="fa fa-share-alt"></i></a></li>
              <li><a href="#"><i class="fa fa-gear"></i></a></li>
              <li><a href="#"><i class="fa fa-flag-o"></i></a></li>
              <li>
                <p class="btn btn-review-warning pull-left" data-toggle="modal" data-target="#myModal_rating">
                  <span class="fa fa-pencil"></span>
                  <span class="num">Đánh giá khóa học</span>
                </p>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="container wrap-course">
  <div class="row">
    <div class="col-md-6 aside" id="aside-course">
      <ul class="nav nav-tabs nav-tabs-unibee bg-while" role="tablist">
        <li role="presentation" class="active">
          <a href="#course_contents" role="tab" data-toggle="tab">
            <span class="icom-navicon-round"></span> 
            <span class="text">Tiết học</span>
          </a>
        </li>
        <li role="presentation">
          <a href="#announcement" role="tab" data-toggle="tab">
            <span class="icom-ios-chatboxes-outline"></span>
            <span class="text">Thông báo</span>
          </a>
        </li>
        <li role="presentation">
          <a href="#course_discussions" role="tab" data-toggle="tab">
            <span class="icom-chatbox-working"></span>
            <span class="text">Thảo luận</span>
          </a>
        </li>
        <li role="presentation">
          <a href="#course_members" id="tab_course_members" role="tab" data-toggle="tab">
          <span class="icom-ios-contact"></span>
          <span class="text">Học viên</span>
          </a>
        </li>
      </ul>

      <div class="tab-content bg-while">

        <div role="tabpanel" class="tab-pane active" id="course_contents">
          @if($last_study)
          <div class="media-course">
            @if($last_study)
              <h3>
                <a href="{{$course->get_default_studying_link($last_study->id)}}">
                  {{$last_study->get_title()}}
                </a>
              </h3>
              <p class="play">
                @if($last_study->getContent()->primary_data_type == config('course.lecture_types.video'))
                  <span><i class="glyphicon glyphicon-film"></i> </span>
                  {{--<i class="time">1:12</i>--}}
                @elseif($last_study->getContent()->secondary_data_type == config('course.lecture_types.document'))
                  <span><i class="glyphicon glyphicon-blackboard"></i> </span>
                  {{--<i class="time">10</i>--}}
                @endif
              </p>
            @else
              <h3>
                <a href="{{$course->get_default_studying_link()}}">
                  Bắt đầu học
                </a>
              </h3>
            @endif
              <p class="status"> Bạn đã hoàn thành <span id="course_progress_text">0%</span> khóa học </p>
              <p class="process-learn">
                <i id="course_progress" class="long" style="width:0%"></i>
              </p>
          </div>
          @endif
          <ul class="list-lecture-item-success list-unstyled">
            <?php $c_stt = 0;?>
            @foreach($course_contents as $content)
              @if($content->get_type() == config('course.content_types.section'))
                <li class="item-group">
                    <h4>{{$content->get_title()}}</h4>
                    <small>{{$content->get_sub_title()}}</small>
                </li>
              @elseif($content->get_type() == config('course.content_types.lecture'))
                <?php $c_stt++;?>
                <li >
                  <a href="{{$course->get_default_studying_link($content->id)}}">
                     <span>{{$content->get_title()}}</span>
                    @if($content->getContent()->primary_data_type == config('course.lecture_types.video'))
                      <span><i class="glyphicon glyphicon-film"></i> </span>
                    @elseif($content->getContent()->secondary_data_type == config('course.lecture_types.document'))
                      <span class="icom-file-text"></span>
                    @endif
                  </a>
                  <div  class="icon-lec-success" >
                    <span id="cc_status_{{$content->id}}" style="width:0%"></span>
                    @if ($c_stt < 10)
                      <i>0{{ $c_stt }}</i>
                    @else
                      <i>{{ $c_stt }}</i>
                    @endif
                  </div>
                </li>
              @elseif($content->get_type() == config('course.content_types.quizzes'))
                <?php $c_stt++;?>
                <li class="{{$content->access_privacy}}-content">
                  <a 
                  href="{{$course->get_default_studying_link($content->id)}}" 
                  title="{{$content->get_title()}}">
                    <span>{{$content->get_title()}}</span>
                    <i class="fa fa-check-square"></i>
                  </a>
                  <div  class="icon-lec-success" >
                    <span id="cc_status_{{$content->id}}" style="width:0%"></span>
                    @if ($c_stt < 10)
                      <i>0{{ $c_stt }}</i>
                    @else
                      <i>{{ $c_stt }}</i>
                    @endif
                  </div>
                </li>
              @endif
            @endforeach
          </ul>
        </div>

        <div role="tabpanel" class="tab-pane" id="announcement">
          <div class="pane">
            <div class="panel-body">
               <span class="blink_me">Đang cập nhật ...</span>
            </div>
          </div>
        </div>

        <div role="tabpanel" class="tab-pane tab-pane-course" id="course_discussions" >
          <ul class="media-list discussion-list list-unstyled">
            <li class="media">
              <div class="media-left">
                <a class="avata u-avatar" href="{{ Auth::user()->showLinkProfile() }}" title="{{ Auth::user()->name }}">
                  @if(Auth::user()->avatar_path != "")
                      <img src="{{ Auth::user()->showAvatar('ua_small') }}" alt="{{ Auth::user()->name }}" class="media-object img-circle img-responsive">
                  @else
                      <img src="{{ Auth::user()->showDefaultAvatar() }}" alt="{{ Auth::user()->name }}" class="media-object img-circle img-responsive">
                  @endif
                </a>
              </div>
              <div class="media-body">
                @include('frontend.student.course.includes.comment',['course_id' => $course->id, 'content_id' => 0])
              </div>
            </li>
          </ul>

          <ul class="media-list discussion-list list-unstyled " id="list_discussion">
            @foreach ($discussion_all as $discussion)
              @if ($discussion->user)
              <li class="media">
                <div class="media-left">
                  <a class="avata u-avatar" href="{{ $discussion->user->showLinkProfile() }}" title="{{ $discussion->user->name }}">
                    @if($discussion->user->avatar_path != "")
                        <img src="{{ $discussion->user->showAvatar('ua_small') }}" alt="{{ $discussion->user->name }}" class="media-object img-circle img-responsive">
                    @else
                        <img src="{{ $discussion->user->showDefaultAvatar() }}" alt="{{ $discussion->user->name }}" class="media-object img-circle img-responsive">
                    @endif
                  </a>
                </div>
                <div class="media-body">
                  <h4 class="media-heading">
                    <a href="{{ $discussion->user->showLinkProfile() }}">{{ $discussion->user->name }}</a>
                    @if ($discussion->content_id > 0)
                      <span class="time">đăng 1 thảo luận tại</span>
                      <a href="{{$course->get_default_studying_link($discussion->content_id)}}"
                      title="{{ $discussion->coursecontent ? str_limit($discussion->coursecontent->get_title(),20) : 'Bài học đã bị xóa' }}">
                        {{ $discussion->coursecontent ? str_limit($discussion->coursecontent->get_title(),20) : "Bài học đã bị xóa" }}
                      </a>
                    @endif
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
                    <span class="reply" onclick="reply(this,{{ $discussion->id }},{{ $discussion->content_id }})"><i class="fa fa-reply-all"></i> Trả lời</span>
                    <span class="like" onclick="voteUp(this,{{ $discussion->id }})"><i class="fa fa-thumbs-o-up"></i> Thích</span>
                    <span class="count">(<span class="numlike">{{ $discussion->vote_up }}</span>)</span>

                    @if ($discussion->report == 1)
                      <span class="report report_active" onclick="report(this,{{ $discussion->id }})" data-toggle="tooltip" data-placement="right" title="Nội dung đang được kiểm duyệt"><i class="fa fa-flag"></i></span>
                    @else
                      <span class="report" onclick="report(this,{{ $discussion->id }})" data-toggle="tooltip" data-placement="right" title="Báo cáo vi phạm"><i class="fa fa-flag"></i></span>
                    @endif

                  </p>


                  @foreach ($discussion->children as $child)
                    <div class="media">
                      <div class="media-left">
                        <a class="avata" href="{{ $child->user->showLinkProfile() }}" title="{{ $child->user->name }}">
                          @if($child->user->avatar_path != "")
                              <img src="{{ $child->user->showAvatar('ua_small') }}" alt="{{ $child->user->name }}" class="media-object img-circle img-responsive">
                          @else
                              <img src="{{ $child->user->showDefaultAvatar() }}" alt="{{ $child->user->name }}" class="media-object img-circle img-responsive">
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
                            <span class="report report_active" onclick="report(this,{{ $child->id }})" data-toggle="tooltip" data-placement="right" title="Nội dung đang được kiểm duyệt"><i class="fa fa-flag"></i></span>
                          @else
                            <span class="report" onclick="report(this,{{ $child->id }})" data-toggle="tooltip" data-placement="right" title="Báo cáo vi phạm"><i class="fa fa-flag"></i></span>
                          @endif
                        </p>
                      </div>
                    </div>
                  @endforeach

                </div>
              </li>
              @endif
            @endforeach
          </ul>
          {!! $discussion_all->render() !!}
        </div>

        <div role="tabpanel" class="tab-pane tab-pane-course" id="course_members">
          <span class="blink_me">Đang cập nhật ...</span>
        </div>

      </div>

      <div class="course-relation course-relation-regis">
        <div class="panel course-list">
          <div class="panel-body ">
              <h3 class="title">Khóa học liên quan</h3>
              @foreach ($course_list as $list)
                <div class="media">
                  <div class="media-left">
                    <a title="{{$list->cou_title}}" href="{{$list->get_public_view_link()}}" target="_blank">
                      <img class="media-object img-responsive" src="{{$list->get_cached_image('cc_medium')}}" alt="{{ $list->cou_title }}">
                    </a>
                  </div>
                  <div class="media-body">
                    <h4 class="media-heading">
                      <a class="c_title course_title" target="_blank" href="{{$list->get_public_view_link()}}" title="{{$list->cou_title}}">{{$list->cou_title}}</a>
                    </h4>

                    <div class="pull-left vote-list">
                      {!! genRating($list->rating) !!}
                      <span class="price_course pull-right">{{ $list->getPrice() }}</span>
                    </div>

                    <p class="num_count_use count-use">
                      <i class="fa fa-user"></i>
                      <span>{{ ($list->user_count > 0) ? $list->user_count : "Đang chờ" }} học viên</span>
                    <p class="tech_name" title="{{ $list->user->name }}">Giáo viên : <a href="{{ route('profile.show',['id'=>$course->user->id,'title'=>str_slug($list->user->name,'-')]) }}">{{ $list->user->name }}</a></p>
                    
                  </div>
                </div>
              @endforeach
            </div>
        </div>
      </div>

    </div>


    <div class="col-md-6 aside-review hidden-xs hidden-sm" id="list-reviews-ap">
      <div class="bg-while list-vote">
        <h3>Đánh giá</h3>
        <table>
          <tbody>
            <tr>
              <td class="total-star">
                <p class="point">{{ $course->rating }}</p>
                {!! genRating($course->rating,false) !!}
                <span class="num-vote">{{ $course->review_count }} đánh giá</span>
              </td>
              <td align="right">
                @foreach ([1,2,3,4,5] as $key)
                  <p class="review-order">
                    <span>{{ $key }} <i class="fa fa-star"></i></span>
                            <span class="longstar">
                              <i class="long"
                                 style="width:{{$course->review_count == 0 ? '0' : ($course->{'review_count_' . $key}/$course->review_count)*100 }}%"
                                 title="{{ $course->{'review_count_' . $key} }}%"></i>
                            </span>
                    <span>{{ $course->{'review_count_' . $key} }}</span>
                  </p>
                @endforeach

              </td>
            </tr>
          </tbody>
        </table>
        <div class="reviews-list">
          <div class="wrap-btn-reviews pull-left">
            <p class="btn btn-primary btn-review-warning pull-left" data-toggle="modal" data-target="#myModal_rating">
            <span class="fa fa-pencil"></span>
            <span class="num">Đánh giá khóa học</span>
            </p>
          </div>
          <h3>Danh sách đánh giá</h3>
          <ul class="media-list-reviews list-unstyled">
            @foreach ($list_reviews as $reviews)
              <li class="media">
                <div class="media-left">
                  <a href="{{ $reviews->user->showLinkProfile() }}" title="{{ $reviews->user->name }}">
                    <img class="media-object img-responsive" src="{{ $reviews->user->showAvatar("ua_small") }}" alt="{{ $reviews->user->name }}">
                  </a>
                </div>
                <div class="media-body">
                  <h4 class="media-heading" title="{{ $reviews->user->name }}">
                    <a href="{{ $reviews->user->showLinkProfile() }}">{{ $reviews->user->name }}</a>
                    <span class="time">
                      @if ($reviews->created_at->diffInDays() > 30)
                        {{ $reviews->created_at->toFormattedDateString() }}
                      @else
                        {{ $reviews->created_at->diffForHumans() }}
                      @endif
                    </span>
                    @if (Auth::check())
                      @if ($reviews->rev_user_id == auth()->user()->id)
                        <span class="label label-primary">Bài đánh giá của bạn</span>
                      @endif
                    @endif
                  </h4>
                  
                  <div class="pull-left review-star">
                    {!! genRating($reviews->rating,false) !!}
                  </div>
                  
                  <p class="content">{{ $reviews->rev_content }}</p>
                </div>
              </li>
            @endforeach
          </ul>
          <div>
            {!! $list_reviews->render() !!}
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="pop_up">
  @include('frontend.popup.reviews')
</div>

@include('frontend.student.course.includes.reply',['course_id' => $course->id, 'content_id' => 0])

@endsection