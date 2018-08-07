<!doctype html>
<html class="no-js" lang="{{App::getLocale()}}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="content-type" content="text/html" />
    {!! SEOMeta::generate() !!}
    {!! OpenGraph::generate() !!}
    <meta property="fb:app_id" content="{!! getenv('FACEBOOK_APP_ID') !!}" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimum-scale=1.0, maximum-scale=1.0"/>
    <meta name="_token" content="{{ csrf_token() }}" />
    @yield('meta')

    @yield('before-styles-end')
    {!! HTML::style('frontend/css/'.config("app.id").'.css') !!}
    {!! HTML::style('frontend/plugin/awesome/css/font-awesome.css') !!}
    {!! HTML::style('frontend/plugin/icomoon/style.css') !!}
    {!! HTML::style('frontend/plugin/animatecss/animate.css') !!}
    {!! HTML::style('frontend/plugin/bootstrap-select/bootstrap-select.min.css') !!}
    {!! HTML::style('frontend/plugin/autocomplete/autocomplete.css') !!}
    @yield('styles-plugin')

    @yield('after-styles-end')

    <link rel="alternate" href="{{url('/')}}" hreflang="vi-vn" />
    <!-- Fonts -->
    <link href='//fonts.googleapis.com/css?family=Roboto:400,300' rel='stylesheet' type='text/css'>

    <!-- Icons-->
    <link rel="apple-touch-icon" href="apple-touch-icon.png">
    <!-- Place favicon.ico in the root directory -->
    
    <script>
      (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
      (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
      m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
      })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');
    
      ga('create', 'UA-90440914-1', 'auto');
      ga('send', 'pageview');
    
    </script>
</head>

<body class="body" id="{{ Request::is('/') ? 'home' : '' }}" style="overflow-y:scroll ">
<div id="wrapper">
    <!-- Fixed navbar -->
    @include('includes.partials.messages')
    @yield('content')

</div>
{!! HTML::script('frontend/plugin/jquery.js') !!}
{!! HTML::script('frontend/plugin/bootstrap/js/bootstrap.min.js') !!}
{!! HTML::script('frontend/plugin/bootbox.min.js') !!}
{!! HTML::script('frontend/plugin/jquery.form.min.js') !!}
{!! HTML::script('frontend/plugin/jquery.slimscroll.min.js') !!}
{!! HTML::script('frontend/plugin/bootstrap-select/bootstrap-select.js') !!}
{!! HTML::script('frontend/plugin/mobile_detect.js') !!}
{!! HTML::script('frontend/plugin/bootstrap-notify/bootstrap-notify.js') !!}
{!! HTML::script('frontend/plugin/autocomplete/autocomplete.js') !!}
{!! HTML::script('frontend/js/financial_recharge_plugin.js') !!}
{!! HTML::script('http://cdn.rawgit.com/hilios/jQuery.countdown/2.2.0/dist/jquery.countdown.min.js') !!}
@yield('scripts-plugin')
@include('includes.partials.social')
@include('includes.partials.ga')
@include('includes.partials.params')
<script>
    jQuery.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
        }
    });
</script>
@yield('before-scripts-end')
{!! HTML::script('frontend/js/libs.edus365.js') !!}
{!! HTML::script('frontend/js/common.js') !!}
@yield('after-scripts-end')
@yield('notify-scripts')
@include('frontend.' . config("app.id") . '.partials.bottom_addition')
<span class="hidden">{{microtime(true) - LARAVEL_START}}</span>
</body>
</html>
