@extends('frontend.layouts.default')

@section('after-styles-end')
  {!! HTML::style('frontend/css/lecture.css') !!}
@endsection

@section('content')
  <div class="container mr_top_3">
    <div class="row">
      @include('frontend.includes.course_building_sidebar')
      <div class="col-sm-9 col-md-9 profile-private main main_lecture">
        <div class="wrap_main">

          <section>
            <div class="panel unibee-box">
              <div class="panel-heading">
                <h3 class="panel-title">Cài đặt giá</h3>
              </div>
              <div class="panel-body no-padding">
              </div>
            </div>
            @include('frontend.includes.course_tiny_info')

            <div class="panel">
              <div class="panel-body ">
                <div>
                  Giá hiện tại : {{$course->getPrice()}}
                </div>
                {!! Form::open(['class' => 'form', 'method' => 'post']) !!}
                <div class="form-group">
                  <label>
                    Giá khóa học (0 = miễn phí)
                  </label>
                  <input class="form-control" name="course_price" value="{{old('course_price', $course->cou_price)}}" />
                </div>
                @if($errors->has('course_price'))
                  {!! $errors->first('course_price', "<p class='text-error'>:message</p>") !!}
                  @endif
                <div class="form-group">
                  <button class="btn btn-primary">Lưu</button>
                </div>
                {!! Form::close() !!}
              </div>
            </div>
          </section>
          <div class="line-pattern"></div>
          {{--<section>--}}

            {{--<div class="panel unibee-box">--}}
              {{--<div class="panel-heading">--}}
                {{--<h3 class="panel-title">Cài đặt mã giảm giá</h3>--}}
              {{--</div>--}}
              {{--<div class="panel-body ">--}}

              {{--</div>--}}
            {{--</div>--}}

          {{--</section> <!-- /section -->--}}

        </div>
      </div>
    </div>
  </div> <!-- /container -->
@endsection