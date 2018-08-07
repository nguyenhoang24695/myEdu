<!-- Left side column. contains the logo and sidebar -->
<aside class="main-sidebar">

  <!-- sidebar: style can be found in sidebar.less -->
  <section class="sidebar">

    <!-- Sidebar user panel (optional) -->
    <div class="user-panel">
      <div class="pull-left image">
        @if (Auth::guest())
          <img src="{{ url('/images/users/user_small_default.png') }}" alt="" class="mini-pic">
        @else
          @if(Auth::user()->avatar_path != "")
            <img src="{{ Auth::user()->showAvatar('ua_small') }}" alt="{{ Auth::user()->name }}"
                 class="mini-pic img-circle">
          @else
            <img src="{{ Auth::user()->showDefaultAvatar() }}" alt="{{ Auth::user()->name }}"
                 class="mini-pic img-circle">
          @endif
        @endif
      </div>
      <div class="pull-left info">
        <p>{{ access()->user()->name }}</p>
        <!-- Status -->
        <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
      </div>
    </div>

    <!-- search form (Optional) -->
    {{--<form action="#" method="get" class="sidebar-form">--}}
      {{--<div class="input-group">--}}
        {{--<input type="text" name="q" class="form-control" placeholder="Search..."/>--}}
        {{--<span class="input-group-btn">--}}
          {{--<button type='submit' name='search' id='search-btn' class="btn btn-flat"><i class="fa fa-search"></i></button>--}}
        {{--</span>--}}
      {{--</div>--}}
    {{--</form>--}}
    <!-- /.search form -->

    <!-- Sidebar Menu -->
    <ul class="sidebar-menu">
      @if(Access::can('system_admin_user_manage') || Access::hasRole(config('access.role_list.administrator')))
      <li class="header">Quản lý tài khoản</li>
      <li class="{{ Active::pattern('admin/access/users*') }}">
        <a href="javascript:void(0)">
          <i class="fa fa-fw fa-user"></i>
          <span>Quản lý User</span>
          <i class="fa fa-angle-left pull-right"></i>
        </a>
        <ul class="treeview-menu">
          <li class="{{ Request::is("admin/access/users") ? 'active' : '' }}">
            <a href="{!!url('admin/access/users')!!}"><i class="fa fa-circle-o"></i> Danh sách</a>
          </li>
          <li class="{{ Request::is("admin/access/users/becometeacher") ? 'active' : '' }}">
            <a href="{{ url('admin/access/users/becometeacher') }}"><i class="fa fa-circle-o"></i> Đăng ký
              giảng viên</a>
          </li>
        </ul>
      </li>
      @endif
      @if(Access::can('system_admin_money_manage') || Access::hasRole(config('access.role_list.administrator')))
      <li class="header">Quản lý tài chính</li>
      <li class="{{\Active::pattern(['admin/revenue-report', 'admin/wallets', 'admin/orders', 'admin/transactions'])}}">
        <a href="">
          <i class="fa fa-dollar"></i> Quản lý tài chính
          <i class="fa fa-angle-left pull-right"></i>
        </a>
        <ul class="treeview-menu">
          <li>
            <a href="{{route('backend.money.revenue_report')}}">
              <i class="fa fa-dollar"></i>
              Tổng quan
            </a>
          </li>
          <li>
            <a href="{{route('backend.money.orders.list')}}">
              <i class="fa fa-file-text"></i>
              Đơn hàng
            </a>
          </li>
          <li>
            <a href="{{route('backend.money.orders.transactions_list')}}">
              <i class="fa fa-exchange"></i>
              Giao dịch tiền
            </a>
          </li>
          <li>
            <a href="{{route('backend.wallet.index')}}">
              <i class="fa fa-user"></i>
              Người dùng
            </a>
          </li>
        </ul>
      </li>
      @endif

      <li class="header">Quản lý tài nguyên</li>

      @if(Access::can('system_admin_course_manage') || Access::hasRole(config('access.role_list.administrator')))

      @if(Access::hasRole(config('access.role_list.administrator')) || Access::can(config('access.perm_list.can_manage_category')))
        <li class="{{ Active::controller('App\Http\Controllers\Backend\Category') }}">
          <a href="{!! route('backend.category_index') !!}">
            <i class="fa fa-fw fa-list"></i> <span>{{trans('admin.category_manage')}}</span>
          </a>
        </li>
      @endif

      <li class="{{ Active::pattern('admin/course/*') }}">
        <a href="javascript:void(0)">
          <i class="fa fa-share-alt-square"></i>
          <span>Quản lý khóa học</span>
          <i class="fa fa-angle-left pull-right"></i>
        </a>
        <ul class="treeview-menu">
          <li class="{{ Request::is("admin/course/list") ? 'active' : '' }}">
            <a href="{{ route('backend.course.module',['module'=>'list']) }}"><i class="fa fa-circle-o"></i> Tất cả</a>

          </li>
          <li class="{{ Request::is("admin/course/pending") ? 'active' : '' }}">
            <a href="{{ route('backend.course.module',['module'=>'pending']) }}"><i class="fa fa-circle-o"></i> Chờ duyệt</a>
          </li>
          <li class="{{ Request::is("admin/reviews/list") ? 'active' : '' }}">
            <a href="{{ route('reviews.list') }}"><i class="fa fa-circle-o"></i> Đánh giá</a>
          </li>
        </ul>
      </li>

      @endif
      @if(Access::can('system_admin_blog_manage') || Access::hasRole(config('access.role_list.administrator')))

        <li class="{{ Active::pattern('admin/blogcate/*') }}">
          <a href="javascript:void(0)">
            <i class="fa fa-share-alt-square"></i>
            <span>Quản lý danh mục Blog</span>
            <i class="fa fa-angle-left pull-right"></i>
          </a>
          <ul class="treeview-menu">
            <li class="{{ Request::is("admin/blogcate/create") ? 'active' : '' }}">
              <a href="{{ route('blogcate.create') }}"><i class="fa fa-circle-o"></i> Thêm mới</a>
            </li>
            <li class="{{ Request::is("admin/blogcate/list") ? 'active' : '' }}">
              <a href="{{ route('blogcate.list') }}"><i class="fa fa-circle-o"></i> Danh sách</a>
            </li>
          </ul>
        </li>

        <li class="{{ Active::pattern('admin/blog/*') }}">
          <a href="javascript:void(0)">
            <i class="fa fa-share-alt-square"></i>
            <span>Quản lý Blog</span>
            <i class="fa fa-angle-left pull-right"></i>
          </a>
          <ul class="treeview-menu">
            <li class="{{ Request::is("admin/blog/list") ? 'active' : '' }}">
              <a href="{{ route('blog.module',['module'=>'list']) }}"><i class="fa fa-circle-o"></i> Toàn bộ blog</a>
            </li>
            <li class="{{ Request::is("admin/blog/pending") ? 'active' : '' }}">
              <a href="{{ route('blog.module',['module'=>'pending']) }}"><i class="fa fa-circle-o"></i> Blog chờ
                duyệt</a>
            </li>
          </ul>
        </li>

      @endif

      @if(Access::can('system_admin_lib_manage') || Access::hasRole(config('access.role_list.administrator')))

        <li class="">
          <a href="{{action('Backend\CourseContentController@getVideoStatus')}}">
            <i class="fa fa-file-movie-o"></i> Videos
          </a>
        </li>
      @endif

      <li class="header">Marketing</li>
      @if(Access::can('system_admin_affiliate_manage') || Access::hasRole(config('access.role_list.administrator')))
        <li class="{{ Active::pattern(['admin/code/*','admin/partner/*']) }}">
          <a href="javascript:void(0)">
            <i class="fa fa-share-alt-square"></i>
            <span>Partner và Mã giảm giá</span>
            <i class="fa fa-angle-left pull-right"></i>
          </a>
          <ul class="treeview-menu">
            <li class="{{ Request::is("admin/code/list") ? 'active' : '' }}">
              <a href="{{ route('backend.code.module',['module'=>'list']) }}"><i class="fa fa-circle-o"></i> Mã giảm giá</a>
            </li>
            <li class="{{ Request::is("admin/partner/list") ? 'active' : '' }}">
              <a href="{{ route('backend.partner.module',['module'=>'list']) }}"><i class="fa fa-circle-o"></i> Partner</a>
            </li>
          </ul>
        </li>
      @endif
      <li class="">
        <a href=""><i class="fa fa-share-alt-square"></i> Liên kết chia sẻ</a>
      </li>
      <li class="{{Active::routePattern('backend.marketing_course.*')}}">
        <a href="javascript:void(0)">
          <i class="fa fa-share-alt-square"></i>
          <span>Marketing Courses</span>
          <i class="fa fa-angle-left pull-right"></i>
        </a>
        <ul class="treeview-menu">
          <li>
            <a href="{{route('backend.marketing_course.index')}}">
              <i class="fa fa-list"></i>Danh sách</a>
          </li>
          <li>
            <a href="{{route('backend.marketing_course.add')}}">
              <i class="fa fa-plus"></i>Thêm</a>
          </li>
        </ul>
      </li>

      <li>
        <a href="{{ route('backend.cod.listing') }}"><i class="fa fa-share-alt-square"></i> Quản lý mã COD </a>
      </li>

      @if(Access::can('system_admin_system_manage') || Access::hasRole(config('access.role_list.administrator')))
      <li class="header">Quản lý hệ thống</li>
      <li class="">
        <a href="{{action('Backend\TagController@getIndex')}}">
          <i class="fa fa-tags"></i> Tags
        </a>
      </li>
      <li class="">
        <a href="{{route('backend.searchindex.index')}}">
          <i class="fa fa-search"></i> Search index
        </a>
      </li>
        @endif

    </ul>
    <!-- /.sidebar-menu -->
  </section>
  <!-- /.sidebar -->
</aside>
