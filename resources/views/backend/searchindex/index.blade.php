@extends ('backend.layouts.master')

@section ('title', 'Quản lý Searchindex')

@section('after-scripts-end')
  {!! HTML::script('backend/js/searchindex.js') !!}
  @endsection

@section('page-header')
  <h1>
    Quản lý Search index
  </h1>
@endsection

@section ('breadcrumbs')
  <li><a href="{!!route('backend.dashboard')!!}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
  <li class="active">Quản lý search index</li>
@stop

@section('content')
  <div class="box">
    <div class="box-body">
      <div class="form-group">
        <a href="{{route('backend.searchindex.index_manipulate', ['type' => 'all', 'action' => 'reinit'])}}" class="btn btn-xs btn-danger">Delete and rebuild mappings of all types</a>
        <p class="small">
        Để cập nhật setting bắt buộc phải sử dụng tính năng này, chú ý sau khi rebuild sẽ không tự động index,
        hãy index từng type bằng các điều hướng bên dưới.
        Nếu chỉ cần cập nhật mapping mà ko sử dụng setting mới thì ko cần tính năng này.
        </p>
      </div>
      <h3>Types list</h3>
      <table class="table table-bordered">
        <tr>
          <th>Type</th>
          <th>Show info</th>
          <th>Reindex</th>
          <th>Update mapping</th>
        </tr>
        <tr>
          <td>Courses</td>
          <td><a href="{{route('backend.searchindex.index_manipulate', ['type' => 'courses', 'action' => 'info'])}}" class="btn btn-xs btn-info">Show info</a></td>
          <td>
            {!! Form::open(['method' => 'get', 'url' => route('backend.searchindex.index_manipulate', ['type' => 'courses', 'action' => 'reindex'])]) !!}
            <input name="ids" placeholder="blank/id/id1_id2" />
            <input name="source" value="course" type="hidden"/>
            <button
              class="btn btn-xs btn-warning">Reindex</button>
            {!! Form::close() !!}
          </td>
        </tr>
        <tr>
          <td>Users</td>
          <td><a href="{{route('backend.searchindex.index_manipulate', ['type' => 'users', 'action' => 'info'])}}" class="btn btn-xs btn-info">Show info</a></td>
          <td>
            {!! Form::open(['method' => 'get', 'url' => route('backend.searchindex.index_manipulate', ['type' => 'users', 'action' => 'reindex'])]) !!}
            <input name="ids" placeholder="blank/id/id1_id2" />
            <input name="source" value="user" type="hidden"/>
            <button href="" class="btn btn-xs btn-warning">Reindex</button>
            {!! Form::close() !!}
          </td>
        </tr>
        <tr>
          <td>Tags</td>
          <td><a href="{{route('backend.searchindex.index_manipulate', ['type' => 'tags', 'action' => 'info'])}}" class="btn btn-xs btn-info">Show info</a></td>
          <td>
            {!! Form::open(['method' => 'get', 'url' => route('backend.searchindex.index_manipulate', ['type' => 'tags', 'action' => 'reindex'])]) !!}
            <input name="ids" placeholder="blank/id/id1_id2" />
            <input name="source" value="tag" type="hidden"/>
            <button href="" class="btn btn-xs btn-warning">Reindex</button>
            {!! Form::close() !!}
          </td>
        </tr>
        <tr>
          <td>Videos</td>
          <td><a href="{{route('backend.searchindex.index_manipulate', ['type' => 'videos', 'action' => 'info'])}}" class="btn btn-xs btn-info">Show info</a></td>
          <td>
            {!! Form::open(['method' => 'get', 'url' => route('backend.searchindex.index_manipulate', ['type' => 'videos', 'action' => 'reindex'])]) !!}
            <input name="ids" placeholder="blank/id/id1_id2" />
            <input name="source" value="video" type="hidden"/>
            <button href="" class="btn btn-xs btn-warning">Reindex</button>
            {!! Form::close() !!}
          </td>
        </tr>
        <tr>
          <td>Marketing courses</td>
          <td><a href="{{route('backend.searchindex.index_manipulate', ['type' => 'marketing_courses', 'action' => 'info'])}}" class="btn btn-xs btn-info">Show info</a></td>
          <td>
            {!! Form::open(['method' => 'get', 'url' => route('backend.searchindex.index_manipulate', ['type' => 'marketing_courses', 'action' => 'reindex'])]) !!}
            <input name="ids" placeholder="blank/id/id1_id2" />
            <input name="source" value="video" type="hidden"/>
            <button href="" class="btn btn-xs btn-warning">Reindex</button>
            {!! Form::close() !!}
          </td>
        </tr>
      </table>
      <h3>Output</h3>
      <div id="output" class="" style="padding: 10px 0;">

      </div>

    </div>
  </div>
@endsection