<div class="a_section_content" data-id="{{$content->id}}">
  <div class="form-group row">
    <div class="col-sm-4 col-md-3 col-lf-2">
      {{trans('course.section_title')}}
    </div>
    <div class="col-sm-8 col-md-9 col-lf-10">
      <strong>{{$content->sec_title}}</strong>
    </div>
  </div>
  <div class="form-group row">
    <div class="col-sm-4 col-md-3 col-lf-2">
      {{trans('course.section_description')}}
    </div>
    <div class="col-sm-8 col-md-9 col-lf-10">
      <i>{{$content->sec_sub_title}}</i>
    </div>
  </div>
  <span class="text-danger"></span>
</div>