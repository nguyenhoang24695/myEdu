<div class="col-xs-12">
  <h4>{{$course_content ? $course_content->get_title() : ""}}</h4>
  {{--Mo ta--}}
  <div>
    {!! $course_content ? $course_content->get_sub_title() : "" !!}
  </div>
  @if(!auth()->guest() && $course_content->getContent()->hasSecondaryData())
  {{$course_content->getContent()->getSecondaryData()->get_download_link()}}
  @endif
  {{--Cac tai lieu dinh kem, ...--}}
  @if($course_content->external_sources->count())
  <h4> Danh sách tư liệu ngoài </h4>
  <div>
    <div class="list-group list_external_source">
      @foreach($course_content->external_sources as $source)
        <div class="list-group-item" data-id="{{$source->id}}">
          @if($source->source_type == 'youtube')
            <a rel="nofollow" href="{{$source->content}}" target="_blank">
              <i class="fa fa-youtube"></i> {{$source->title == '' ? trans('course.external_source') : $source->title}}
            </a>
          @elseif($source->source_type == 'url')
            <a rel="nofollow" href="{{$source->content}}" target="_blank">
              <i class="fa fa-external-link"></i> {{$source->title == '' ? trans('course.external_source') : $source->title}}
            </a>
          @else
            <div> {{$source->title == '' ? trans('course.external_source') : $source->title}} </div>
            <div> {{$source->content}} </div>
          @endif
        </div>
      @endforeach
    </div>
  </div>
  @endif
</div>

