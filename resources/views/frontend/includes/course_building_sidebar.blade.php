<div class="col-sm-3 col-md-3 aside unibee-aside">
  <aside class="bg-while" >

    <div class="use-info use-info-medium use-aside ">
      <a class="u-avatar pull-left" href="{{ Auth::user()->showLinkProfile() }}">
          <img src="{{ Auth::user()->showAvatar('ua_small') }}" alt="{{ Auth::user()->name }}" class="mini-pic img-responsive img-circle">
      </a>
      <p class="u-name">
          <a href="{{ Auth::user()->showLinkProfile() }}">{{ Auth::user()->name }}</a>
          @if (Auth::user()->school_name != "")
              <span class="nn">{{(Auth::user()->user_type != "") ? trans('auth.user_types.' . Auth::user()->user_type). " trường " : ""}} {{ Auth::user()->school_name }}</span>
          @else
              <span class="nn">Đang cập nhật</span>
          @endif
      </p>
      <a href="#" class="pull-right dropdown-mobile-dashboard hidden-sm hidden-md hidden-lg" data-toggle="collapse" data-target="#aside-dashboard" aria-expanded="true" aria-controls="aside-dashboard" >
          <i class="bar bar-one"></i>
          <i class="bar bar-two"></i>
          <i class="bar bar-three"></i>
      </a>
    </div>
    <div class="list-tabs collapse navbar-collapse" id="aside-dashboard">
      <div class="list-group">
        <h3><span>Nội dung khóa học</span> <hr></h3>
        <a class="list-group-item {{ Request::is('teacher/build_course/'.$course->id.'/doi_tuong') ? 'active' : '' }} " href="{{route('teacher.build_course', ['id' => $course->id, 'action' => config('course.build_actions.editObject')])}}">
            <i class="fa fa-user"></i> Yêu cầu & Mục tiêu
        </a>

        <a class="list-group-item {{ Request::is('teacher/build_course/'.$course->id.'/noi_dung_khoa_hoc') ? 'active' : '' }}" href="{{route('teacher.build_course', ['id' => $course->id, 'action' => config('course.build_actions.editContent')])}}">
            <i class="fa fa-columns"></i> Nội dung giảng dạy
        </a>

        <h3><span>Thông tin khóa học</span><hr></h3>

        <a class="list-group-item {{ Request::is('teacher/build_course/'.$course->id.'/thong_tin') ? 'active' : '' }}" href="{{route('teacher.build_course', ['id' => $course->id, 'action' => config('course.build_actions.editSummary')])}}">
            <i class="fa fa-list"></i> Thông tin tóm tắt
        </a>

        <a class="list-group-item {{ Request::is('teacher/build_course/'.$course->id.'/anh_dai_dien') ? 'active' : '' }}" href="{{route('teacher.build_course', ['id' => $course->id, 'action' => config('course.build_actions.editAvatar')])}}">
            <i class="fa fa-image"></i> Ảnh đại diện
        </a>

        <a class="list-group-item {{ Request::is('teacher/build_course/'.$course->id.'/video_gioi_thieu') ? 'active' : '' }}" href="{{route('teacher.build_course', ['id' => $course->id, 'action' => config('course.build_actions.editIntroVideo')])}}">
            <i class="fa fa-film"></i> Video giới thiệu
        </a>

        <h3><span>Thiết lập khóa học</span><hr></h3>

        <a class="list-group-item {{ Request::is('teacher/build_course/'.$course->id.'/che_do_rieng_tu') ? 'active' : '' }}" href="{{route('teacher.build_course', ['id' => $course->id, 'action' => config('course.build_actions.editPrivacy')])}}">
            <i class="fa fa-lock"></i> Xuất bản khóa học
        </a>

        <a class="list-group-item {{ Request::is('teacher/build_course/'.$course->id.'/thong_tin_gia_ban') ? 'active' : '' }}" href="{{route('teacher.build_course', ['id' => $course->id, 'action' => config('course.build_actions.editPrice')])}}">
            <i class="fa fa-money"></i> Giá
        </a>

      </div>
    </div>
  </aside>
</div>