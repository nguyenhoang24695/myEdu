<div class="a_quizzes_form" data-id="{{$content->id or ''}}">
  <div class="form-group">
    <label>Tiêu đề bài kiểm tra.</label>
    <input 
    value="{{$content->qui_title or '' }}" 
    class="form-control input_quizzes_name"
    placeholder="Tiêu đề"/>
  </div>
  
  <div class="checkbox">
    <label>
      <input 
      type="checkbox" 
      class="input_require"
      {{ (!empty($content) && $content->require) ? 'checked':'' }}> Bắt buộc đúng 100% mới được chuyển bài
    </label>
  </div>
  
  <div class="form-group">
    <label>
      Mô tả bài kiểm tra
    </label>
    <textarea 
    class="form-control input_quizzes_description"
    placeholder="Mô tả">{{$content->qui_sub_title or '' }}</textarea>
  </div>
  <button type="button" class="btn-sm btn btn-primary btn-save btn_save">{{trans('common.save')}}</button>
  <button type="button" class="btn-sm btn btn-default btn-cancel btn_cancel">{{trans('common.cancel')}}</button>
</div>