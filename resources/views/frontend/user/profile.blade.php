@extends('frontend.layouts.default')

@inject('objReviews', 'App\Http\Controllers\Frontend\ReviewsController')

@section('content')

  <div class="container-fluid profile-public" id="profile">
    <div class="profile-top {{ (Auth::check()) ? 'profile-top-edit':'' }}">
      <div class="cover">
        @if ($profile->cover_path != "")
          <img class="cover-pic img-responsive" src="{{ $profile->showCover() }}" alt="">
        @else
          <img class="cover-pic img-responsive" src="../frontend/img/coverProfileTeach.png" alt="">
        @endif
      </div>
      <div class="container avata">
        <div class="media">
          <div class="media-left">
            <a href="javascript:void(0)" class="thumnai-avata">
              <img class="media-object avatar-pic img-responsive" src="{{ $profile->showAvatar('ua_medium') }}" alt="{{ $profile->name }}">
              @if (Auth::check() && Auth::user()->id == $profile->id)
                <p class="update_avata hidden-xs hidden-sm" data-toggle="modal" data-target="#profileUpdate"><i class="fa fa-camera"></i> Thay ảnh đại diện</p>
              @endif
            </a>
          </div>
          <div class="media-body">
            <h4 class="media-heading">
              <span class="is_name">{{ $profile->name }}</span>
            </h4>
            <h6>{{ ($profile->school_name != "") ? $profile->school_name: "Đang cập nhật" }}</h6>
            <hr>
            <div class="status">
              <p class="pull-left"><i class="fa fa-quote-left"></i></p>
              <div class="text_status">
                <span class="is_status_text {!! (mb_strlen(strip_tags($profile->status_text),'utf-8') > 490) ? 'ad_more':'' !!}">{!! (strip_tags($profile->status_text) != "") ? strip_tags($profile->status_text) : "Hãy chia sẻ đôi chút về bản thân của bạn " !!}</span>
                @if (mb_strlen(strip_tags($profile->status_text),'utf-8') > 490)
                  <span class="more_status" onclick="ViewMoreStatus(this)">Xem thêm</span>
                @endif
              </div>
            </div>
          </div>
        </div>
        @if (Auth::check() && Auth::user()->id == $profile->id)
          <div class="add_cover">
            <p class="btn btn-primary btn-sm waves-effect btn_add_cover" data-toggle="modal" data-target="#profileUpdate"><i class="fa fa-camera"></i> Thay ảnh bìa</p>
            <div class="btn-group">
              <a href="#" class="btn btn-primary btn-sm dropdown-toggle" data-toggle="dropdown"><span class="fa fa-gear"></span></a>
              <ul class="dropdown-menu pull-right">
                <li><a class="update-all" href="#" data-toggle="modal" data-target="#profileUpdate">Cập nhật thông tin</a></li>
              </ul>
            </div>
          </div>
        @endif
      </div>
    </div>
    <div class="profile_menu">
      <div class="container content_menu">
        <ul class="social-circle profile-nav list-unstyled">
          <li class="hidden-xs hidden-sm">
            <a href="javascript:void(0)" class="btn btn-primary btn-sm btn-send-mes"><i
                class="fa fa-envelope-o"></i>Gửi tin nhắn</a>
          </li>
          <li class="hidden-md hidden-lg">
            <a href="javascript:void(0)" class="unibee-send-mess img-circle"><i
                class="fa fa-envelope-o"></i></a>
          </li>
          <li>
            <a href="{{ ($profile->social_facebook != "") ? $profile->social_facebook : "javascript:void(0);" }}"
               class="facebook-sign img-circle"><i class="fa fa-facebook"></i></a>
          </li>
          <li>
            <a href="{{ ($profile->social_twitter != "") ? $profile->social_twitter : "javascript:void(0);" }}" class="twitter-sign img-circle"><i class="fa fa-twitter"></i></a>
          </li>
          <li>
            <a href="{{ ($profile->social_google != "") ? $profile->social_google : "javascript:void(0);" }}" class="google-plus-sign img-circle"><i class="fa fa-google-plus"></i></a>
          </li>
          <li>
            <a href="{{ ($profile->social_linkedin != "") ? $profile->social_linkedin : "javascript:void(0);" }}" class="linkedin img-circle"><i class="fa fa-linkedin"></i></a>
          </li>
        </ul>

        <ul class="report profile-nav">
          <li>
            <p>Khóa học</p>

            <p class="num">{{ $data_course->total() }}</p>
          </li>
          <li>
            <p>Bài viết</p>

            <p class="num">{{ $data_blog->total() }}</p>
          </li>

        </ul>
      </div>
    </div>

    <div class="container course-grid content-course">
      <div class="row">
        @foreach($data_course as $course)
          <div class="col-xs-6 col-sm-4 col-md-3 course-item">
            <div class="thumbnail">
              <a class="block-img" href="{{ route('frontend.course.public_view',['id'=> $course->id]) }}">
                <img class="img-responsive cou-pic" src="{{$course->get_cached_image('cc_medium')}}" alt="{{ $course->cou_title }}">
              </a>

              <div class="caption">
                <h3>
                  <a href="{{ $course->get_public_view_link() }}">
                    <span>{{ $course->cou_title }}</span>
                  </a>
                </h3>

                <p class="star-vote pull-left">
                  {!! genRating($course->rating) !!}({{$course->review_count}})
                </p>

                <p class="count-use text-overflow"><i class="fa fa-user"></i> {{ ($course->user_count > 0) ? $course->user_count:"Đang chờ " }} học viên</p>

                <p class="line-break"></p>

                <div class="use-info use-info-small">
                  <a class="u-avatar pull-left"
                     href="{{ route('profile.show',['id'=>$course->cou_user_id,'title'=>str_slug($profile->name,'-')]) }}"
                     title="{{ $profile->name }}">
                    <img src="{{ $profile->showAvatar("ua_small") }}" alt="{{ $profile->name }}"/>
                  </a>

                  <p class="u-name">
                    <a title="{{ $profile->name }}"
                       href="{{ route('profile.show',['id'=>$profile->id,'title'=>str_slug($profile->name,'-')]) }}">{{ $profile->name }}</a>
                    <span
                      class="nn">{{ ($profile->school_name != "") ? $profile->school_name: "Đang cập nhật" }}</span>
                  </p>
                </div>
              </div>
              @if ($course->getPrice() > 0)
                <p class="price pull-right">{{ $course->getPrice() }}</p>
              @else
                <p class="price pull-right">Miễn phí</p>
              @endif
              
            </div>
          </div>
        @endforeach
      </div>
      {!! $data_page->appends($appended)->render() !!}
    </div>
  </div>
  
@if (Auth::check() && Auth::user()->id == $profile->id)
  <div class="pop_up">
    @include('frontend.popup.profile',['profile' => $profile])
  </div>
@endif

@endsection

@section('after-scripts-end')
  {!! HTML::script('frontend/js/profile.js') !!}
@endsection