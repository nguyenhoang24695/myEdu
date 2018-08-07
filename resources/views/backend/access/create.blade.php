@extends ('backend.layouts.master')

@section ('title', 'User Management | Create User')

@section ('before-styles-end')
  {!! HTML::style('css/plugin/jquery.onoff.css') !!}
@stop

@section('page-header')
  <h1>
    User Management
    <small>Create User</small>
  </h1>
@endsection

@section ('breadcrumbs')
  <li><a href="{!!route('backend.dashboard')!!}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
  <li>{!! link_to_route('admin.access.users.index', 'User Management') !!}</li>
  <li class="active">{!! link_to_route('admin.access.users.create', 'User Management') !!}</li>
@stop

@section('content')
  @include('backend.access.includes.partials.header-buttons')
  <div class="box">
    <div class="box-header">
      <h4>Thêm người dùng</h4>
    </div>
    <div class="box-body">
      {!! Form::open(['route' => 'admin.access.users.store', 'class' => 'form-horizontal', 'role' => 'form', 'method' => 'post']) !!}
      @if($errors->any())
        <div class="text-error">
          <ul>
            @foreach($errors->all() as $error)
              <li>
                {{$error}}
              </li>
            @endforeach
          </ul>

        </div>
      @endif
      <div class="form-group">
        <label class="col-lg-2 control-label">Name</label>

        <div class="col-lg-10">
          {!! Form::text('name', null, ['class' => 'form-control', 'placeholder' => 'Full Name']) !!}
        </div>
      </div>
      <!--form control-->

      <div class="form-group">
        <label class="col-lg-2 control-label">E-mail</label>

        <div class="col-lg-10">
          {!! Form::text('email', null, ['class' => 'form-control', 'placeholder' => 'E-mail Address']) !!}
        </div>
      </div>
      <!--form control-->

      <div class="form-group">
        <label class="col-lg-2 control-label">Password</label>

        <div class="col-lg-10">
          {!! Form::password('password', ['class' => 'form-control']) !!}
        </div>
      </div>
      <!--form control-->

      <div class="form-group">
        <label class="col-lg-2 control-label">Confirm Password</label>

        <div class="col-lg-10">
          {!! Form::password('password_confirmation', ['class' => 'form-control']) !!}
        </div>
      </div>
      <!--form control-->

      <div class="form-group">
        <label class="col-lg-2 control-label">Active</label>

        <div class="col-lg-10">
          <label>
            <input type="checkbox" value="1" name="status" class="" id="user-active"
                   checked="checked">
            Activated
          </label>
          <!--green checkbox-->
        </div>
      </div>
      <!--form control-->

      <div class="form-group">
        <label class="col-lg-2 control-label">Confirmed</label>

        <div class="col-lg-10">
          <label>
            <input type="checkbox" value="1" name="confirmed" class="toggleBtn onoffswitch-checkbox"
                   id="confirm-active" checked="checked">
            confirmed
          </label>
          <!--green checkbox-->
        </div>
      </div>
      <!--form control-->

      <div class="form-group">
        <label class="col-lg-2 control-label">Send Confirmation E-mail<br/>
          <small>(If confirmed is off)</small>
        </label>

        <div class="col-lg-10">
          <label>
            <input type="checkbox" value="1" name="confirmation_email" class=""
                   id=confirmation-email-active">
            Send
          </label>
          <!--green checkbox-->
        </div>
      </div>
      <!--form control-->

      <div class="form-group">
        <label class="col-lg-2 control-label">Associated Roles</label>

        <div class="col-lg-10">
          @if (count($roles) > 0)
            @foreach($roles as $role)
              <div class="col-lg-4" style="border: 1px solid #f1f1f1;">
                <label>
                  <input type="checkbox" value="{{$role->id}}" name="assignees_roles[]"
                         class="" id="role-{{$role->id}}">
                  {!! $role->name !!}
                </label>

              @if (count($role->permissions) > 0)
                @foreach ($role->permissions as $perm)
                  + {{$perm->display_name}}<br/>
                  @endforeach
              @else
                No permissions<br/><br/>
              @endif
              </div>
            @endforeach
          @else
            No Roles to set
          @endif
        </div>
      </div>
      <!--form control-->

      <div class="form-group">
        <label class="col-lg-2 control-label">Other Permissions</label>

        <div class="col-lg-10">
          @if (count($permissions))
            @foreach ($permissions as $perm)
              <div class="col-lg-4" style="border: 1px solid #f1f1f1;">
                <label>
                  <input type="checkbox" value="{{$perm->id}}" name="permission_user[]"
                         class="" id="permission-{{$perm->id}}">
                  {!! $perm->display_name !!}
                </label>
              </div>
            @endforeach
          @else
            No other permissions
          @endif
        </div>
        <!--col 3-->
      </div>
      <!--form control-->

      <div class="pull-left">
        <a href="{{route('admin.access.users.index')}}" class="btn btn-danger">Cancel</a>
      </div>

      <div class="pull-right">
        <input type="submit" class="btn btn-success" value="Save"/>
      </div>
      <div class="clearfix"></div>

      {!! Form::close() !!}
    </div>
  </div>
@stop