@extends('frontend.layouts.default')

@section('before-styles-end')
  {!! HTML::style('frontend/plugin/slider/jquery.bxslider.css') !!}
@endsection

@section('before-scripts-end')
  {!! HTML::script('frontend/plugin/slider/jquery.bxslider.min.js') !!}
  <script>
      $(document).ready(function(){
          $('.slider4').bxSlider({
              slideWidth: 300,
              minSlides: 2,
              maxSlides: 3,
              moveSlides: 1,
              slideMargin: 10
          });
      });
  </script>
@endsection

@inject('objReviews', 'App\Http\Controllers\Frontend\ReviewsController')

@section('content')

<div class="container-fluid unibee-breadcrumb">
  <div class="container">
    @if ($breadcrumb)
      {!! Breadcrumbs::render('course_category', $category)  !!}
    @else
      <ol class="breadcrumb">
        <li>
          <a href="{{ url('/') }}">
            <i class="fa fa-home"></i>
          </a>
        </li>
        <li class="active">Danh mục</li>
      </ol>
    @endif
    <h3>
      {{ $category->cat_title }}
    </h3>
  </div>
</div>

<div class="container course-grid course-grid-small">
  <div class="row">
    <div class="col-md-2 sidebar-cat">
      <div class="row">
        <h4 class="no-margin-top">Lọc theo</h4>
        <div class="col-xs-12">
          <form class="form-loc-cat row">
            <div class="form-group col-xs-6 col-md-12 col-fill">
              <select class="trend c_option selectpicker dr-small">
                <option  value="{{ route('category.show',['id'=>$category->id,'title'=>str_slug($category->cat_title,'-')]) }}">Xu hướng</option>
                <option {{ (Request::get('trend') == 'cviews') ? "selected":"" }} value="{{ route('category.show',['id'=>$category->id,'title'=>str_slug($category->cat_title,'-')]).'?page='.Request::get('page',1).'&trend=cviews&price='.Request::get('price') }}">Xem nhiều</option>
              </select>
            </div>

            <div class="form-group col-xs-6 col-md-12 col-fill">
              <select class="price c_option selectpicker dr-small">
                <option value="{{ route('category.show',['id'=>$category->id,'title'=>str_slug($category->cat_title,'-')]) }}">Giá</option>
                <option {{ (Request::get('price') == 'cdesc') ? "selected":"" }} value="{{ route('category.show',['id'=>$category->id,'title'=>str_slug($category->cat_title,'-')]).'?page='.Request::get('page',1).'&trend='.Request::get('trend').'&price=cdesc' }}">Cao đến thấp</option>
                <option {{ (Request::get('price') == 'casc') ? "selected":"" }} value="{{ route('category.show',['id'=>$category->id,'title'=>str_slug($category->cat_title,'-')]).'?page='.Request::get('page',1).'&trend='.Request::get('trend').'&price=casc' }}">Thấp đến Cao</option>
                <option {{ (Request::get('price') == 'cfree') ? "selected":"" }} value="{{ route('category.show',['id'=>$category->id,'title'=>str_slug($category->cat_title,'-')]).'?page='.Request::get('page',1).'&trend='.Request::get('trend').'&price=cfree' }}">Miễn phí</option>
              </select>
            </div>
          </form>
        </div>

        <h4>Danh mục con</h4>
        <div class="menu-child">
          <ul class="list-menu-child list-unstyled">
            @foreach ($children as $child)
              <li class="hidden-xs hidden-sm hidden-md">
                <a href="{{ route('category.show',['id'=>$child->id,'title'=>str_slug($child->cat_title,'-')]) }}" title="{{ $child->cat_title }}" class="{{ ($category->id == $child->id) ? 'active':'' }}">{{ $child->cat_title }}</a>
              </li>
            @endforeach
          </ul>
          <select class="c_option selectpicker hidden-lg">
            @foreach ($children as $child)
              <option {{ ($category->id == $child->id) ? 'selected':'' }} value="{{ route('category.show',['id'=>$child->id,'title'=>str_slug($child->cat_title,'-')]) }}">{{ $child->cat_title }}</option>
            @endforeach
          </select>
        </div>  
      </div>
    </div>
    <div class="col-md-10">
      @if(count($data_course_hot) > 3)
      <div class="row">
        <p><h3>Các khóa học nổi bật</h3></p>
        <div class="slider4">
          @foreach ($data_course_hot as $course_hot)
          <div class="slide">
            <div class="thumbnail">
              <a class="block-img" href="{{ $course_hot->get_public_view_link() }}">
                <img class="img-responsive cou-pic" src="{{$course_hot->get_cached_image('cc_medium')}}" alt="{{ $course_hot->cou_title }}">
              </a>
              <div class="caption">
                <div><a href="{{ $course_hot->get_public_view_link() }}" title="{{ $course_hot->cou_title }}"><span>{{ $course_hot->cou_title }}</span></a></div>

                {!! genRating($course_hot->rating) !!}

                <p class="count-use text-overflow"><i class="fa fa-user"></i>Số lượt xem: {{ $course_hot->cou_views }}</p>
                <p class="line-break"></p>
                <div class="use-info use-info-small">
                  <a class="u-avatar pull-left" href="{{ route('profile.show',['id'=>$course_hot->user->id,'title'=>str_slug($course_hot->user->name,'-')]) }}" title="{{ $course_hot->user->name }}">
                    <img class="img-responsive img-circle" src="{{ $course_hot->user->showAvatar("ua_small") }}" alt="{{ $course_hot->user->name }}" />
                  </a>
                  <p class="u-name">
                    <a title="{{ $course_hot->user->name }}" href="{{ route('profile.show',['id'=>$course_hot->user->id,'title'=>str_slug($course_hot->user->name,'-')]) }}">{{ $course_hot->user->name }}</a>
                    <span class="nn" style="white-space: normal">{{ ($course_hot->user->position != "") ? $course_hot->user->position: "Đang cập nhật" }}</span>
                  </p>
                </div>
              </div>
              @if ($course_hot->cou_price > 0)
                <p class="price">{{ number_format($course_hot->cou_price) . " đ" }}</p>
              @else
                <p class="price">Miễn phí</p>
              @endif
            </div>
          </div>
          @endforeach
        </div>
      </div>
      @endif
      <div class="row">
        <?php $k = 1;?>
        @foreach ($data_course as $course)
          <div class="col-xs-6 col-sm-4 col-md-3 course-item {{ ($k%4 == 0) ? 'last':'' }}">
            <div class="thumbnail">
              <a class="block-img" href="{{ $course->get_public_view_link() }}">
                <img class="img-responsive cou-pic" src="{{$course->get_cached_image('cc_medium')}}" alt="{{ $course->cou_title }}">
              </a>
              <div class="caption">
                <h3><a href="{{ $course->get_public_view_link() }}" title="{{ $course->cou_title }}"><span>{{ $course->cou_title }}</span></a></h3>

                {!! genRating($course->rating) !!}

                <p class="count-use text-overflow"><i class="fa fa-user"></i>Số lượt xem: {{ $course->cou_views }}</p>
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
      {!! $data_course->appends($appended)->render() !!}
    </div>
  </div>

</div>
@endsection


@section('after-scripts-end')
  {!! HTML::script('frontend/js/category.js') !!}
@endsection