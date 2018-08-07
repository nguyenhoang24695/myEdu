@extends('frontend.teacher.library.layout')

@section('after-scripts-end')
  {!! HTML::script('frontend/js/library.js') !!}
@stop

@section('sub_content')
  <div class="panel wiget_box">
    <div class="panel-heading">
      <h3 class="panel-title">{{$audio->aud_title}}</h3>
    </div>
    <div class="panel-body ">
      <div class="col-md-12">
        <audio id="audio_player_{{$audio->id}}" class="video-js vjs-default-skin" controls
               preload="none" data-setup='{responsive: true}' poster="{{$audio->thumbnail_link()}}">
          <source src="{{$download_link}}" type='audio/mp3'/>
        </audio>
      </div>
      <div class="col-md-12">
        <ul>
          <li>
            Tên : {{$audio->aud_title}}
          </li>
          <li>
            Độ dài : {{human_timesize($audio->duration)}}
          </li>
          <li>
            Kích thước tập tin : {{human_filesize($audio->file_size)}}
          </li>
          <li>
            Loại tập tin : {{$audio->file_type}}
          </li>
          <li>
            Mô tả : {{$audio->aud_description}}
          </li>
          <li>
            Ngày tạo : {{$audio->created_at}}
          </li>
          <li>
            Người tạo : {{$audio->user()->first()->name}}
          </li>
        </ul>
      </div>
    </div>
  </div>
@endsection