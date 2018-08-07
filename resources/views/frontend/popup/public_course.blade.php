<div class="modal fade unibee-model" id="modal_public_course" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <div class="close triangle-topright" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </div>
        <h4 class="modal-title" id="myModalLabel">Xuất bản khóa học</h4>
      </div>
      <div class="modal-body">
        <div class="alert alert-success" role="alert">Sau khi xuất bản khóa học BQT sẽ duyệt khóa học trong (01) ngày làm việc. Nếu trong thời gian trên mà khóa học chưa được duyệt bạn vui lòng liên hệ với <strong>BQT</strong> để được trợ giúp.</div>
        <form action="{{ route('build_course.public',['id'=>$course->id]) }}" method="POST" accept-charset="utf-8">
          <input type="hidden" name="id" value="{{ $course->id }}">
          {!! csrf_field() !!}
          <button type="submit" class="btn btn-primary btn_action_reviews">Đồng ý xuất bản</button>
        </form>
      </div>
    </div>
  </div>
</div>