@extends ('backend.layouts.master')

@section ('title', 'Quản lý Khóa học')

@section('page-header')
  <h1>
    Quản lý Video
  </h1>
@endsection

@section ('breadcrumbs')
  <li><a href="{!!route('backend.dashboard')!!}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
  <li class="active">Danh sách video</li>
@stop

@section('content')
  <div class="margin-bottom">
    Filter
    {!! Form::open(['method' => 'get']) !!}
    <div class="row form-group">
      <div class="col-md-4 col-sm-6">
        <label>ID(s) cách nhau dấu ,</label>
        {!! Form::text('id', \Request::query('id'), ['class' => 'form-control']) !!}
      </div>
      <div class="col-md-4 col-sm-6">
        <label>Course id</label>
        {!! Form::text('course_id', \Request::query('course_id'), ['class' => 'form-control']) !!}
      </div>
      <div class="col-md-4 col-sm-6">
        <label>Trạng thái convert</label>
        {!! Form::select('status', $convert_status_list, \Request::query('status'), ['class' => 'form-control']) !!}
      </div>
    </div>
    <button class="btn btn-primary">Tìm</button>
    <a class="btn btn-default"
      href="{{action('Backend\CourseContentController@getVideoStatus')}}">
      Tất cả
    </a>
    {!! Form::close() !!}
  </div>
  <div class="box">
    <div class="box-body">
      <div class="row">
        <div class="col-sm-12">
          <table class="table table-striped table-bordered table-hover" style="font-size: 80%;">
            <thead>
            <tr>
              <th>ID</th>
              <th>Tên</th>
              <th>Người tạo</th>
              <th>Thuộc tính</th>
              <th>Lưu trữ</th>
              <th>Ngày cập nhật</th>
              <th>Convert(c/f/s)</th>
              <th>Thao tác</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($medias as $media)
              <tr>
                <td>{{$media->id}}</td>
                <td width="200" title="{{$media->getSubTitle()}}">{{$media->getTitle()}}</td>
                <td>{{$media->user->name}}</td>
                <td>
                  {{human_timesize($media->duration)}}
                  || {{human_filesize($media->file_size)}}
                  || {{$media->file_type}}
                </td>
                <td title="{{$media->get_media_path()}}">{{$media->get_media_disk()}}</td>

                <td title="{{$media->updated_at}}">{{$media->updated_at->diffForHumans()}}</td>
                <td>{{$media->convert_status_string}}/{{$media->convert_fail_count}}/{{$media->convert_success_count}}</td>
                <td>
                  <div class="btn-group">
                    <button type="button"
                            class="btn btn-xs btn-default dropdown-toggle"
                            data-toggle="dropdown"
                            aria-haspopup="true"
                            aria-expanded="false">
                      Action <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu">
                      @if($media->convert_status > 0 || $media->convert_status == 2)
                        <li>
                          <a
                            href="{{action('Backend\CourseContentController@getReconvertVideo', ['video_id' => $media->id])}}">
                            Reconvert
                          </a>
                        </li>
                        <li>
                          <a
                            href="{{action('Backend\CourseContentController@getReconvertVideo', ['video_id' => $media->id, 'option' => \App\Console\Commands\ConvertVideo::__RE_SD])}}">
                            Reconvert SD
                          </a>
                        </li>
                        <li>
                          <a
                            href="{{action('Backend\CourseContentController@getReconvertVideo', ['video_id' => $media->id, 'option' => \App\Console\Commands\ConvertVideo::__RE_HD])}}">
                            Reconvert HD
                          </a>
                        </li>
                      @endif
                      <li>
                        <a
                          href="{{action('Backend\CourseContentController@getReconvertVideo', ['video_id' => $media->id, 'option' => \App\Console\Commands\ConvertVideo::__GEN_INFO])}}">
                          Update Info
                        </a>
                      </li>
                    </ul>
                  </div>
                 </td>
              </tr>
            @endforeach
            </tbody>
          </table>
        </div>
      </div>
      <!--Phân trang-->
      {!! $medias->appends(\Request::query())->render() !!}
      <div class="clearfix"></div>
    </div>
  </div>
@stop