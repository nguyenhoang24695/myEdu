<div class="media" data-type="{{$media_type}}">
  <div class="media-left">
    <div class="media-object">
      @if($media_type == 'video')
        <i class="fa fa-file-movie-o fa-5x" style="padding: 5px;"></i>
      @elseif($media_type == 'document')
        <i class="fa fa-file-text fa-5x" style="padding: 5px;"></i>
      @elseif($media_type == 'audio')
        <i class="fa fa-file-audio-o" style="padding: 5px;"></i>
      @else
        <i class="fa fa-file-text fa-5x" style="padding: 5px;"></i>
      @endif
    </div>
  </div>
  <div class="media-body">
    <div class="">
      <div class="form-group">
        <label>Thêm mới</label>
        <div>
          <a class="btn btn-default btn-xs" href="{{route('teacher.my_library.add_' . $media_type)}}" target="_blank"> Chọn file </a>
        </div>
      </div>
      <div class="form-group">
        <label>Chọn từ thư viện</label>
        <input class="form-control insert_from_my_library" placeholder="Tìm kiếm..."/>
      </div>
      <button class="btn btn-primary btn-xs assign_{{$media_type}}_to_lecture"> Chọn </button>
    </div>
  </div>
</div>