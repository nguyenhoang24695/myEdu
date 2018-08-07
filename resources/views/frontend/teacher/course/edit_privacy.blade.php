@extends('frontend.layouts.default', ['has_videojs' => true])

@section('after-styles-end')
  {!! HTML::style('frontend/css/lecture.css') !!}
@endsection

@section('after-scripts-end')
  {!! HTML::script('frontend/js/teacher/course/edit_privacy.js') !!}
@endsection

@section('content')
  <div class="container mr_top_3" xmlns="http://www.w3.org/1999/html">
    <div class="row">
      @include('frontend.includes.course_building_sidebar')
      <div class="col-sm-9 col-md-9 profile-private main main_lecture">
        <div class="wrap_main">
          <section>
            <div class="panel unibee-box">
              <div class="panel-heading">
                <h3 class="panel-title">Xuất bản khóa học</h3>
              </div>
              <div class="panel-body no-padding">
              </div>
            </div>
            @include('frontend.includes.course_tiny_info')

            <div class="panel">
              <div class="panel-body ">
                <p>Sau khi xây dựng xong khóa học, để khóa hoc đến được với hàng ngàn người đang cần kiến thức đó bạn
                  cần xuất bản khóa học. Sau khi xuất bản, khóa học của bạn sẽ được liệt kê và tìm thấy bởi người dùng
                  trên hệ thống</p>
                @if($course->editingStatus() == config('course.content_edit_status.editing'))
                  <p class="text-primary">Khóa học đang ở chế độ chỉnh sửa</p>
                  <button class="btn btn-primary" data-toggle="modal" data-target="#modal_public_course">Xuất bản khóa học</button>
                @elseif($course->editingStatus() == config('course.content_edit_status.public'))
                  <p class="text-primary">Khóa học đã được xuất bản</p>
                  <button class="btn btn-danger">Bỏ xuất bản khóa học</button>
                  <p>Khóa học của bạn {{$course->isActive() ? "đã được duyệt" : "chưa được duyệt"}}</p>
                  <p>Sau khi bạn bỏ chế độ xuất bản khóa học, để xuất bản lại và cho phép người dùng truy cập, khóa học của bạn cần được duyệt lại bởi ban quản trị</p>
                @endif
              </div>
            </div>
          </section>
          <section>
            <div class="panel unibee-box">
              <div class="panel-heading">
                <h3 class="panel-title">Giới hạn truy cập</h3>
              </div>
              <div class="panel-body no-padding">
              </div>
            </div>
            <div class="panel">
              <div class="panel-body ">
                <p>Khóa học của bạn chỉ giành cho tổ chức, hoặc một danh sách giới hạn những người được mời. Bạn có thể cài đặt ở đây.</p>
                <div class="btn-group" data-toggle="buttons">
                  <label class="btn btn-primary active">
                    <input type="radio" name="course_privacy" autocomplete="off" checked> Công khai
                  </label>
                  <label class="btn btn-primary">
                    <input type="radio" name="course_privacy" autocomplete="off"> Chỉ những người được mời
                  </label>
                  <label class="btn btn-primary">
                    <input type="radio" name="course_privacy" autocomplete="off"> Đặt mật khẩu
                  </label>
                </div>
              </div>
            </div>

          </section> <!-- /section -->

        </div>
      </div>
    </div>
  </div> <!-- /container -->
@endsection