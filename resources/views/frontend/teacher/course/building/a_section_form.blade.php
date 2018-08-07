<div class="a_section_form" data-id="{{$content->id or ''}}">
  <div class="form-group">
    <label>
      {{trans('course.section_title')}}
    </label>
    <input value="{{$content->sec_title or ''}}" class="form-control input_section_name"
           placeholder="{{trans('course.section_title')}}"/>
  </div>
  <div class="form-group">
    <label>
      {{trans('course.section_description')}}
    </label>
    <textarea class="form-control input_section_description" rows="3"
              placeholder="{{trans('course.section_description')}}">{{$content->sec_sub_title or ''}}</textarea>
  </div>
  <button type="button" class="btn-sm btn btn-primary btn-save">{{trans('common.save')}}</button>
  <button type="button" class="btn-sm btn btn-default btn-cancel">{{trans('common.cancel')}}</button>
  <span class="text-danger"></span>
</div>