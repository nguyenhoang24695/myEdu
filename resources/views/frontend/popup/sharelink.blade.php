<div class="modal fade unibee-model" id="myModal_sharelink" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <div class="close triangle-topright" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </div>
        <h4 class="modal-title" id="myModalLabel">Chia sẻ link tăng doanh thu</h4>
      </div>
      <div class="modal-body pop-up-share">
        <p>Chia sẻ cơ hội học tập tới cộng đồng</p>
        <p>Chúng tôi sẽ đem lại thu nhập thụ động cho bạn hàng ngày</p>
        <ul class="guid-step">
            <li class="text-guid">
              <i class="fa fa-link"></i>
              <p>Tạo link</p>
            </li>
            <li class="step">
              <i class="fa fa-angle-right"></i>
            </li>
            <li class="text-guid">
              <i class="fa fa-share-alt"></i>
              <p>Chia sẻ</p>
            </li>
            <li class="step">
              <i class="fa fa-angle-right"></i>
            </li>
            <li class="text-guid">
              <i class="fa fa-money"></i>
              <p>Nhận tiền</p>
            </li>
        </ul>
        <form method="POST" action="{{ route('frontend.link.create') }}" id="create_link_share">

          <div class="discount discount-link">
            <table class="table">
              <thead>
                <tr>
                  <th>Nội dung</th>
                  @if ($code_info->user_id == $course->cou_user_id)
                    <th>Khóa học của bạn</th>
                  @else
                    <th>Khóa học của người khác</th>
                  @endif
                  <th></th>
                </tr>
              </thead>
              <tbody>
                <tr class="row-first">
                  <td>Bạn được nhận</td>
                  <td>
                  @if ($code_info->user_id == $course->cou_user_id)
                    <input 
                    type="text"
                    class="form-control enjoy_2"
                    d-val="{{ $discount_owner }}" 
                    value="{{ $discount_owner }}%"
                    disabled>
                    <input type="hidden" name="dmax" value="{{ $discount_owner }}">
                  @else
                    <input 
                    type="text"
                    class="form-control enjoy_1"
                    d-val="{{ $code_info->discount_max }}" 
                    value="{{ $code_info->discount_max }}%"
                    disabled>
                    <input type="hidden" name="dmax" value="{{ $code_info->discount_max }}">
                  @endif
                  </td>
                  <td><p>Cách tăng % <a href="{{ route('partner.info') }}" target="_blank">tại đây</a></p></td>
                </tr>
                <tr>
                  <td>Chiết khấu cho bạn bè</td>
                  <td>
                    <div class="input-group" style="max-width: 200px">
                      @if ($code_info->user_id == $course->cou_user_id)
                        <input 
                        type="number" 
                        name="discount"
                        class="form-control num_discount_friend"
                        max="{{ $discount_owner }}"
                        d-type=2
                        placeholder="Nhập số" 
                        >
                      @else
                        <input 
                        type="number" 
                        name="discount"
                        class="form-control num_discount_friend"
                        d-type=1
                        max="{{ $code_info->discount_max }}"
                        placeholder="Nhập số" 
                        >
                      @endif
                      <span class="input-group-addon">%</span>
                    </div>
                  </td>
                  <td></td>
                </tr>
                <tr>
                  <td>Còn lại của bạn</td>
                  <td>
                    @if ($code_info->user_id == $course->cou_user_id)
                      <input 
                      type="text" 
                      value="{{ $discount_owner }}%" 
                      class="form-control after_discount_2" disabled>
                    @else
                      <input 
                      type="text" 
                      value="{{ $code_info->discount_max }}%" 
                      class="form-control after_discount_1" disabled>
                    @endif
                    <input type="hidden" name="course_id"  value="{{ $course->id }}" >
                    <input type="hidden" name="link"  value="{{ $course->get_public_view_link() }}" >
                    <input type="hidden" name="cou_title" value="{{ $course->cou_title }}" >
                    <input type="hidden" name="cou_summary" value="{{ $course->cou_summary }}" >
                    @if (Auth::check())
                      <input type="hidden" name="user_id" value="{{ Auth::user()->id }}">
                    @endif
                  </td>
                  <td></td>
                </tr>
              </tbody>
            </table>
          </div>

          {!! csrf_field() !!}
          <button type="submit" class="btn btn-primary btn_action_reviews">Tạo link chia sẻ</button>
        </form>
      </div>
    </div>
  </div>
</div>



<div class="modal fade unibee-model" id="myModal_sociallink" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <div class="close triangle-topright" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </div>
        <h4 class="modal-title">Chia sẻ link tăng doanh thu</h4>
      </div>
      <div class="modal-body">
        <p>Chia sẻ cơ hội học tập tới cộng đồng</p>
        <p>Chúng tôi sẽ đem lại thu nhập thụ động cho bạn hàng ngày</p>
        <ul class="guid-step">
            <li class="text-guid">
              <i class="fa fa-link"></i>
              <p>Tạo link</p>
            </li>
            <li class="step">
              <i class="fa fa-angle-right"></i>
            </li>
            <li class="text-guid">
              <i class="fa fa-share-alt"></i>
              <p>Chia sẻ</p>
            </li>
            <li class="step">
              <i class="fa fa-angle-right"></i>
            </li>
            <li class="text-guid">
              <i class="fa fa-money"></i>
              <p>Nhận tiền</p>
            </li>
        </ul>

        <div class="copy-link"> 
          <div class="input-group">

            <input 
            type="text"
            class="form-control link_share"
            id="link_public" >

            <span 
            class="input-group-addon addon-copy to-copy click_to_copy "
            data-clipboard-target="#link_public">
                <i class="fa fa-clipboard"></i> Copy link
            </span>

          </div>
          <h5>Hoặc chia sẻ</h5>
          <div class="social_link_all"></div>
        </div>

      </div>
    </div>
  </div>
</div>