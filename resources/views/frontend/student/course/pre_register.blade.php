@extends('frontend.layouts.default', ['has_videojs' => false])

@section('after-scripts-end')

@endsection

@section('scripts-plugin')

@endsection

@section('content')
  <div class="container-fluid unibee-breadcrumb ">
    <div class="container no-padding">
      {!! Breadcrumbs::render('pre_register_course')  !!}
      <h1>
        {{ trans('course.buy_course') }}
      </h1>
    </div>
  </div>
  <div class="container">
    <div class="">
      <h3 class="page-header">Thanh toán</h3>
      <div class="panel course-list course-list-private">
        <div class="panel-body">
          <div class="media">
            <div class="media-left">
              <a title="{{$course->cou_title}}" href="{{$course->get_public_view_link()}}" target="_blank">
                <img class="media-object" src="{{$course->get_cached_image('cc_small')}}" alt="{{$course->cou_title}}">
              </a>
            </div>
            <div class="media-body">
              <h4 class="media-heading">
                <a class="c_title course_title" target="_blank" href="{{$course->get_public_view_link()}}" title="{{$course->cou_title}}">
                  {{$course->cou_title}}
                </a>
              </h4>
              <p class="star-vote star-vote-medium">
                <i class="fa fa-star off"></i>
                <i class="fa fa-star off"></i>
                <i class="fa fa-star off"></i>
                <i class="fa fa-star off"></i>
                <i class="fa fa-star off"></i>
                <span class="num-vote">(0)</span>
              </p>
              <p class="num_count_use count-use">
                <i class="fa fa-user"></i>
                <span>{{$course->user_count > 0 ? $course->user_count . " học viên" : "Đang chờ học viên"}}</span>
                <span class="price_course pull-right">{{$course->getPrice()}}</span>
              </p>

              <p class="report_stt">
                <span><i class="icon-all icon-certificate"></i> Giáo viên: {{$course->user->name or $course->user->full_name}}</span>
                <span><i class="icon-all icon-certificate"></i> {{$course->content_lecture_count}} tiết học</span>
                {{--<a class="view_demo" href="http://unibee.dev/course/default_studying/cac-thu-thuat-hay-trong-powerpoint-ban-nen-biet"><i class="fa fa-arrow-circle-right"></i> Học thử</a>--}}
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      @if (isset($cookie_course) && !empty($cookie_course[$course->id]))
        {{-- code --}}
        <div class="col-sm-6">
        </div>
      @else
      <div class="col-sm-6">
        <div class="panel panel-primary">
          <div class="panel-heading">
            <h3 class="panel-title">Mã Giảm giá</h3>
          </div>
          <div class="panel-body">
            <p>{!! $promote_code_message !!}</p>
            {!! Form::open(['class' => 'form-inline', 'update_code_form']) !!}
            <div class="form-group">
              <input class="form-control" placeholder="Mã giảm giá" name="promote_code" maxlength="6" autocomplete="off"/>
            </div>
            <div class="form-group">
              <input class="btn btn-primary" type="submit" value="Áp dụng"/>
            </div>
            {!! Form::close() !!}
          </div>
        </div>
      </div>
      @endif
      <div class="col-sm-6 text-right">
        <div class="panel panel-primary">
          <div class="panel-heading">
            <h3 class="panel-title">Tính tổng thanh toán</h3>
          </div>
          <div class="panel-body">
            <p>Giá khóa học : <strong>{{human_money($course_price)}}</strong></p>
            <p>Giảm giá : <strong>{{$discount}}%</strong></p>
            <p>Giá khóa học sau giảm giá : <strong>{{human_money($course_price_discounted)}}</strong></p>
            <hr/>
            <p class="text-warning">Tài khoản của bạn : <strong>{{human_money($wallet_before,'0 đ')}}</strong></p>
            @if($wallet_after >= 0)
              <p class="text-warning">Tài khoản của bạn sau khi mua khóa học : <strong>{{human_money($wallet_after)}}</strong></p>
            {!! Form::open(['name' => 'register_course_form', 'route' => 'frontend.course.register']) !!}
              {!! Form::hidden('course_id', $course->id) !!}
              {!! Form::hidden('user_id', auth()->user()->id) !!}
              {!! Form::hidden('promote_code', $current_promote_code) !!}
              <button class="btn btn-primary btn-primary ">Thanh toán</button>
              {!! Form::close() !!}
            @else
            <a href="javascript:;" onclick="recharge_modal_form.modal('show');" class="btn btn-warning">Nạp tiền mua khóa học</a>
            @endif
          </div>
        </div>

      </div>
    </div>
  </div>

@endsection
