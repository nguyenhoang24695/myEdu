@if($content->hasContent() && ( $content->getPrimaryData() || $content->getSecondaryData() ))
  @if($content->hasPrimaryData() )
    @include('frontend.teacher.course.building.a_lecture_media_view', ['media_object' => $content->getPrimaryData(), 'media_type' => $content->getPrimaryData()->get_media_type()])
  @endif
  @if($content->hasSecondaryData() )
    @include('frontend.teacher.course.building.a_lecture_media_view', ['media_object' => $content->getSecondaryData(), 'media_type' => $content->getSecondaryData()->get_media_type()])
  @endif
@endif