<form class="discussions has-primary-unibee" id="lecture_discussions" method="POST" action="{{ route('frontend.discussion.store') }}">
  <div class="form-group hide">
    <input name="title" type="text" class="form-control dis_title" placeholder="Bắt đầu một cuộc thảo luận mới">
  </div>

  <input type="text" class="form-control dis_title" placeholder="Bắt đầu một cuộc thảo luận mới" style="margin-bottom: 15px;">
  
  <div class="form-group hide ishide">
    <textarea name="content" rows="3" class="form-control content" placeholder="Đặt câu hỏi thảo luận về bài giảng" style="margin-bottom: 5px"></textarea>
    <span class="label label-danger discussions_err"></span>
    <input type="hidden" name="cou_id" value="{{ $course_id }}" >
    <input type="hidden" name="content_id" value="{{ $content_id }}">
  </div>
  <button type="submit" id="send_discussion" class="btn btn-primary btn-sm hide ishide">Gửi</button>
  <button type="button" class="btn btn-default btn-sm hide ishide btn_edus_back">Để sau</button>
  {!! csrf_field() !!}
</form>