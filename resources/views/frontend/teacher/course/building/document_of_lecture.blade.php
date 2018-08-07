<div class="media" data-type="{{$document->get_media_type()}}">
  <div class="media-left">
    <a href="{{route('teacher.my_library.document', ['id' => $document->id])}}" target="_blank">
      <img class="media-object" src="{{$document->thumbnail_link()}}" alt="{{$document->doc_title}}">
    </a>
  </div>
  <div class="media-body">
    <h4 class="media-heading">{{$document->doc_title}}</h4>

    <div class="media-brief">
      {{$document->doc_description}}
    </div>
    <div class="media-control">
      <button class="btn btn-xs btn-danger btn_delete">{{trans('common.delete')}}</button>
      <a class="btn btn-xs btn-primary" target="_blank"
         href="{{route('teacher.my_library.document', ['id' => $document->id])}}">{{trans('common.detail')}}</a>
    </div>
  </div>
  <span class="media-delete">
    <i class="fa fa-close"></i>
  </span>
</div>