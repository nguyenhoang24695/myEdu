<aside class="bg-while" >

    <div class="use-aside">
        <div class="aside-cover"></div>
        <div class="use-aside-info text-center">
            <a href="{{ Auth::user()->showLinkProfile() }}">
                <img src="{{ Auth::user()->showAvatar('ua_medium') }}" alt="{{ Auth::user()->name }}" class="mini-pic img-responsive img-circle">
            </a>
            <div class="base-info">
                <p class="u-name">
                    {{ Auth::user()->name }}
                </p>
                <p class="nn">
                    {{ Auth::user()->position. ' - ' .Auth::user()->unit_name  }}
                </p>
            </div>
        </div>
        <ul class="social-circle list-unstyled social-private text-center">
            <li>
                <a 
                href="javascript:void(0);" 
                class="facebook-sign img-circle">
                <i class="fa fa-facebook"></i></a>
            </li>
            <li>
                <a 
                href="javascript:void(0);" 
                class="twitter-sign img-circle">
                <i class="fa fa-twitter"></i></a>
            </li>
            <li>
                <a 
                href="javascript:void(0);" 
                class="google-plus-sign img-circle"><i class="fa fa-google-plus"></i></a>
            </li>
        </ul>
        <p class="text-center" style="font-size: 12px;color: #999">
            <i class="fa fa-clock-o"></i> Gia nhập: {{ Auth::user()->created_at->format('d/m/Y') }}
        </p>
        <p class="dropdown-mobile-dashboard hidden-sm hidden-md hidden-lg" data-toggle="collapse" data-target="#aside-dashboard" aria-expanded="true" aria-controls="aside-dashboard" >
            <i class="bar bar-one"></i>
            <i class="bar bar-two"></i>
            <i class="bar bar-three"></i>
        </p>
    </div>

    @if(\Access::hasRoles([config('access.role_list.teacher')]))
        <ul class="list-unstyled repo-use-private text-center">
            <li>
                <p class="num">
                    {{ Auth::user()->course()->where('cou_user_id','=', Auth::user()->id)->count() }}
                </p>
                <p>Khóa học</p>
            </li>
            <li>
                <p class="num">
                    {{ Auth::user()->course()->where('cou_active','=', 1)->where('cou_user_id','=', Auth::user()->id)->count() }}
                </p>
                <p>Khóa học public</p>
            </li>
        </ul>
    @endif

    <div class="list-tabs collapse navbar-collapse" id="aside-dashboard">
        
        <div class="list-group">
          <a href="{{url('/dashboard')}}" class="hide list-group-item {{ Request::is('dashboard') ? 'active' : '' }}"><i class="fa fa-home"></i> Tổng quan</a>

          @if(\Access::hasRoles([config('access.role_list.teacher')]))
                <a href="{{ url('/teacher/my_courses') }}" class="list-group-item {{ Request::is('teacher/my_courses') ? 'active' : '' }}">
                    <i class="fa fa-globe"></i> Khóa học đã tạo 
                    <span class="badge">
                        {{ Auth::user()->course()->where('cou_user_id','=', Auth::user()->id)->count() }}
                    </span>
                </a>
                <a href="{{ route('teacher.my_library') }}" class="list-group-item {{ \Active::controller('App\Http\Controllers\Frontend\Teacher\Library')}}"><i class="fa fa-file"></i> {{trans('common.course_resource')}} </a>
                <a href="#" data-toggle="modal" data-target="#myModal_dev" class="list-group-item"><i class="glyphicon glyphicon-list-alt" aria-hidden="true"></i> Xây dựng đề thi kiểm tra</a>
            @endif

            <a href="{{ route('frontend.student.my_courses') }}" class="list-group-item {{ \Active::route('frontend.student.my_courses') ? 'active' : '' }}">
                <i class="fa fa-globe"></i> Khóa học đang tham gia 
                <span class="badge">
                    {{ Auth::user()->courseStudents()->where('user_id','=', Auth::user()->id)->count() }}
                </span>
            </a>

            <a href="{{route('user.financial.review')}}" class="list-group-item">
              <i class="fa fa-dollar"></i> Báo cáo và tài chính
            </a>

            <?php
            $partner = \App\Models\Partner::where('user_id',Auth::user()->id)->where('active',1)->first();
            ?>

            @if ($partner)
                <a href="{{route('frontend.link.listing')}}" class="list-group-item">
                    <i class="fa fa-share-alt"></i> Quản lý link chia sẻ
                </a>
            @endif

            <a href="{{ url('/dashboard/notification') }}"
               class="list-group-item {{ (Request::is('dashboard/notification') || Request::is('dashboard/notification_setting')) ? 'active' : '' }}">
                <i class="fa fa-bell-o"></i> Thông báo của bạn 
            </a>

            <a href="{{ url('/dashboard/setting') }}" class="list-group-item {{ Request::is('dashboard/setting') ? 'active' : '' }}"><i class="fa fa-gear"></i> Cài đặt </a>

            @if(\Access::hasRoles([config('access.role_list.administrator')]))
                <a href="{{ route('backend.dashboard') }}" target="_blank" class="list-group-item"><i class="fa fa-dashboard"></i> Quản trị </a>
            @endif
        </div>
    </div>
</aside>
@include('frontend.popup.developing')

