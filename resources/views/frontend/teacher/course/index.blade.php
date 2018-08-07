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
                          <h3 class="panel-title">Danh sách khóa học</h3>
                        </div>
                        <div class="panel-body no-padding">
                          <a href="{{route('teacher.add_new_course')}}" class="btn btn-primary pull-left">Tạo mới khóa học</a>

                          <form action="" accept-charset="utf-8" method="GET" class="frm-fillter">
                            <div class="form-group wrap_selectpicker pull-right">
                              <select class="selectpicker" onchange="window.location.href=this.value"  >
                                <option  value="{{ url('/teacher/my_courses') }}">Tùy chọn</option>
                                <option {{ (Request::get('free') == 'on') ? "selected":"" }} value="{{ url('/teacher/my_courses').'?free=on' }}">Miễn phí</option>
                                <option {{ (Request::get('free') == 'off') ? "selected":"" }} value="{{ url('/teacher/my_courses').'?free=off' }}">Có phí</option>
                                <option {{ (Request::get('editing') == 'on') ? "selected":"" }} value="{{ url('/teacher/my_courses').'?editing=on' }}">Đang chỉnh sửa</option>
                                <option {{ (Request::get('public') == 'on') ? "selected":"" }} value="{{ url('/teacher/my_courses').'?public=on' }}">Đã gửi xuất bản</option>
                              </select>
                            </div>
                          </form>
                          
                        </div>
                      </div>
                      
                      <div class="panel course-list course-list-private">
                        <div class="panel-body">
                          @foreach($my_courses as $course)
                            <div class="media">
                              <div class="media-left">
                                <a title="{{$course->cou_title}}" href="{{$course->get_public_view_link()}}" target="_blank">
                                  <img class="media-object" src="{{$course->get_cached_image('cc_small')}}" alt="">
                                </a>
                              </div>
                              <div class="media-body">
                                <h4 class="media-heading">
                                  <a class="c_title course_title" target="_blank" href="{{$course->get_public_view_link()}}" title="{{$course->cou_title}}">{{$course->cou_title}}</a>
                                  <a class="c_edit" title="Sửa" href="{{route('teacher.build_course', ['id' => $course->id])}}"><i class="fa fa-edit pull-right"></i></a>
                                </h4>
                                
                                <div class="pull-left vote-list">
                                  {!! genRating($course->rating) !!}
                                </div>

                                <p class="num_count_use count-use">
                                  <i class="fa fa-user"></i>
                                  <span>{{ ($course->user_count > 0) ? $course->user_count : "Đang chờ" }} học viên</span>
                                  <span class="price_course pull-right">{{ $course->getPrice() }}</span>
                                </p>

                                <p class="report_stt">
                                  @if ($course->public_status == 0)
                                    <span><i class="fa fa-lock"></i> Nháp</span>
                                  @else
                                    @if ($course->public_status == 1 && $course->cou_active == 1)
                                      <span><i class="fa fa-check-square-o"></i> Đã xuất bản</span>
                                    @else
                                      <span><i class="fa fa-hourglass-half"></i> Đang chờ xuất bản</span>
                                    @endif
                                  @endif
                                  <span><i class="fa fa-list-alt"></i> {{$course->content_lecture_count}} tiết học</span>
                                  <a class="view_demo" href="{{$course->get_default_studying_link()}}"><i class="fa fa-external-link-square"></i> Học thử</a>
                                </p>
                              </div>
                            </div>
                          @endforeach 
                        </div>
                      </div>
                      <div class="row">
                        {!! $my_courses->render() !!}
                      </div>
                    </section>
                </div>
            </div>
        </div>
    </div>
@endsection