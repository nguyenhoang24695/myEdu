@if(\Access::hasRole(config('access.role_list.teacher')))
<section>
  <h3 class="title">{{trans('common.about_me.teacher_title')}}</h3>
  <div class="use-info use-info-lag use-info-primary">
    <a class="u-avatar pull-left" href="{{ $teacher->showLinkProfile() }}" title="{{ $teacher->name }}">
      <img src="{{ $teacher->showAvatar() }}" alt="{{ $teacher->name }}" class="img-circle img-responsive">
    </a>
    <p class="u-name">
      <a href="{{ $teacher->showLinkProfile() }}" title="{{ $teacher->name }}">{{$teacher->name}}</a>
      
        @if ($teacher->unit_name != "")
          <span class="nn">{{ $teacher->position. ' - ' .$teacher->unit_name  }}</span>
        @else
          <span class="nn">Đang cập nhật</span>
        @endif
      
    </p>
    <p class="social social-medium mr-t">
      <a title="twitter" href="{{ ($teacher->social_twitter != "") ? $teacher->social_twitter : "javascript:void(0);" }}" class="twitter-sign"><i class="fa fa-twitter-square"></i></a>
      <a title="google" href="{{ ($teacher->social_google != "") ? $teacher->social_google : "javascript:void(0);" }}" class="google-plus-sign"><i class="fa fa-google-plus-square"></i></a>
      <a title="facebook" href="{{ ($teacher->social_facebook != "") ? $teacher->social_facebook : "javascript:void(0);" }}" class="facebook-sign"><i class="fa fa-facebook-square"></i></a>
      <a title="linkedin" href="{{ ($teacher->social_linkedin != "") ? $teacher->social_linkedin : "javascript:void(0);" }}" class="linkedin"><i class="fa fa-linkedin-square"></i></a>
    </p>
  </div>
  <div class="note">
    <p>{!! $teacher->status_text !!}</p>
  </div>
</section>
@else
<section>
  <h3 class="title">{{trans('common.about_me.teacher_title')}}</h3>
  <div class="use-info use-info-lag use-info-primary">
    <a class="u-avatar pull-left" href="{{ $teacher->showLinkProfile() }}">
      <img src="{{$teacher->showAvatar('ua_small')}}" alt="{{$teacher->name}}" class="img-circle img-responsive">
    </a>
    <p class="u-name">
      <a href="{{ $teacher->showLinkProfile() }}" title="{{$teacher->name}}">{{$teacher->name}}</a>
      
      @if ($teacher->unit_name != "")
        <span class="nn">{{ $teacher->position. ' - ' .$teacher->unit_name  }}</span>
      @else
        <span class="nn">Đang cập nhật</span>
      @endif
      
    </p>
    <p class="social social-medium mr-t">
      <a title="twitter" href="{{ ($teacher->social_twitter != "") ? $teacher->social_twitter : "javascript:void(0);" }}" class="twitter-sign"><i class="fa fa-twitter-square"></i></a>
      <a title="google" href="{{ ($teacher->social_google != "") ? $teacher->social_google : "javascript:void(0);" }}" class="google-plus-sign"><i class="fa fa-google-plus-square"></i></a>
      <a title="facebook" href="{{ ($teacher->social_facebook != "") ? $teacher->social_facebook : "javascript:void(0);" }}" class="facebook-sign"><i class="fa fa-facebook-square"></i></a>
      <a title="linkedin" href="{{ ($teacher->social_linkedin != "") ? $teacher->social_linkedin : "javascript:void(0);" }}" class="linkedin"><i class="fa fa-linkedin-square"></i></a>
    </p>
  </div>
  <div class="note">
    <p>{!! $teacher->status_text !!}</p>
  </div>
</section>
@endif
