@extends('frontend.layouts.default')

@inject('objReviews', 'App\Http\Controllers\Frontend\ReviewsController')

@section('content')
  <div class="container">
    <div class="row">
      <div class="col-md-3 aside unibee-aside">
        @include('frontend.user.includes.aside')
      </div>
      <div class="col-md-9 profile-private">
        <div class="wrap_main">
          <section>
            <div class="panel unibee-box">
              <div class="panel-heading">
                <h3 class="panel-title">Khóa học đang tham gia</h3>
              </div>
              <div class="panel-body no-padding">
                <form action="" accept-charset="utf-8" method="GET" class="frm-fillter">
                    <div class="form-group wrap_selectpicker pull-left">
                      <select class="selectpicker" data-width="auto" onchange="window.location.href=this.value"  >
                        <option  value="{{ url('course/studying') }}">Tùy chọn</option>
                        <option {{ (Request::get('success') == 'off') ? "selected":"" }} value="{{ url('course/studying').'?success=off' }}">Chưa hoàn thành</option>
                        <option {{ (Request::get('success') == 'on') ? "selected":"" }} value="{{ url('course/studying').'?success=on' }}">Hoàn thành</option>
                      </select>
                    </div>
                  </form>
              </div>
            </div>            
          </section>
          
          <div class="row course-grid">
          @if ($courses)
            @foreach ($courses as $cs)
              @if ($cs->course)
                <div class="col-sm-6 col-md-4">
                  <div class="thumbnail">
                    <a class="block_img" href="{{ route('frontend.course.public_view',['id'=> $cs->course->id]) }}">
                      <img class="c_pic img-responsive" src="{{$cs->course->get_cached_image('cc_medium')}}" alt="{{ $cs->course->cou_title }}">
                    </a>
                    <div class="caption">
                      <h3><a href="{{ route('frontend.course.public_view',['id'=> $cs->course->id]) }}" title="{{ $cs->course->cou_title }}"><span>{{ $cs->course->cou_title }}</span></a></h3>

                      {!! genRating($cs->course->rating) !!}

                      <p class="count-use text-overflow"><i class="fa fa-user"></i> {{$cs->course->user_count}} học viên</p>
                      <p class="line-break"></p>
                      <div class="use-info use-info-small">
                        <a class="u-avatar pull-left" href="{{ route('profile.show',['id'=>$cs->course->user->id,'title'=>str_slug($cs->course->user->name,'-')]) }}" title="{{ $cs->course->user->name }}">
                          <img src="{{ $cs->course->user->showAvatar("ua_small") }}" alt="{{ $cs->course->user->name }}" class="img-responsive img-circle" />
                        </a>
                        <p class="u-name">
                          <a title="{{ $cs->course->user->name }}" href="{{ route('profile.show',['id'=>$cs->course->user->id,'title'=>str_slug($cs->course->user->name,'-')]) }}">{{ $cs->course->user->name }}</a>
                          <span class="nn">{{ ($cs->course->user->position != "") ? $cs->course->user->position: "Đang cập nhật" }}</span>
                        </p>
                      </div>
                    </div>
                    <p class="price">{{ ($cs->course->cou_price > 0) ? $cs->course->cou_price . " đ":"Miễn phí" }}</p>
                  </div>
                </div>
              @endif
            @endforeach
          @endif
          </div>

          <div class="course-grid">
            <div class="panel unibee-box">
              <div class="panel-body">
                
                <div class="row">
                  <div class="col-sm-12 col-md-8 center fl_none al_center" >
                    {!! $courses->render() !!}
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection


@section('after-scripts-end')
  {!! HTML::script('frontend/js/category.js') !!}
@endsection