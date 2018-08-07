<div class="a_lecture_form" data-id="{{$content->id or ''}}">
  <div class="form-group">
    <label>{{trans('course.lecture_title')}}</label>
    <input value="{{$content->lec_title or '' }}" class="form-control input_lecture_name"
           placeholder="{{trans('course.lecture_title')}}"/>
  </div>
  @if(!empty($course_content))
  <div class="form-group">
    <label>Bài học miễn phí</label>
    <input type="checkbox"
           value="public"
           class="input_lecture_privacy"
           name="input_lecture_privacy" {{$course_content->access_privacy == "public" ? 'checked' : ''}} />
    <i class="small" title="Nếu khóa học của bạn có phí, mọi người vẫn có thể xem bài học miễn phí" style="cursor: help;">(?)</i>
  </div>
  @endif
  <div class="form-group">
    <label>
      {{trans('common.description')}}
    </label>
      <textarea name=""
                class="form-control input_lecture_description"
                placeholder="{{trans('common.description')}}">{{$content->lec_sub_title or ""}}</textarea>
  </div>
  <button type="button" class="btn-sm btn btn-primary btn-save btn_save">{{trans('common.save')}}</button>
  <button type="button" class="btn-sm btn btn-default btn-cancel btn_cancel">{{trans('common.cancel')}}</button>
</div>