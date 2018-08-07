<div class="media" data-type="{{$media_type}}">
  <div class="media-left">
    <a href="{{route('teacher.my_library.video', ['id' => $media_object->get_media_id()])}}" target="_blank">
      <img class="media-object" src="{{$media_object->thumbnail_link()}}" alt="{{$media_object->getTitle()}}">
    </a>
  </div>
  <div class="media-body">
    <h4 class="media-heading">
      <a href="{{route('teacher.my_library.' . $media_type, ['id' => $media_object->get_media_id()])}}" target="_blank">
       {{$media_object->getTitle()}}
      </a>
    </h4>

    <div class="media-brief">
      {{$media_object->getSubTitle()}}
    </div>
  </div>
  <span class="media-delete">
    <i class="fa fa-close"></i>
  </span>
</div>