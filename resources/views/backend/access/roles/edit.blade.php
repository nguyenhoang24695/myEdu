@extends ('backend.layouts.master')

@section ('title', 'Role Management | Edit Role')

@section ('before-styles-end')
    {!! HTML::style('css/plugin/jquery.onoff.css') !!}
@stop

@section('page-header')
    <h1>
        User Management
        <small>Edit Role</small>
    </h1>
@endsection

@section ('breadcrumbs')
     <li><a href="{!!route('backend.dashboard')!!}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
     <li>{!! link_to_route('admin.access.users.index', 'User Management') !!}</li>
     <li>{!! link_to_route('admin.access.roles.index', 'Role Management') !!}</li>
     <li>{!! link_to_route('admin.access.roles.edit', 'Edit '.$role->name, $role->id) !!}</li>
@stop

@section('content')
    @include('backend.access.includes.partials.header-buttons')
    <div class="box">
      <div class="box-header">
        <h4>Sửa role</h4>
      </div>
      <div class="box-body">
        {!! Form::model($role, ['route' => ['admin.access.roles.update', $role->id], 'class' => 'form-horizontal', 'role' => 'form', 'method' => 'PATCH']) !!}

        <div class="form-group">
          <label class="col-lg-2 control-label">Role Name</label>

          <div class="col-lg-10">
            {!! Form::text('name', null, ['class' => 'form-control', 'placeholder' => 'Role Name']) !!}
          </div>
        </div>
        <!--form control-->

        <div class="form-group">
          <label class="col-lg-2 control-label">Associated Permissions</label>

          <div class="col-lg-3">
            @if (count($permissions) > 0)
              @foreach($permissions as $perm)
                <label>
                  <input type="checkbox" value="{{$perm->id}}" name="role_permissions[]"
                         class=""
                         id="perm-{{$perm->id}}" {{in_array($perm->id, $role_permissions) ? 'checked="checked"' : ""}}>
                  {!! $perm->display_name !!}
                </label>

                <div class="clearfix"></div>
              @endforeach
            @else
              No permissions to set
            @endif
          </div>
        </div>
        <!--form control-->

        <div class="pull-left">
          <a href="{{route('admin.access.roles.index')}}" class="btn btn-danger">Cancel</a>
        </div>

        <div class="pull-right">
          <input type="submit" class="btn btn-success" value="Save"/>
        </div>
        <div class="clearfix"></div>

        {!! Form::close() !!}
      </div>
    </div>
@stop