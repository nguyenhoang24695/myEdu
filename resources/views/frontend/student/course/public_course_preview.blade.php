@extends('frontend.layouts.default', ['has_videojs' => true])

@section('before-scripts-end')
  {!! HTML::script('frontend/plugin/clipboard.min.js') !!}
  @if (config('app.id') == 'quochoc')
    <script type="text/javascript">
    / <![CDATA[ /
    var google_conversion_id = 882092071;
    var google_conversion_language = "en";
    var google_conversion_format = "3";
    var google_conversion_color = "ffffff";
    var google_conversion_label = "fAxlCOmlwGYQp9DOpAM";
    var google_remarketing_only = false;
    / ]]> /
    </script>
    <script type="text/javascript" src="//www.googleadservices.com/pagead/conversion.js">
    </script>
    <noscript>
    <div style="display:inline;">
    <img height="1" width="1" style="border-style:none;" alt="" src="//www.googleadservices.com/pagead/conversion/882092071/?label=fAxlCOmlwGYQp9DOpAM&amp;guid=ON&amp;script=0"/>
    </div>
    </noscript>
  @endif
@endsection

@section('after-scripts-end')
  {!! HTML::script('frontend/plugin/readmore.min.js') !!}
  <script>
    $(document).ready(function(){
      $('.readmore_collapse').readmore({
        moreLink: '<a href="#">Đọc thêm</a>',
        lessLink: '<a href="#">Thu gọn</a>',
        collapsedHeight: 350,
        afterToggle: function(trigger, element, expanded) {
          if(expanded) {
            $(element).animate({height: '100%'});
          }
        }
      });
      $('.readmore_collapse_150').readmore({
        moreLink: '<a href="#">Đọc thêm</a>',
        lessLink: '<a href="#">Thu gọn</a>',
        collapsedHeight: 100,
        afterToggle: function(trigger, element, expanded) {
          if(expanded) {
            $(element).animate({height: '100%'});
          }
        }
      });
      var free_content = $('li.public-content:first');
      if(free_content.length == 0){
        $('#btn_go_to_free_lecture').remove();
      }else{
        $('#btn_go_to_free_lecture').click(function(){
          var _link = free_content.find('a:first').attr('href');
          window.location = _link;
        });
      }

    });
  </script>
@endsection

@section('content')
<div class="container-fluid unibee-breadcrumb ">
  <div class="container no-padding">
    {!! Breadcrumbs::render('course_detail', $course)  !!}
    <h1>
      {{ $course->cou_title }}
    </h1>
  </div>
</div>

<div class="container-fluid top-detail ">
  <div class="container no-padding">
    <ul class="top-report list-unstyled">
      <li>
        {!! genRating($course->rating) !!}
        <span 
          class="num-vote" 
          >{{ $list_reviews->total() }} Đánh giá</span>
      </li>
      <li>
        <p class="count-use">
          <i class="fa fa-user"></i>
          <span>Số lượt xem: {{$course->cou_views}}</span>
        </p>
      </li>
      <li>
        <p class="calendar"><i class="fa fa-calendar"></i> {{$course->created_at->format('d-m-Y')}}</p>
      </li>
    </ul>
  </div>
</div>

