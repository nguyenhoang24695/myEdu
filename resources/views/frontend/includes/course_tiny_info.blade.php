<div class="panel reset-panel">
  <div class="panel-body">
    <div class="media media-course-info">
      <div class="media-left">
        <a href="{{route('teacher.build_course', ['id' => $course->id, 'action' => config('course.build_actions.editAvatar')])}}">
          <img class="media-object" src="{{$course->get_cached_image('cc_small')}}" alt="{{$course->cou_title}}">
        </a>
      </div>
      <div class="media-body">
        <h4 class="media-heading course_title">{{$course->cou_title}}</h4>
        @if ($course->public_status == 1)
          <span class="label label-primary">Khóa học của bạn đã được xuất bản</span>
        @else
          <span class="label label-info">Khóa học của bạn chưa được xuất bản</span>
        @endif
        <p class="course_btn_view">
          <a class="btn btn-sm btn-default " href="{{$course->get_default_studying_link()}}" target="_blank" class="cv_demo">{{trans('common.preview')}}</a>
          @if ($course->public_status == 0)
            <a class="btn btn-sm btn-primary" href="#" data-toggle="modal" data-target="#modal_public_course" class="cv_demo">Xuất bản khóa học</a>
          @endif
      </div>
    </div>
  </div>
</div>
@if ($course->id > 0)
  @include('frontend.popup.public_course',['course'=>$course])
@endif
