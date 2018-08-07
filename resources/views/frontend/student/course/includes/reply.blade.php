<div class="collapse" id="frm_reply" style="display:none;">
    <div class="media media_reply">
      <div class="media-left">
        <a class="avata" href="{{ Auth::user()->showLinkProfile() }}" title="{{ Auth::user()->name }}">
          @if(Auth::user()->avatar_path != "")
              <img src="{{ Auth::user()->showAvatar('ua_small') }}" alt="{{ Auth::user()->name }}" class="media-object img-circle img-responsive">
          @else
              <img src="{{ Auth::user()->showDefaultAvatar() }}" alt="{{ Auth::user()->name }}" class="media-object img-circle img-responsive">
          @endif
        </a>
      </div>
      <div class="media-body">
        <form class="discussions frm_reply" method="POST" action="{{ route('frontend.discussion.reply') }}">
          <div class="form-group">
            <textarea name="content" rows="2" class="form-control" placeholder="Nội dung thảo luận" style="margin-bottom: 5px"></textarea>
            <span class="label label-danger err_reply"></span>
            <input type="hidden" name="cou_id" value="{{ $course_id }}" >
            <input type="hidden" name="content_id" class="content_id"  value="{{ $content_id }}">
            <input type="hidden" name="parent_id" class="parent_id">
          </div>
          <button type="submit" class="btn btn-primary btn-sm btn_edus_primary">Trả lời</button>
          <button type="button" class="btn btn-default btn-sm btn_edus_back cancel_reply" onclick="cancel_reply(this)">hủy</button>
          {!! csrf_field() !!}
        </form>
      </div>
    </div>
</div>