<div class="container-fluid detail-main">
  <div class="container basic-info no-padding">
    <div class="row">
      <div class="col-sm-7 col-md-7 basic-info-left">
          {{-- Hiển thị video demo hoặc ảnh đại diện --}}
        @if(0 && $course_video_demo)
            <div class="embed-responsive embed-responsive-16by9">
              <div class="embed-responsive-item">
                @include('includes.partials.response_video_player', ['player_id' => 'video_' . $course->id,
                        'stream_link' => $course_video_demo,
                        'autoplay' => false,
                        'cover_img' => $course->get_cached_image('cc_video_cover')])
              </div>
            </div>
        @else
        <a href="javascript:void(0)" class="obj-demo">
          <img src="{!! $course_preview_image !!}" alt="" class="img-responsive"/>
        </a>
        @endif
      </div>

      <div class="col-sm-5 col-md-5 basic-info-right">

        @if ($new_price > 0 && $new_price != $course->cou_price)
          <h3>{{ number_format($new_price) }} VND <small>({{ $course->getPrice() }})</small></h3>
        @else
          <h3>{{ $course->getPrice() }}</h3>
        @endif
        
        <ul class="join-class list-unstyled">
          <li>
            @if(auth()->guest())
            {{-- chưa đăng nhập --}}

            <a rel="nofollow" href="{{ url('auth/login') }}" class="btn btn-primary btn-join ">Tham dự khóa học</a>

            @elseif(in_array($my_role, ['admin', 'teacher']))
            {{-- giáo viên hoặc admin --}}
            <a {{config('seo.no_follow.default_study_lecture_link') ? 'rel="nofollow"' : ''}} href="{{$course->get_default_studying_link()}}" class="btn btn-primary btn-join ">Tham dự khóa học</a>
            @elseif(in_array($my_role, ['course_admod']))
            {{-- giáo viên hoặc admin --}}
            <a {{config('seo.no_follow.default_study_lecture_link') ? 'rel="nofollow"' : ''}} href="{{$course->get_default_studying_link()}}" class="btn btn-primary btn-join ">Kiểm duyệt nội dung</a>
            @else
              {{--Người có khả năng đăng ký học(người dùng chưa đăng ký)--}}
                @if($course->cou_price > 0)
                <form action="{{route('frontend.course.pre_register_course', ['course_id' => $course->id])}}"
                      method="POST" accept-charset="utf-8" class="form-inline">
                  <button class="btn btn-primary btn-join ">Tham dự lớp học</button>
                  {{--Nếu khóa học có phí mới hiển thị ô nhập mã giảm giá và sang link thanh toán--}}
                  
                  @if ($new_price == 0)
                    <div class="form-group">
                      <input 
                      class="form-control" 
                      placeholder="Nhập mã COD"
                      name="promote_code" 
                      maxlength="6"
                      autocomplete="off">
                    </div>
                  @endif
                  
                  <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
                  {!! csrf_field() !!}
                </form>
                @else
                {!! Form::open(['name' => 'register_course_form', 'route' => 'frontend.course.register']) !!}
                {!! Form::hidden('course_id', $course->id) !!}
                {!! Form::hidden('user_id', auth()->user()->id) !!}
                <button class="btn btn-primary btn-primary">Tham dự lớp học</button>
                {!! Form::close() !!}
                @endif
            @endif
            @if($course->cou_price > 0)
            <a id="btn_go_to_free_lecture" href="javascript:void(0);"><i>(Học thử miễn phí)</i></a>
            @endif
          </li>
          <li class="social-btn-online">
            <div style="display: inline-block;">
              <div class="fb-send" data-href="{{ $course->get_public_view_link() }}"></div>
            </div>
            <div style="display: inline-block;">
              <div class="fb-like" data-href="{{ $course->get_public_view_link() }}" data-layout="button_count" data-action="like" data-show-faces="true" data-share="true"></div>
            </div>
            <div style="display: inline-block;vertical-align: top;">
              <div class="g-plusone" data-size="medium" data-href="{{ $course->get_public_view_link() }}"></div>
            </div>
          </li>

          <li style="margin-top: 10px">
            <a 
            href="{{ route('payment.guide.module',['module' => 'payment-guide']) }}" 
            target="_blank">Hướng dẫn tham gia khóa học</a>
          </li>

          @if ($partner != "" && $code_info != "" && $course->cou_price > 0)
            <li style="margin-top: 10px">
              <a 
              href="javascript:void(0)" 
              data-toggle="modal" 
              data-target="#myModal_sharelink"
              class="btn btn-info btn-sm">
              <i class="fa fa-share-alt" aria-hidden="true"></i>Chia sẻ là có tiền</a>
            </li>
          @endif

        </ul>
        <hr>
        <table>
          <tbody>
          <tr>
            <td class="set">
              <p><i class="fa fa-circle"></i> Bài học</p>
            </td>
            <td>
              <p><strong>{{$course->content_lecture_count}}</strong></p>
            </td>
          </tr>
          <tr>
            <td class="set">
              <p><i class="fa fa-circle"></i> Bài tập kiểm tra</p>
            </td>
            <td>
              <p><strong>0</strong></p>
            </td>
          </tr>
          @if(config('feature_toggle.show_course_skill_level'))
          <tr>
            <td class="set">
              <p><i class="fa fa-circle"></i> Dành cho học sinh</p>
            </td>
            <td>
              <p><strong>{{$cou_skill_level_name}}</strong></p>
            </td>
          </tr>
          @endif
          <tr>
            <td class="set">
              <p><i class="fa fa-circle"></i> Kiến thức mục tiêu</p>
            </td>
            <td>
              {!! '<p><strong>' . implode("</strong></p><p><strong>", explode('|', $course->cou_goals)) . '</strong></p>' !!}
            </td>
          </tr>
          </tbody>
        </table>
        <hr style="margin: 10px 0px;">
        <div class="row course-info-in">
          <div class="col-md-12">
            <h4>Giảng viên:</h4>
            <div class="use-info use-info-medium use-info-primary">
              <a class="u-avatar pull-left" href="{{ $teacher->showLinkProfile() }}">
                <img class="img-circle img-responsive" src="{{$teacher->showAvatar()}}" alt="">
              </a>
              <p class="u-name">
                <a href="{{ $teacher->showLinkProfile() }}" title="{{$teacher->name}}">{{$teacher->name}}</a>
                @if ($teacher->unit_name != "")
                  <span>{{ $teacher->position. ' - ' .$teacher->unit_name  }}</span>
                @else
                  <span>Đang cập nhật</span>
                @endif
                
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

  <div class="container-fluid">
    <div class="container lecture-list course-detail-all no-padding">
      <div class="row">
        <div class="col-md-7">
          <div class="course-detail-info">
            <section>
              <h3 class="title">Giới thiệu chung</h3>
              <div class="summary readmore_collapse">{!! $course->cou_summary !!}</div>
            </section>

            @if(config('feature_toggle.show_course_audience'))
              <section>
                <h4 class="title">Khóa học này dành ai</h4>
                {!! $course->cou_audience != "" ? '<ul class="no-margin"><li>' . implode("</li><li>", explode('|', $course->cou_audience)) . '</li></ul>' : "" !!}
              </section>
            @endif

            <section>
              <h4 class="title">Yêu cầu trước khi tham gia khóa học</h4>
              {!! $course->cou_requirements != "" ? '<ul class="no-margin"><li>' . implode("</li><li>", explode('|', $course->cou_requirements)) . '</li></ul>' : "" !!}
            </section>

            <section>
              <h4 class="title">Kiến thức mục tiêu</h4>
              {!! '<ul class="s_last"><li>' . implode("</li><li>", explode('|', $course->cou_goals)) . '</li></ul>' !!}
            </section>
            <div class="line-break-bold"></div>

            <section>
              <h3 class="title" id="course_content_list">Chương trình học</h3>
              <ul class="list-lecture-item list-unstyled {{$course->cou_price == 0 ? 'public-content' : ''}}">
               @foreach($course_contents as $content)
                    @if($content->get_type() == config('course.content_types.section'))
                      <li class="item-group">
                        <h4>{{$content->get_title()}}</h4>
                        @if ($content->get_sub_title() != '')
                          <small>{{$content->get_sub_title()}}</small>
                        @endif
                        
                      </li>
                    @elseif($content->get_type() == config('course.content_types.lecture'))
                      <li class="{{$content->access_privacy}}-content">
                        <a {{config('seo.no_follow.public_study_lecture_link') ? 'rel="nofollow"' : ''}}
                           class=""
                           href="{{$content->access_privacy == 'public' || $course->cou_price == 0 ? $course->get_public_studying_link($content->id) : 'javascript:void(0)'}}"
                           title="{{$content->access_privacy == 'public' || $course->cou_price == 0 ? 'Học miễn phí bài : ' : ''}} {{$content->get_title()}}">
                          @if($content->getContent()->primary_data_type == config('course.lecture_types.video'))
                            <i class="glyphicon glyphicon-film "></i> {{$content->get_title()}}
                            {{--<span class="time">1:12</span>--}}
                          @elseif($content->getContent()->primary_data_type == config('course.lecture_types.audio'))
                            <i class="glyphicon glyphicon-music "></i> {{$content->get_title()}}
                            {{--<span class="time">1:12</span>--}}
                          @elseif($content->getContent()->primary_data_type == config('course.lecture_types.document'))
                            <span class="icom-file-text"></span> {{$content->get_title()}}
                          @else
                          <i class="glyphicon glyphicon-play-circle "></i> {{$content->get_title()}}
                            {{--<span class="time">1:12</span>--}}
                            @endif
                            <span class="pull-right text-info lock-label"><i class="fa fa-lock"></i></span>
                            <span class="pull-right text-info public-label "><i class="fa fa-unlock-alt"></i> </span>
                        </a>
                      </li>
                    @elseif($content->get_type() == config('course.content_types.quizzes'))
                      <li class="{{$content->access_privacy}}-content">
                        <a 
                        href="{{$content->access_privacy == 'public' || $course->cou_price == 0 ? $course->get_public_studying_link($content->id) : 'javascript:void(0)'}}" 
                        title="{{$content->get_title()}}">
                          <i class="fa fa-check-square"></i> {{$content->get_title()}}
                          <span class="pull-right text-info lock-label"><i class="fa fa-lock"></i></span>
                          <span class="pull-right text-info public-label "><i class="fa fa-unlock-alt"></i> </span>
                        </a>
                      </li>
                    @endif
                @endforeach
              </ul>
            </section>

            <div class="line-break-bold"></div>
            @include('frontend.user.includes.about_me')

            <section>
              <div class="summary sub_summary readmore_collapse_150" data-readmore="" aria-expanded="false">
                {!! $course->sub_summary !!}
              </div>
            </section>

            <div class="line-break-bold"></div>

            <section class='aside-review'>
              <h3 class="title">Đánh giá</h3>
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
                <h3>Danh sách đánh giá</h3>
                <ul class="media-list-reviews list-unstyled">
                  @foreach ($list_reviews as $reviews)
                  <?php //$emp_r = 5-$reviews->rating;?>
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

            </section>

          </div>
        </div>

        <div class="col-md-5 course-relation">
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
                      <span>Số lượt xem: {{ $list->cou_views }}</span>
                    <p class="tech_name" title="{{ $list->user->name }}">Giáo viên : <a href="{{ route('profile.show',['id'=>$list->user->id,'title'=>str_slug($list->user->name,'-')]) }}">{{ $list->user->name }}</a></p>
                    
                  </div>
                </div>
              @endforeach
            </div>
          </div>

          <div class="panel panel-default tag-list">
            <div class="panel-body">
              <h3 class="title">Tag : </h3>
               <div>
                 @foreach ($course->tagged as $cou_tag)
                  <a href="#" class="tag-item" title="{{ $cou_tag->tag_name }}">{{ $cou_tag->tag_name }}</a>
                 @endforeach
                </div>
            </div>
          </div>

        </div>
      </div>
    </div>
  </div>

@if ($partner != "" && $code_info != "")
  <div class="pop_up">
    @include('frontend.popup.sharelink',['course' => $course, 'code_info' => $code_info])
  </div>
@endif

  @endsection
