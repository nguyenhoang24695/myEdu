<div class="modal fade unibee-model" id="profileUpdate" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <div class="close triangle-topright" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </div>
        <h4 class="modal-title" id="myModalLabel">Cập nhật thông tin cá nhân</h4>
      </div>
      <div class="modal-body">
        <form enctype="multipart/form-data" method="POST" action="{{ route('frontend.profile.update') }}" class="updateInfoProfile has-success-unibee">
          <input type="hidden" name="id" value="{{ $profile->id }}">
          <div class="form-group up_cover">
            <label for="exampleInputFile">Cập nhật ảnh bìa</label>
            <input type="file" class="form-control" name="cover">
            <p class="help-block cover_error">Kích thước đề nghị tối thiểu 1600x340.</p>
          </div>
          <div class="form-group up_avatar">
            <label for="exampleInputFile">Cập nhật ảnh đại diện</label>
            <input type="file" class="form-control" name="avatar">
            <p class="help-block avatar_error">Kích thước đề nghị tối thiểu 200x200.</p>
          </div>
          <div class="form-group up_name">
            <label for="exampleInputFile">Tên hiển thị</label>
            <input type="text" name="name" class="form-control old_name" placeholder="Cập nhật tên hiển thị" value="{{ $profile->name }}">
          </div>
          <div class="form-group up_status_text">
            <label for="exampleInputFile">Hãy chia sẻ cho chúng tôi biết bạn đang có suy nghĩ gì?</label>
            <textarea rows="3" maxlength="600" name="status_text" class="form-control old_status" placeholder="Hãy chia sẻ cho chúng tôi biết bạn đang có suy nghĩ gì">{{ $profile->status_text }}</textarea>
          </div>
          {!! csrf_field() !!}
          <button type="submit" class="btn btn-primary">Cập nhật</button>
          <a href="#" class="btn btn-link" target="_blank">Cách tìm ảnh đẹp trên google</a>
        </form>
      </div>
    </div>
  </div>
</div>