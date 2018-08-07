<!doctype html>
<html class="no-js" lang="">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="_token" content="{{ csrf_token() }}" />
  <title>@yield('title', app_name())</title>
  <meta name="description" content="@yield('meta_description', 'Nền tảng giáo dục trực tuyến')">
  <meta name="author" content="@yield('author', 'Colombo Group')">
  @yield('meta')
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
  <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
  <![endif]-->
  @yield('after_style_end')
</head>
<body class="" id="ca_body">
  @yield('content')
  @yield('before_script_start')
  @if(config('analytics.ca-google-analytics'))
    @include('includes.partials.ca_ga', ['ga_code' => config('analytics.ca-google-analytics')])
    @endif
  @yield('after_script_end')
</body>
</html>
