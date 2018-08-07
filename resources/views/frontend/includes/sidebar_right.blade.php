<div id="sidebar-right" class="sidebar pull-right">
  <ul class="list-unstyled sidebar-nav sidebar-login">
    @if (Auth::guest())
      <li class="not-login">
        <a
          title="Đăng ký"
          href="{{ url('auth/register') }}"
          class="btn btn-login" >Đăng ký</a></li>
      <li class="not-login">
        <a
          title="Đăng nhập"
          href="{{ url('auth/login') }}"
          class="btn btn-login" >Đăng nhập</a></li>
    @else
      <li class="m-profile text-center">
        <div class="m-cover">
          <img src="{{ url('frontend/img/common/right_menu_bg.png') }}" alt="" class="img-responsive">
        </div>
        <div class="m-info">
          <p>
            @if(Auth::user()->avatar_path != "")
                <img class="img-responsive img-circle" src="{{ Auth::user()->showAvatar('ua_small') }}" alt="{{ Auth::user()->name }}" class="mini-pic">
            @else
                <img class="img-responsive img-circle" src="{{ Auth::user()->showDefaultAvatar() }}" alt="{{ Auth::user()->name }}" class="mini-pic">
            @endif
          </p>
          <p class="name"><a href="{{ url('dashboard') }}">{{ Auth::user()->name }}</a></p>
          <p class="obj-repo">
            <strong>{{ Auth::user()->course()->where('cou_active','=', 1)->where('cou_user_id','=', Auth::user()->id)->count() }}</strong>
            Khóa học <span>|</span>
            <strong>{{ Auth::user()->blog()->where('public','>', 0)->where('blo_user_id','=', Auth::user()->id)->count() }}</strong>
            bài viết
          </p>
        </div>
      </li>
      <li class="divider"></li>
      @permission('view_backend')
      <li class="m-login">
        <a href="{{ url('admin/dashboard') }}"><i class="fa fa-dashboard"></i> Quản trị</a>
      </li>
      @endpermission

      <li class="m-login">
        <a href="{{ route('frontend.student.my_courses') }}">
          <i class="fa fa-globe"></i> Khóa học đang tham gia</a>
      </li>
      <li class="m-login">
        <a href="{{ url('auth/logout') }}">
          <span class="glyphicon glyphicon-off" aria-hidden="true"></span> Thoát
        </a>
      </li>
    @endif
  </ul>
</div>