<!-- Đánh giá khóa học -->
<div class="modal fade unibee-model" id="myModal_rating" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <div class="close triangle-topright" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </div>
        <h4 class="modal-title" id="myModalLabel">Đánh giá khóa học</h4>
      </div>
      <div class="modal-body">
        <form action="{{ route('reviews.store') }}" id="reviews" method="POST" class="has-success-unibee">
          <div class="form-group" style="margin-bottom: 0">
            <label class="title" >Bình chọn</label>
              <fieldset class="unibee-rating">
                <section class="starRating">
                  <input class="star star-5" id="star-5" type="radio" name="rating" dvalue="Rất thích" value="5" />
                  <label class="star star-5" for="star-5" dtitle="Rất hài lòng"></label>
                  <input class="star star-4" id="star-4" type="radio" name="rating" dvalue="Thích" value="4" />
                  <label class="star star-4" for="star-4" dtitle="Hài lòng"></label>
                  <input class="star star-3" id="star-3" type="radio" name="rating" dvalue="Bình thường" value="3" />
                  <label class="star star-3" for="star-3" dtitle="Bình thường"></label>
                  <input class="star star-2" id="star-2" type="radio" name="rating" dvalue="Không thích" value="2" />
                  <label class="star star-2" for="star-2" dtitle="Không hài lòng"></label>
                  <input class="star star-1" id="star-1" type="radio" name="rating" dvalue="Ghét" value="1" />
                  <label class="star star-1" for="star-1" dtitle="Rất tồi"></label>
                </section>
                <span class="note_rating"></span>
              </fieldset>
              <label class="label label-danger rating_error hide"></label>
          </div>
          <div class="form-group">
            <label class="title" >Đánh giá về lớp học</label>
            <p class="help-block"><i class="fa fa-circle"></i>Nội dung bài giảng có phong phú, phù hợp và đúng quy chuẩn đào tạo?</p>
            <p class="help-block"><i class="fa fa-circle"></i>Chất lượng hình ảnh, video.</p>
            <p class="help-block"><i class="fa fa-circle"></i>Phong cách giảng dạy của thầy giáo thu hút và thú vị.</p>
          </div>
          <div class="form-group">
            <textarea class="form-control" rows="3" name="rev_content">{{ Request::old('rev_content') }}</textarea>
            <input type="hidden" value="{{ $course->id }}" name="rev_cou_id">
            <label class="label label-danger rev_content_error hide"></label>
          </div>
          {!! csrf_field() !!}
          <button type="submit" class="btn btn-primary btn_action_reviews">Gửi đánh giá</button>
        </form>
      </div>
    </div>
  </div>
</div>

<div class="modal fade unibee-model" id="myModal_notify" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <div class="close triangle-topright" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </div>
        <h4 class="modal-title" id="myModalLabel">Thông báo</h4>
      </div>
      <div class="modal-body">
        <div class="alert alert-success" role="alert">
          <strong>Đánh giá khóa học thành công!</strong> Đánh giá của bạn sẽ được BQT duyệt trước khi được hiển thị
        </div>
      </div>
    </div>
  </div>
</div>