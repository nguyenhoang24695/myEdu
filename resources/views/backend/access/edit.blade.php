@extends ('backend.layouts.master')

@section ('title', 'User Management | Edit User')

@section ('before-styles-end')
    {!! HTML::style('backend/plugin/jqueryonoff/jqueryonoff.css') !!}
@stop

@section('page-header')
    <h1>
        Quản lý người dùng
        <small>Chỉnh sửa thông tin, quyền</small>
    </h1>
@endsection

@section ('breadcrumbs')
    <li><a href="{!!route('backend.dashboard')!!}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
    <li>{!! link_to_route('admin.access.users.index', 'User Management') !!}</li>
    <li class="active">{!! link_to_route('admin.access.users.edit', "Edit ".$user->name, $user->id) !!}</li>
@stop

@section('content')
    @include('backend.access.includes.partials.header-buttons')
    <div class="box">
      <div class="box-header">
        <h4>Chỉnh sửa thông tin người dùng</h4>
      </div>
      <div class="box-body">

    {!! Form::model($user, ['route' => ['admin.access.users.update', $user->id], 'class' => 'form-horizontal', 'role' => 'form', 'method' => 'PATCH']) !!}

        <div class="form-group">
            <label class="col-lg-2 control-label">Name</label>
            <div class="col-lg-10">
                {!! Form::text('name', null, ['class' => 'form-control', 'placeholder' => 'First Name']) !!}
            </div>
        </div><!--form control-->

        <div class="form-group">
            <label class="col-lg-2 control-label">E-mail</label>
            <div class="col-lg-10">
                {!! Form::text('email', null, ['class' => 'form-control', 'placeholder' => 'E-mail Address']) !!}
            </div>
        </div><!--form control-->

        <div class="form-group">
            <label class="col-lg-2 control-label">Active</label>
            <div class="col-lg-10">
              <label>
                <input type="checkbox" value="1" name="status" class="" id="user-active" {{$user->status == 1 ? "checked='checked'" : ''}}>
                Activated
              </label>
            </div>
        </div><!--form control-->

        <div class="form-group">
            <label class="col-lg-2 control-label">Confirmed</label>
            <div class="col-lg-10">
              <label>
                <input type="checkbox"
                       value="1"
                       name="confirmed"
                       class=""
                       id="confirm-active" {{$user->confirmed == 1 ? "checked='checked'" : ''}}>
                Confirm
              </label>
            </div>
        </div><!--form control-->

        <div class="form-group">
            <label class="col-lg-2 control-label">Associated Roles</label>
            <div class="col-lg-10">
                @if (count($roles) > 0)
                    @foreach($roles as $role)
                  <div class="col-lg-4" style="border: 1px solid #f1f1f1">
                        <label>
                          <input type="checkbox"
                                 value="{{$role->id}}"
                                 name="assignees_roles[]" {{in_array($role->id, $user_roles) ? 'checked="checked"' : ""}}
                                 class=""
                                 id="role-{{$role->id}}">
                          {!! $role->name !!}
                        </label>

                        <div class="clearfix"></div>

                        @if (count($role->permissions) > 0)
                            <div>
                              @foreach ($role->permissions as $perm)
                                + {{$perm->display_name}}<br/>
                              @endforeach
                            </div>
                        @else
                            No permissions<br/><br/>
                        @endif
                    </div>
                    @endforeach
                @else
                    No Roles to set
                @endif
            </div>
        </div><!--form control-->

        <div class="form-group">
            <label class="col-lg-2 control-label">Other Permissions</label>

          <div class="col-lg-10">
            @if (count($permissions))
              @foreach ($permissions as $perm)
                <div class="col-lg-4" style="border: 1px solid #f1f1f1">
                  <label>
                    <input type="checkbox"
                           value="{{$perm->id}}"
                           name="permission_user[]"
                           {{in_array($perm->id, $user_permissions) ? 'checked="checked"' : ""}}
                           class="" id="permission-{{$perm->id}}">
                  </label>
                  {!! $perm->display_name !!}
                </div>
              @endforeach
            @else
              No other permissions
            @endif
          </div>
          <!--col 3-->
        </div><!--form control-->

        <div class="pull-left">
            <a href="{{route('admin.access.users.index')}}" class="btn btn-danger">Cancel</a>
        </div>

        <div class="pull-right">
            <input type="submit" class="btn btn-success" value="Save" />
        </div>
        <div class="clearfix"></div>

    {!! Form::close() !!}
  </div>
  </div>
@stop