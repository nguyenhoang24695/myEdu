@extends('frontend.layouts.default', ['has_videojs' => true])

@section('after-scripts-end')
  {!! HTML::script('frontend/js/library.js') !!}
@stop

@section('after-styles-end')
  {!! HTML::style('frontend/css/profile.css') !!}
@endsection

@section('content')
  <div class="container mr_top_3">
    <div class="row">
      <div class="col-sm-3 col-md-3 aside unibee-aside">
        @include('frontend.user.includes.aside')
      </div>
      <div class="col-sm-9 col-md-9 profile-private">
        <div class="wrap_main">
          <section>
            @include('includes.partials.library_header')
            <div class="line-pattern"></div>
          </section>
          @yield('sub_content')
        </div>
      </div>
    </div>
  </div>

@endsection