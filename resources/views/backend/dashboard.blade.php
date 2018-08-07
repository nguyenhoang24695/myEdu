@extends('backend.layouts.master')

@section('page-header')
    <h1>
        {{trans('admin.title')}}
        <small>{{trans('admin.subtitle')}}</small>
    </h1>
@endsection

@section('breadcrumbs')
    <li><a href="{!!route('backend.dashboard')!!}"><i class="fa fa-dashboard"></i> {{trans('admin.dashboard')}}</a></li>
    <li class="active">{{trans('admin.here')}}</li>
@endsection

@section('content')
    <div class="box box-success">
        <div class="box-header with-border">
          <h3 class="box-title">{{trans('admin.hello_user', ['user_name' => auth()->user()->name])}}</h3>
          <div class="box-tools pull-right">
              <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
          </div>
        </div><!-- /.box-header -->
        <div class="box-body">
            <p>
                {{trans('admin.dashboard_intro')}}
            </p>
        </div><!-- /.box-body -->
    </div><!--box box-success-->
@endsection