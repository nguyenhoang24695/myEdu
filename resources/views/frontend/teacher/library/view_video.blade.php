@extends('frontend.teacher.library.layout')

@section('after-scripts-end')
  {!! HTML::script('frontend/js/library.js') !!}
@stop

@section('sub_content')
  <div class="panel wiget_box">
    <div class="panel-heading">
      <h3 class="panel-title">{{$video->vid_title}}</h3>
    </div>
    <div class="panel-body ">
      <div class="col-md-12">
        <?php $player_id = 'preview_my_video_' . $video->id; //add_multi_source_player($player_id); ?>
        @include('includes.partials.response_video_player',
                  ['stream_link' => $streams,
                  'player_id' => $player_id,
                  'cover_img' => $video->thumbnail_link('original'),
                  'addition' => ['url' => $video->get_sub_link()],
                  'autoplay' => false])
      </div>
      <div class="col-md-12">
        <ul>
          <li>
            Tên : {{$video->vid_title}}
          </li>
          <li>
            URL set : {{$video->makeUrlSetLink()['url']}}
          </li>
          <li>
            Mô tả : {{$video->vid_description}}
          </li>
          <li>
            Độ dài : {{human_timesize($video->duration)}}
          </li>
          <li>
            Loại tập tin : {{$video->file_type}}
          </li>
          <li>
            Kích thước tập tin : {{human_filesize($video->file_size)}}
          </li>
          <li>
            {{trans('common.subtitle')}} : <a href="{{$video->get_sub_link()}}">{{basename($video->base_sub_path)}}</a>
          </li>
          <li>
            Ngày tạo : {{$video->created_at}}
          </li>
          <li>
            Người tạo : {{$video->user()->first()->name}}
          </li>
          <li>
            <a href="{{route('teacher.my_library.edit_video', ['id' => $video->id])}}">
              {{trans('common.edit')}}
            </a>
          </li>
        </ul>
      </div>
    </div>
  </div>
@endsection
