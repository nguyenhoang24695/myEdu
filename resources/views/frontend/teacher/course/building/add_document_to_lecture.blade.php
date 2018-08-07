Thêm tài liệu văn bản vào bài học
<div class="controls row">
  <div class="col-sm-6">
    <label>
      <input type="radio" class="add_document_options" name="add_document_options" value="upload" checked="checked" /> Tải lên
    </label>
  </div>
  <div class="col-sm-6">
    <label>
      <input type="radio" class="add_document_options" name="add_document_options" value="library" /> Chọn từ thư viện
    </label>
  </div>
</div>
<div class="document_form form_upload">
  <div class="progress">
    <div class="progress-bar progress-bar-info progress-bar-striped active"
         role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100"
         style="width: 0%">
      <span class="progress-bar-pc">0%</span>
    </div>
  </div>
  <div class="upload_zone">
    <div class="upload_notice"></div>
    <button class="btn btn-xs btn-info btn_select_file">Chọn file</button>
    <span class="upload_file_name"> Chọn tập tin hoặc kéo vào đây để tải lên ... </span> <span class="upload_file_size"></span>
  </div>
</div>
<div class="document_form form_library" style="display: none">
  <div class="input-group">
    <input type="text" class="form-control input_keyword" placeholder="{{trans('common.keyword')}}...">
  <span class="input-group-btn">
    <button class="btn btn-default btn_search" type="button">{{trans('common.search')}}</button>
  </span>
  </div>
  <div class="media_list">
  </div>
</div>
<div class="controls padding">
  <button class="btn btn-xs btn_save">{{trans('common.save')}}</button>
  <button class="btn btn-xs btn_cancel">{{trans('common.back')}}</button>
</div>