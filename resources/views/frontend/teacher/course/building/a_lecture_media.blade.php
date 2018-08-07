<div class="media" data-type="{{$media->get_media_type() or ""}}">
  <div class="media-left">
    <div class="media-object">
      @if($media->id)
      <i class="fa fa-file-movie-o fa-5x" style="padding: 5px;"></i>
      @else
      <a href="{{route('teacher.my_library.video', ['id' => $media->id])}}" target="_blank">
        <img class="media-object" src="{{$media->thumbnail_link()}}" alt="{{$media->getTitle()}}">
      </a>
      @endif
    </div>
  </div>
  <div class="media-body">
    <div class="">
      <div class="form-group">
        <label>Thêm mới</label>
        <div>
          <button class="btn btn-default btn-xs"> Chọn file </button>
        </div>
      </div>
      <div class="form-group">
        <label>Chọn từ thư viện</label>
        <div class="input-group">
          <input class="form-control" placeholder="Gõ từ khóa để tìm kiếm" />
              <span class="input-group-btn">
                <button class="btn btn-default"> Chọn </button>
              </span>
        </div>
      </div>
    </div>
  </div>
</div>