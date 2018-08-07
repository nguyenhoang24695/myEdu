@extends ('backend.layouts.master')

@section ('title', 'Danh sách nạp thẻ')

@section('after-scripts-end')
  {{--{!! HTML::script('backend/js/searchindex.js') !!}--}}
@endsection

@section('page-header')
  <h1>
    Danh sách nạp thẻ
  </h1>
@endsection

@section ('breadcrumbs')
  <li><a href="{!!route('backend.dashboard')!!}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
  <li class="active">Revenue report</li>
@stop

@section('content')
  <div class="box">
    <div class="box-header with-border">
      <h3 class="box-title">Danh sách thẻ nạp</h3>
    </div>
    <div class="box-body">
      <table class="table table-responsive table-striped table-bordered">
        <tr>
          <th>ID</th>
          <th>User</th>
          <th>Pin</th>
          <th>Serial</th>
          <th>Status</th>
          <th>Created</th>
        </tr>

      </table>
      <div>
        <a href="" class="btn btn-xs btn-primary">Xem toàn bộ</a>
      </div>
    </div>
  </div>
@endsection