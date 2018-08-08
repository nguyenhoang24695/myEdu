@extends('frontend.layouts.default')

@inject('objReviews', 'App\Http\Controllers\Frontend\ReviewsController')

@section('content')

<div class="container-fluid unibee-breadcrumb">
  <div class="container">
    <ol class="breadcrumb">
      <li>

        <a href="{{ url('/') }}">
          <i class="fa fa-home"></i>
        </a>
      </li>
      <li>
        <a href="javascript:void(0)" rel="nofollow">
          Tìm kiếm
        </a>
      </li>

      <li class="active">{{ $query }}</li>
    </ol>
    <h3>

      <strong style="color:#333">{{ $total }}</strong> kết quả với từ khóa: <strong style="color:#333">“{{ $query }}”</strong>
    </h3>
  </div>
</div>

<div class="container course-grid course-grid-small">
  <div class="row">
    <div class="col-md-2 sidebar-cat">
      <div class="row hidden-xs hidden-sm hidden-md">
        <h4>Từ khóa liên quan</h4>
        <div class="menu-child ">
          <ul class="list-menu-child list-unstyled">
            @foreach ($tags as $tag)
              <li class="hidden-xs hidden-sm hidden-md">
                <a href="" title="{{ $tag->name }}" >{{ $tag->name }}</a>
              </li>
            @endforeach
          </ul>
          <select class="c_option selectpicker hidden-lg">
            <option value="">Chọn từ khóa liên quan</option>}
            option
            @foreach ($tags as $tag)
              <option value="">{{ $tag->name }}</option>
            @endforeach
          </select>
        </div>  
      </div>
    </div>
    <div class="col-md-10">
      <div class="row">
        <ul class="list-unstyled tab_search">
            <li class="{{ ($type_s == 'course' || $type_s == '') ? 'active':'' }}">
              <a href="{{ url('search/kwd').'?q='.str_replace(' ','+',$query).'&se=course' }}">Khóa học ({{ $total_course }})</a>
            </li>
            <li class="{{ ($type_s == 'teacher') ? 'active':'' }}">
              <a href="{{ url('search/kwd').'?q='.str_replace(' ','+',$query).'&se=teacher' }}">Thành viên ({{ $total_user }})</a>
            </li>
        </ul>
      </div>
      <div class="row">
      @if ($type_s == 'course' || $type_s == '' )
          <?php $k = 1;?>
          @foreach ($data_course as $course)
            <div class="col-xs-6 col-sm-4 col-md-3 course-item {{ ($k%4 == 0) ? 'last':'' }}">
              <div class="thumbnail">
                <a class="block-img" href="{{ $course->get_public_view_link() }}">
                  <img class="img-responsive cou-pic" src="{{$course->get_cached_image('cc_medium')}}" alt="{{ $course->cou_title }}">
                </a>
                <div class="caption">
                  <h3><a href="{{ $course->get_public_view_link() }}" title="{{ $course->cou_title }}"><span>{{ $course->cou_title }}</span></a></h3>
                  <p class="star-vote pull-left">
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
            <?php $k++;?>
          @endforeach
        @else
          <?php $k = 1;?>
          @foreach ($data_user as $user)
            <div class="col-xs-6 col-sm-4 col-md-4 {{ ($k%4 == 0) ? 'last':'' }}">
              <div class="use-info use-info-lag use-info-search">
                <a class="u-avatar pull-left" href="{{ $user->showLinkProfile() }}" title="{{ $user->name }}">
                  <img src="{{ $user->showAvatar() }}" alt="{{ $user->name }}" class="img-circle img-responsive">
                </a>
                <p class="u-name">
                  <a href="{{ $user->showLinkProfile() }}" title="{{ $user->name }}">{{$user->name}}</a>

                    @if ($user->unit_name != "")
                      <span class="nn">{{ $user->position. ' - ' .$user->unit_name  }}</span>
                    @else
                      <span class="nn">Đang cập nhật</span>
                    @endif

                </p>
              </div>
            </div>
          @endforeach
        @endif
      </div>
    </div>
  </div>
  
  <div class="row hide">
    <div class="col-sm-12 col-md-8 text-center cate-pagination">
      <a href="#" class="view-more">Xem thêm</a>
    </div>
  </div>

  <div class="row">
    <div class="col-md-4 col-md-offset-5 text-center cate-pagination" >
      
    </div>
  </div>

</div>
@endsection


@section('after-scripts-end')
  {!! HTML::script('frontend/js/category.js') !!}
@endsection