@extends ('backend.layouts.master')

@section ('title', 'Quản lý khóa học chạy marketing')

@section('after-scripts-end')
  {{--{!! HTML::script('backend/js/searchindex.js') !!}--}}
@endsection

@section('page-header')
  <h1>
    Danh sách khóa học chạy marketing
  </h1>
@endsection

@section ('breadcrumbs')
  <li><a href="{!!route('backend.dashboard')!!}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
  <li><a href="{{route('backend.marketing_course.index')}}">Marketing courses</a></li>
  <li class="active">Search and add</li>
@stop

@section('content')
  <div class="box">
    <div class="box-header with-border">
      <h3 class="box-title">Filter</h3>
    </div>
    <div class="box-body">
      {!! Form::open(['method' => 'get']) !!}
      <div class="row">
        <div class="col-sm-6">
          <div class="form-group">
            <label>Từ khóa</label>
            {!! Form::text('keyword', \Request::query('keyword'), ['class' => 'form-control']) !!}
          </div>
        </div>
        <div class="col-sm-6">
          <div class="form-group">
            <button class="btn btn-primary">Tìm</button>
          </div>
        </div>
      </div>
      {!! Form::close() !!}
    </div>
  </div>
  <div class="box">
    <div class="box-header with-border">
      <h3 class="box-title">Danh sách khóa học</h3>
    </div>
    <div class="box-body">
      @include('backend.marketing_course.partial.add_list', ['courses' => $courses])
      <div class="pagination">
        {!! $c_paginate !!}
      </div>
    </div>
  </div>
@endsection