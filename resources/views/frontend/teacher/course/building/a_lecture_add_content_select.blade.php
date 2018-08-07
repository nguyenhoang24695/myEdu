<div class="a_lecture_content_add_select closed" data-id="{{$lecture_id}}">
  <div class="add_video_lecture col-xs-3">
    <i class="fa fa-facetime-video"></i> {{trans('course.add_video')}}
  </div>
  <div class="add_audio_lecture col-xs-3">
    <i class="fa fa-volume-up"></i> {{trans('course.add_audio')}}
  </div>
  <div class="add_document_lecture col-xs-3">
    <i class="fa fa-file-text-alt"></i> {{trans('course.add_document')}}
  </div>
  <div class="add_text_lecture col-xs-3">
    <i class="fa fa-file-text-alt"></i> {{trans('course.add_text')}}
  </div>
  <div class="add_mixed_lecture col-xs-3">
    <i class="fa fa-desktop"></i> {{trans('course.add_mix')}}
  </div>
  <button type="button" class="btn-xs btn btn-default btn-cancel">{{trans('common.cancel')}}</button>
</div>