<div class="a_lecture_content" data-id="{{$content->id or ''}}">
  <div class="a_lecture_media">
    @include('frontend.teacher.course.building.lecture_media', ['content' => $content])
  </div>

  <div class="lecture_external_source">
    <div class="list-group lecture_external_source_list">
      <div class="list-group-item">
        <div class="view_attachment_title">
          <div class="row form-group form-group-sm ">
            <div class="col-sm-8">
              <input class="form-control source_title" placeholder="Tên"/>
            </div>
            <div class="col-sm-4">
              <select class="form-control source_type">
                <option value="url">Liên kết</option>
                <option value="youtube">Youtube</option>
              </select>
            </div>
          </div>

        </div>
        <div class=" view_attachment_content">
          <div class="row form-group form-group-sm">
            <div class="col-sm-8">
              <input class="form-control source_content" placeholder="Nội dung"/>
            </div>
            <div class="col-sm-4">
              <button class="btn btn-xs btn-default btn_save"><i class="fa fa-save"></i></button>
              <button class="btn btn-xs btn-default btn_cancel"><i class="fa fa-remove"></i></button>
            </div>
          </div>

        </div>
        <span class="btn_delete_source">
          <i class="fa fa-close"></i>
        </span>
      </div>
      @foreach($course_content->external_sources as $external_source)
      <div class="list-group-item" data-id="{{$external_source->id}}">
        <div> {{$external_source->title}} </div>
        <div> {{$external_source->content}} </div>
        <span class="btn_delete_source">
          <i class="fa fa-close"></i>
        </span>
      </div>
      @endforeach
    </div>
  </div>

  <div class="row">
    <div class="col-sm-4">
      <div class="btn-group btn_add_lecture_content">
        <button type="button"
                class="btn btn-default dropdown-toggle"
                data-toggle="dropdown"
                aria-haspopup="true" aria-expanded="false">
          <i class="fa fa-plus"></i> Thêm nội dung <span class="caret"></span>
        </button>
        <ul class="dropdown-menu">
          <li><a href="javascript:void(0);" data-add="video" class="add_video">Video</a></li>
          <li><a href="javascript:void(0);" data-add="document" class="add_document">Tài liệu</a></li>
          <li><a href="javascript:void(0);" data-add="audio" class="add_audio">Audio</a></li>
          <li><a href="javascript:void(0);" data-add="other" class="add_other">Khác</a> </li>
        </ul>
      </div>
    </div>
    <div class="col-sm-8">
      Bạn có thể thêm tối đa 1 video hoặc 1 audio, 1 tài liệu, các tài liệu khác sẽ được hiển thị như tài nguyên đính kèm của bài học
    </div>
  </div>

  <div class="a_lecture_description">
    <h4 class="description_title">{{trans('common.description')}}</h4>
    <div class="a_lecture_description_editor">
      {!! $content->lec_sub_title !!}
    </div>
  </div>
  <div class="clearfix"></div>
</div>