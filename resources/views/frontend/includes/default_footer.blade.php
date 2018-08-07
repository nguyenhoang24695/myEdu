<footer class="footer" id="footer">
  <div class="container">

    <div class="row hidden-xs">
      <ul class="list list-unstyled">
        @foreach($hot_tags as $hot_tag)
          <li><a href="#">{{$hot_tag->name}}</a></li>
          @endforeach
      </ul>
    </div>

    <div class="row logo-f">
      <div class="col-md-6 col-md-offset-3">
        <div class="row">
          <div class="col-md-5">
            <span class="line"></span>
          </div>
          <div class="col-md-2 no-padding">
            <a class="icon-all logo-x-small btn-block" href="{{ url('/') }}"></a>
          </div>
          <div class="col-md-5">
            <span class="line"></span>
          </div>
        </div>
      </div>

    </div>

    
    <div class="row info-edus">

      <div class="col-md-9 text-left">
        @if (config('common.'.config("app.id").'.footer.info.company') != "")
          <p>{!! config('common.'.config("app.id").'.footer.info.company') !!}</p>
        @endif

        @if (config('common.'.config("app.id").'.footer.info.dkkd') != "")
          <p>{!! config('common.'.config("app.id").'.footer.info.dkkd') !!}</p>
        @endif

        @if (config('common.'.config("app.id").'.footer.info.certificate') != "")
          <p>{!! config('common.'.config("app.id").'.footer.info.certificate') !!}</p>
        @endif

        @if (config('common.'.config("app.id").'.footer.info.address') != "")
          <p>{!! config('common.'.config("app.id").'.footer.info.address') !!}</p>
        @endif
      </div>

      <div class="col-md-3 guide">
        <a href="{{ route('payment.guide.module',['module' => 'payment-guide']) }}">Hướng dẫn thanh toán</a>
        <a href="{{ route('payment.guide.module',['module' => 'chinh-sach-hoan-hoc-phi']) }}">
        Chính sách hoàn học phí</a>
        <a href="{{ route('payment.guide.module',['module' => 'quy-che-hoat-dong']) }}">Quy chế hoạt động</a>
        <a href="{{ route('payment.guide.module',['module' => 'dieu-khoan-su-dung']) }}">Điều khoản sử dụng</a>
        <a href="{{ route('payment.guide.module',['module' => 'chinh-sach-bao-mat-thong-tin']) }}">Chính sách bảo mật thông tin</a>
      </div>
      
    </div>

    <div class="row info-edus info-inline info-edus-phone">
      <div class="col-md-12 ">
        <p>{!! config('common.'.config("app.id").'.footer.info.copyright') !!}</p>
        <p><i class="fa fa-phone"></i>  {!! config('common.'.config("app.id").'.contact.telephone') !!}</p>
        <p><i class="fa fa-envelope-o"></i>  {!! config('common.'.config("app.id").'.contact.email') !!}</p>
      </div>
    </div>

  </div>
</footer>