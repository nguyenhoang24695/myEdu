<!doctype html>
<html class="no-js" lang="">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="_token" content="{{ csrf_token() }}" />
        <title>@yield('title', app_name())</title>
        <meta name="description" content="@yield('meta_description', 'Default Description')">
        <meta name="author" content="@yield('author', 'Anthony Rappa')">
        @yield('meta')

        @yield('before-styles-end')
        {!! HTML::style('backend/plugin/bootstrap/css/bootstrap.css') !!}
        {!! HTML::style('backend/plugin/awesome/css/font-awesome.css') !!}
        {!! HTML::style('backend/css/backend.css') !!}
        {!! HTML::style('backend/plugin/bootstrap-modal/css/bootstrap-modal-bs3patch.css') !!}
        {!! HTML::style('backend/plugin/bootstrap-modal/css/bootstrap-modal.css') !!}
        {!! HTML::style('backend/plugin/switchery/switchery.min.css') !!}
        @yield('after-styles-end')

        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
            <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
            <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
        <![endif]-->
    </head>
    <body class="skin-blue">
        <div class="wrapper">
          @include('backend.includes.header')
          @include('backend.includes.sidebar')

          <!-- Content Wrapper. Contains page content -->
          <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <section class="content-header">
              @yield('page-header')
              <ol class="breadcrumb">
                @yield('breadcrumbs')
              </ol>
            </section>

            <!-- Main content -->
            <section class="content">
              @include('backend.includes.message')
              @yield('content')
            </section><!-- /.content -->
          </div><!-- /.content-wrapper -->

          @include('backend.includes.footer')
        </div><!-- ./wrapper -->

        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
        <script>window.jQuery || document.write('<script src="{{asset('js/vendor/jquery-1.11.2.min.js')}}"><\/script>')</script>
        @include('includes.partials.params')
        <script>
            jQuery.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                }
            });
        </script>
        {!! HTML::script('backend/plugin/bootstrap/js/bootstrap.min.js') !!}
        {!! HTML::script('backend/js/bootbox.min.js') !!}
        {!! HTML::script('backend/plugin/bootstrap-modal/js/bootstrap-modal.js') !!}
        {!! HTML::script('backend/plugin/bootstrap-modal/js/bootstrap-modalmanager.js') !!}
        {!! HTML::script('backend/plugin/switchery/switchery.js') !!}

        @yield('before-scripts-end')
        {!! HTML::script('backend/js/backend.js') !!}
        @yield('after-scripts-end')
        {!! HTML::script('backend/js/active.js') !!}
        {!! HTML::script('backend/js/common.js') !!}
        <span class="hidden">{{microtime(true) - LARAVEL_START}}</span>
    </body>
</html>
