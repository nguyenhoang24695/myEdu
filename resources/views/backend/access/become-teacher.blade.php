@extends ('backend.layouts.master')

@section ('title', 'User Management')

@section('page-header')
    <h1>
        Danh sách đăng ký trở thành giảng viên.
    </h1>
@endsection

@section('after-scripts-end')
  {!! HTML::script('backend/js/active.js') !!}
@endsection

@section ('breadcrumbs')
    <li><a href="{!!route('backend.dashboard')!!}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
    <li class="active">{!! link_to_route('admin.access.users.index', 'User Management') !!}</li>
@stop

@section('content')
    @include('backend.access.includes.partials.header-buttons')
    <div class="box">
      <div class="box-header">
        <h4>Danh sách đăng ký giảng viên</h4>
      </div>
      <div class="box-body no-padding table-responsive">
        <table class="table table-striped table-bordered table-hover">
          <thead>
          <tr>
            <th>ID</th>
            <th>Name</th>
            <th>E-mail</th>
            <th>Ảnh đại diện</th>
            <th class="visible-lg">Thông tin đăng ký</th>
            <th>Actions</th>
          </tr>
          </thead>
          <tbody>
          @foreach ($users as $user)
            <tr>
              <td>{!! $user->id !!}</td>
              <td>{!! $user->name !!}</td>
              <td>{!! link_to("mailto:".$user->email, $user->email) !!}</td>
              <td>
                @if($user->avatar_path != "")
                  <img class="img-responsive img-circle" src="{{ $user->showAvatar('ua_small') }}"
                       alt="{{ $user->name }}" class="mini-pic">
                @else
                  <img class="img-responsive img-circle" src="{{ $user->showDefaultAvatar() }}" alt="{{ $user->name }}"
                       class="mini-pic">
                @endif
              </td>
              <td class="visible-lg">
                <p><strong>Tên hiển thị:</strong> {{ $user->name }}</p>

                <p><strong>Tên đơn vị:</strong> {{ $user->unit_name }}</p>

                <p><strong>Vị trí:</strong> {{ $user->position }}</p>

                <p><strong>Giới thiệu bản thân:</strong></p>

                <div>
                  {!! $user->status_text !!}
                </div>
                <p><strong>Thành tích đạt được:</strong></p>

                <div>
                  {!! $user->achievement !!}
                </div>
              </td>
              <td>
                @if ($user->hasRole('Teacher'))
                  <button
                    type="button"
                    class="btn btn-danger remove_role"
                    data-src="{{ route('admin.access.users.becometeacher.remove',['user_id' => $user->id]) }}">
                    Hủy bỏ đăng ký
                  </button>
                @else
                  <button
                    type="button"
                    class="btn btn-primary active_teacher"
                    data-src="{{ route('admin.access.users.becometeacher.active',['user_id' => $user->id]) }}">Duyệt
                  </button>

                  <button
                    type="button"
                    class="btn btn-danger deactive_teacher"
                    data-src="{{ route('admin.access.users.becometeacher.deactive',['user_id' => $user->id]) }}">Không
                    duyệt
                  </button>
                @endif

              </td>
            </tr>
          @endforeach
          </tbody>
        </table>



      </div>
      <div class="box-footer">
        <div class="pull-left">
          {!! $users->total() !!} user(s) total
        </div>

        <div class="pull-right">
          {!! $users->render() !!}
        </div>
        <div class="clearfix"></div>
      </div>
    </div>
@stop