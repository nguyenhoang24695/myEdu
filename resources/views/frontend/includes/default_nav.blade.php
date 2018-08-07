<nav class="navbar navbar-default navbar-fixed-top navbar-unibee {{ Request::is('/') ? 'navbar-outline isHome' : '' }}">
  <div class="container">
    <div class="navbar-header">
      <button type="button" id="menu-toggle" class="dropdown-toggle menu-toggle no-border no-bg hidden-sm hidden-md hidden-lg">
        <span class='icon-all m-icon-menu'></span>
      </button>

      <button type="button" class="dropdown-toggle avata-login avata-login-mobile hidden-sm hidden-md hidden-lg" id="menu-use">
        <span class='icon-all m-icon-user'></span>
      </button>
      
      <button type="button" class="dropdown-toggle no-border no-bg search-mobile hidden-sm hidden-md hidden-lg" >
        <span class='icon-all m-icon-search'></span>
      </button>

      <a href="/" class="navbar-brand">
        <i class="icon-all logo-small"></i>
      </a>
    </div>

    <div class="collapse navbar-collapse navbar-collapse-unibee">
      <ul class="nav navbar-nav">
        <li class="dropdown border-l border-r dropdown-feat dropdown-cate {{ (Auth::guest()) ? '':'loged' }}">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false" title="Danh mục">
            <span class="icon-all caret-cat"></span>
            Danh mục
          </a>
          <?php
            $categories = App\Models\Category::where("cat_active", "=", 1)->get()->toHierarchy();
            $arr_color  = ['696538','d9562c','3b9daa','3c3e4b','706d80','d4bd49','67a485','2d6c8d','8db5bd','b88cbd'];
            $arr_icon   = ['cat-laptop','cat-paint','cat-skill','cat-shopping', 'cat-money','cat-group','cat-heart','cat-language','cat-camera','cat-board']
          ?>
          <ul class="dropdown-menu dropdown-menu-cat list-unstyled animate_menu_unibee main_menu" role="menu">
            
            @if (isset($categories) && !empty($categories))
              <?php $i = 0;?>
              @foreach ($categories as $category)
                <li style="background:#{{ isset($arr_color[$i]) ? $arr_color[$i] : "" }}">
                  <a href="{{ route('category.show',['id'=>$category->id,'title'=>str_slug($category->cat_title,'-')]) }}" title="{{ $category->cat_title }}">
                    <span>{{ $category->cat_title }}</span>
                    <i class="icon-cate icon-cat {{ isset($arr_icon[$i]) ? $arr_icon[$i] : ""}}"></i>
                  </a>
                </li>
                <?php $i++;?>
              @endforeach
            @endif
          </ul>
        </li>
      </ul>

      <div class="navbar-form navbar-left search-unibee hidden-xs hidden-sm hidden-md" 
           role="search" 
           data-action="{{ url('/search/kwd') }}"
           auto-action="{{ url('/search/api') }}">
        <div class="input-group input-group-search">
          <input type="text" class="form-control txt_search autocomplete_search" placeholder="Tìm khóa học ... " autocomplete="off">
          <div class="input-group-btn">
            <span class="btn btn-primary icon-btn-input btn-primary-search" >
              <i class="fa fa-search icon-search"></i>
            </span>
          </div>
        </div>
      </div>

      <div class="nav navbar-right">
        <ul class="nav navbar-nav border-r border-l dropdown-add {{ (Auth::guest()) ? '':'loged' }}">
        
          @if (Auth::guest())
            <li>
              <a title="Trở thành giảng viên myEdu" class="btn btn-primary btn-add-become"
                      href="http://tuyendunggiangvien.myedu.com.vn">Trở thành giảng viên</a></li>
          @else
            @if(\Access::hasRoles([config('access.role_list.teacher')]))
              <li><a title="Tạo khóa học" href="{{route('teacher.add_new_course')}}" class="btn btn-primary btn-add-become">Tạo khóa học</a></li>
            @else
              <li>
                <a 
                title="Nạp tiền mua khóa học" 
                class="btn btn-primary btn-add-become"
                href="javascript:void(0);"
                onclick="recharge_modal_form.modal('show');">Nạp tiền mua khóa học</a></li>
            @endif
          @endif

        </ul>
        <ul class="nav navbar-nav nav-login">
          @if (Auth::guest())
            <li>
              <a 
              title="Đăng ký" 
              href="{{ url('auth/register') }}" 
              class="btn btn-login" >Đăng ký</a></li>
            <li>
              <a 
              title="Đăng nhập" 
              href="{{ url('auth/login') }}" 
              class="btn btn-login" >Đăng nhập</a></li>
          @else
          <li class="dropdown dropdown-logged">
            <?php
            $count_notify = Auth::user()->notifications()->unread()->count();
            $toptennotify = Auth::user()->notifications()->orderby('id','DESC')->take(10)->get();
            ?>
            <span 
                class="icon-notify hidden-xs notification {{ ($count_notify > 0) ? 'iscount':'' }} " 
                title="Thông báo của bạn"
                data-toggle="dropdown"
                aria-expanded="false"
                >
                <i class="fa fa-bell-o"></i>
                @if ($count_notify > 0)
                  <span class="icount">
                    <span class="num-count">{{ $count_notify }}</span>
                  </span>
                @endif
            </span>

            <ul class="dropdown-menu dropdown-menu-logged dropdown-notification" role="menu">
              <li class="head">
                <h4>
                  <span class="title">Thông báo</span>
                  <a href="{{ url('/dashboard/notification_setting') }}" title="Cài đặt nhận thông báo từ Unibee">
                    <span class="icom-cog"></span>
                    Tùy chỉnh
                  </a>
                </h4>
              </li>
              @if ($toptennotify->count() == 0)
                <li class="noti-null">
                  <span>Chưa có thông báo nào</span>
                </li>
              @else
                <li class="notification-list">
                  <div class="scrollable">
                    @foreach ($toptennotify as $notify)
                      <div class="item-notify">
                        <a href="{!! $notify->url_detail() !!}" rel="nofollow" class="{{ ($notify->read == 0) ? 'is-read':'' }}">
                          <div class="img-use-noti">
                            <img src="{{ $notify->getObjImage() }}" alt="">
                          </div>
                          <div class="noti-content">
                            <p class="subject">{!! $notify->subject !!}</p>
                            <p class="time">{{ $notify->sent_at }}</p>
                          </div>
                        </a>
                        <div class="option">
                          @if ($notify->read == 0)
                            <span class="icom-check-circle read is_mark"
                                  data-toggle="tooltip" 
                                  data-placement="top"
                                  data-container="body"
                                  data-pk = "{{ $notify->id }}"
                                  data-type = "read"
                                  title="Đánh dấu đã đọc"></span>
                          @endif
                          <span class="icom-times-circle remove is_mark"
                                data-toggle="tooltip" 
                                data-placement="top"
                                data-container="body"
                                data-pk = "{{ $notify->id }}"
                                data-type = "remove"
                                title="Xóa thông báo"></span>
                        </div>
                      </div>
                    @endforeach
                  </div>
                </li>
              @endif
              <li class="foot">
                <a href="{{ url('/dashboard/notification') }}" title="Xem tất cả thông báo">Xem tất cả</a>
              </li>
            </ul>
          </li>

          <li class="dropdown dropdown-logged">
              <button type="button" class="dropdown-toggle avata-login" data-toggle="dropdown" role="button" aria-expanded="false" title="Thông tin cá nhân">
                  @if(Auth::user()->avatar_path != "")
                      <img class="img-responsive img-circle" src="{{ Auth::user()->showAvatar('ua_small') }}" alt="{{ Auth::user()->name }}" class="mini-pic">
                  @else
                      <img class="img-responsive img-circle" src="{{ Auth::user()->showDefaultAvatar() }}" alt="{{ Auth::user()->name }}" class="mini-pic">
                  @endif
              </button>
              <ul class="dropdown-menu dropdown-menu-logged" role="menu">
                @permission('view_backend')
                {{-- This can also be @role('Administrator') instead --}}
                <li>{!! link_to_route('backend.dashboard', 'Admin') !!}</li>
                @endpermission
                <li class="style-info">
                  <a href="{{ url('/dashboard') }}" class="show_name"><i class="fa fa-info-circle"></i> Thông tin tài khoản</a>
                </li>
                <li class="style-info">
                  <a 
                  href="{{ route('frontend.student.my_courses') }}">
                    <i class="fa fa-globe"></i>Khóa học đang học
                  </a>
                </li>
                @if(Access::hasRole(config('access.role_list.teacher')))
                <li class="style-info">
                  <a 
                  href="{{ route('teacher.my_courses') }}">
                    <i class="fa fa-globe"></i>Khóa học đã tạo
                  </a>
                </li>
                @endif
                <li class="style-info">
                  <a 
                  href="javascript:void(0);"
                  onclick="recharge_modal_form.modal('show');"><i class="fa fa-dollar"></i> Nạp tiền mua khóa học</a>
                </li>
                <li class="style-info">
                  <a href="{{route('user.financial.review')}}"><i class="fa fa-dollar"></i> Báo cáo tài chính</a>
                </li>
                @if(!\Access::hasRoles([config('access.role_list.teacher')]))
                  <li class="style-info">
                    <a title="Trở thành giáo viên" href="{{route('become.teacher')}}" ><i class="fa fa-registered"></i>Trở thành giáo viên</a>
                  </li>
                @endif
                @if (! \App\Models\Partner::where('user_id',\Auth::user()->id)->first())
                  <li class="style-info">
                    <a href="{{ route('partner.info') }}"><i class="fa fa-registered"></i>Đăng ký đối tác</a>
                  </li>
                @endif
                <li class="style-info">
                  <a href="{{ url('/dashboard/setting') }}"><i class="fa fa-gear"></i> Cài đặt tài khoản</a>
                </li>
                <li class="style-info">
                  <a href="{{ url('auth/logout') }}"><i class="fa fa-sign-out"></i> Thoát</a>
                </li>
              </ul>
          </li>
          @endif
        </ul>
      </div>
    </div>
  </div>
</nav>

<nav 
  class="navbar navbar-default navbar-fixed-top navbar-mobile-search"
  data-action="{{ url('/search/kwd') }}"
  auto-action="{{ url('/search/api') }}">
  <div class="input-group popup-mobile-search">
    <span class="input-group-addon addon-back-s"><i class="fa fa-arrow-left"></i></span>
    <input type="text" class="form-control auto_search_mobile" placeholder="Tìm khóa học ... " autocomplete="off">
    <div class="content_mobile_search"></div>
  </div>
</nav>