@extends('frontend.layouts.default')

@section('after-scripts-end')
  {!! HTML::script('frontend/plugin/jquery.devrama.slider.js') !!}
  {!! HTML::script('frontend/js/home.js') !!}
@endsection

@inject('objReviews', 'App\Http\Controllers\Frontend\ReviewsController')

@section('content')
  <div class="container-fluid bg-home">
    <div class="container rm-padding">
      <div class="inf-main">
        <h3 class="welcome animated fadeInDown">Nhận khóa học miễn phí tốt nhất</h3>
        <h3 class="welcome animated fadeInDown">trên internet</h3>
        <form class="form-horizontal animated fadeInUp" onsubmit="return false;">
          <div class="form-group">
            <div class="col-md-6 col-md-offset-3">
              <div class="input-group input-group-search-home">
                <input type="text" class="form-control search_home" placeholder="Tìm kiếm khóa học để bắt đầu..." autocomplete="off">
                <span class="input-group-btn">
                  <button class="btn btn-primary btn-find" type="button"><i class="fa fa-search"></i></button>
                </span>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>

  <div class="container miss-content">
    <div class="row">
      <div class="col-sm-12 col-md-6 slide-home">
          <div class="row">
            <div class="col-xs-12">
              <div class="slide slide-style">

                <a href="{{route('become.teacher')}}">
                  <img class="img-slide" data-lazy-src="{{ url('banner/01.jpg') }}" src="{{ url('banner/01.jpg') }}" />
                </a>

                <a href="{{ route('partner.info') }}">
                  <img class="img-slide" data-lazy-src="{{ url('banner/02.jpg') }}" src="{{ url('banner/02.jpg') }}" />
                </a>

              </div>
            </div>
          </div>
      </div>        
      <div class="col-sm-12 col-md-6 category-hot">
        <div class="row">
          @foreach($cat_hot as $hot)
            <div class="col-xs-6 col-cat-hot">
              <a class="item-cat-hot" href="{{ route('category.show',['id'=>$hot->id,'title'=>str_slug($hot->cat_title,'-')]) }}" title="{{ $hot->cat_title }}">
                <img src="{!! App\Core\MyStorage::get_image_link($hot->disk,$hot->avata_path,'cate_medium') !!}" class="img-responsive" >
                <span class="center-text-to-div cat-hot-name">{{ $hot->cat_title }}</span>
              </a>
            </div>
          @endforeach
        </div>
      </div>
    </div>
  </div>

  <div class="container course-grid">
    <div class="row">
      @foreach ($data_course as $course)
        <div class="col-xs-6 col-sm-4 col-md-3 course-item">
          <div class="thumbnail">
            <a class="block-img" href="{{ $course->get_public_view_link() }}">
              <img class="img-responsive cou-pic" src="{{$course->get_cached_image('cc_medium')}}" alt="{{ $course->cou_title }}">
            </a>
            <div class="caption">
              <h3><a href="{{ $course->get_public_view_link() }}" title="{{ $course->cou_title }}"><span>{{ $course->cou_title }}</span></a></h3>
              <p class="star-vote star-vote-medium pull-left">
                {{--{!! $objReviews->genRatingWithCourse($course->id) !!}--}}
                {!! genRating($course->rating) !!}({{$course->review_count}})
              </p>
              <p class="count-use text-overflow"><i class="fa fa-user"></i> {{ ($course->user_count > 0) ? $course->user_count:"Đang chờ " }} học viên</p>
              <p class="line-break"></p>
              <div class="use-info use-info-small">
                <a class="u-avatar pull-left" href="{{ route('profile.show',['id'=>$course->user->id,'title'=>str_slug($course->user->name,'-')]) }}" title="{{ $course->user->name }}">
                  <img class="img-responsive img-circle" src="{{ $course->user->showAvatar("ua_small") }}" alt="{{ $course->user->name }}" />
                </a>
                <p class="u-name">
                  <a title="{{ $course->user->name }}" href="{{ route('profile.show',['id'=>$course->user->id,'title'=>str_slug($course->user->name,'-')]) }}">{{ $course->user->name }}</a>
                  <span class="nn">{{ ($course->user->position != "") ? $course->user->position: "Đang cập nhật" }}</span>
                </p>
              </div>
            </div>
            @if ($course->cou_price > 0)
                <p class="price">{{ number_format($course->cou_price) . " đ" }}</p>
              @else
                <p class="price">Miễn phí</p>
              @endif
          </div>
        </div>
      @endforeach
      <div class="col-xs-6 col-sm-4 col-md-3 course-item">
        <div class="thumbnail more-option">
            <a class="block-img" href="#">
              <img class="img-responsive cou-pic" src="{!! App\Core\MyStorage::get_default_image() !!}" alt="Xem thêm">
            </a>
            <div class="caption">
              <h3><a href="#"><span>Xem thêm</span></a></h3>
              <p class="star-vote star-vote-medium pull-left">
                <i class="fa fa-star"></i>
                <i class="fa fa-star"></i>
                <i class="fa fa-star"></i>
                <i class="fa fa-star"></i>
                <i class="fa fa-star"></i>
                <span class="num-vote">(0)</span>
              </p>
              <p class="count-use text-overflow">
                <i class="fa fa-user"></i> Đang chờ  học viên</p>
              <p class="line-break"></p>
              <div class="use-info use-info-small">
                <a class="u-avatar pull-left" href="#" title="Teacher User">
                  <img class="img-responsive img-circle" src="{!! App\Core\MyStorage::get_default_image('ua_small','user.png') !!}" >
                </a>
                <p class="u-name">
                  <a title="unibee" href="">unibee</a>
                  <span class="nn">Đang cập nhật</span>
                </p>
              </div>
            </div>
            <div class="wrap-more">
              <div class="thumbnail-more">
                <a title="Xem thêm" href="{{ route('category.show',['id'=>0,'title'=>str_slug('tất cả danh mục','-')]) }}">
                  <div class="responsive">
                    <p>
                      <i class="icom-plus"></i>
                    </p>
                    <span>Xem thêm</span>
                  </div>
                </a>
              </div>
            </div>
        </div>
      </div>
    </div>
  </div>

  <div class="container-fluid">
    <div class="container text-center">
      <ul class="logo-vnpgroup">
        <li><a href="#" class="icon-all logo-vnp logo-daytot"></a></li>
        <li><a href="#" class="icon-all logo-vnp logo-vatgia"></a></li>
        <li><a href="#" class="icon-all logo-vnp logo-baokim"></a></li>
        <li><a href="#" class="icon-all logo-vnp logo-123doc"></a></li>
        <li><a href="#" class="icon-all logo-vnp logo-readzo"></a></li>
      </ul>
    </div>
  </div>

@endsection

@section('after-scripts-end')
  <script>
    //Being injected from FrontendController
    console.log(test);
  </script>
@stop