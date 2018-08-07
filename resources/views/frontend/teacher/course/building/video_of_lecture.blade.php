<div class="media" data-type="video">
  <div class="media-left">
    <a href="{{route('teacher.my_library.video', ['id' => $video->id])}}" target="_blank">
      <img class="media-object" src="{{$video->thumbnail_link()}}" alt="{{$video->vid_title}}">
    </a>
  </div>
  <div class="media-body">
    <h4 class="media-heading">{{$video->vid_title}}</h4>

    <div class="media-brief">
      {{$video->vid_description}}
    </div>
    <div class="media-control">
      <button class="btn btn-xs btn-danger btn_delete">{{trans('common.delete')}}</button>
      <a class="btn btn-xs btn-primary" target="_blank"
         href="{{route('teacher.my_library.video', ['id' => $video->id])}}">{{trans('common.detail')}}</a>
    </div>
  </div>
  <span class="media-delete">
    <i class="fa fa-close"></i>
  </span>
</div>