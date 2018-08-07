<!doctype html>
<html class="no-js" lang="{{App::getLocale()}}">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta http-equiv="content-type" content="text/html" />
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
  <meta name="_token" content="{{ csrf_token() }}" />
  {!! SEOMeta::generate() !!}
  {!! OpenGraph::generate() !!}
  <meta property="fb:app_id" content="{!! getenv('FACEBOOK_APP_ID') !!}" />
  @yield('meta')

  @yield('before-styles-end')
  {!! HTML::style('frontend/css/'.config("app.id").'.css') !!}
  {!! HTML::style('frontend/plugin/awesome/css/font-awesome.css') !!}
  {!! HTML::style('frontend/plugin/icomoon/style.css') !!}
  {!! HTML::style('frontend/plugin/animatecss/animate.css') !!}
  {!! HTML::style('frontend/plugin/bootstrap-select/bootstrap-select.min.css') !!}
  {!! HTML::style('frontend/plugin/switchery/switchery.min.css') !!}
  @yield('styles-plugin')
  
  @yield('after-styles-end')

    <!-- Fonts -->
  <link href='//fonts.googleapis.com/css?family=Roboto:400,300' rel='stylesheet' type='text/css'>

  <!-- Icons-->
  <link rel="apple-touch-icon" href="apple-touch-icon.png">
  <!-- Place favicon.ico in the root directory -->
</head>

<body>
@yield('content')
{{--<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>--}}
{!! HTML::script('frontend/plugin/jquery.js') !!}
{!! HTML::script('frontend/plugin/bootstrap/js/bootstrap.min.js') !!}
{!! HTML::script('frontend/plugin/jquery.form.min.js') !!}
{!! HTML::script('frontend/plugin/jquery.slimscroll.min.js') !!}
{!! HTML::script('backend/plugin/switchery/switchery.js') !!}
@include('includes.partials.ga')
@include('includes.partials.params')
@include('includes.partials.messages')
<script>
  jQuery.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
    }
  });
</script>
{!! HTML::script('frontend/plugin/bootbox.min.js') !!}
{!! HTML::script('frontend/plugin/bootstrap-notify/bootstrap-notify.js') !!}


@if($video_player == 'videojs')
  {!! HTML::style('frontend/plugin/videojs/video-js.min.css') !!}
  {!! HTML::style('frontend/plugin/videojs/videojs-resolution-switcher.css') !!}
  {!! HTML::script('frontend/plugin/videojs/video.min.js') !!}
  {!! HTML::script('frontend/plugin/videojs/videojs.hls.js') !!}
  {!! HTML::script('frontend/plugin/videojs/videojs-resolution-switcher.js') !!}
  {!! HTML::script('frontend/plugin/videojs/youtube.min.js') !!}
  {!! HTML::script('frontend/plugin/videojs/ie8/videojs-ie8.js') !!}
  @if(config('app.enable_viblast', false))
    {!! HTML::script('frontend/plugin/viblast/viblast.js') !!}
  @endif
  <script>
    $(document).ready(function(){
      <?php $_multisource_players = get_multi_source_players();?>
      @foreach($_multisource_players as $player_id)
      videojs('{{$player_id}}').videoJsResolutionSwitcher();
      @endforeach
    });
  </script>
@elseif($video_player == 'mediaelement')
  {!! HTML::style('frontend/plugin/mediaelement/mediaelementplayer.css') !!}
  {!! HTML::script('frontend/plugin/mediaelement/mediaelement-and-player.min.js') !!}
  <script type="text/javascript">
    $('video,audio').mediaelementplayer({
    defaultVideoWidth: '100%',
    defaultVideoHeight: '100%'
    });
  </script>
@elseif($video_player == 'clappr')
  <script type="text/javascript">
    window.clappr = window.clappr || {};
    window.clappr.externals = [];
  </script>
  {!! HTML::script('frontend/plugin/clappr/clappr.js') !!}
  {!! HTML::script('frontend/plugin/clappr/level-selector/level-selector.js') !!}
  <script type="text/javascript">

    $(document).ready(function(){
        <?php $_multisource_players = get_multi_source_players();?>
        @foreach($_multisource_players as $player)


        var playerElement_{{array_get($player,'id')}} = document.getElementById('{{array_get($player,'id')}}');
      var player_{{array_get($player,'id')}} = new Clappr.Player({
        sources:
        <?php
        //\Log::alert($player);
        $stream = array_get($player,'stream');
        $_source = [];
        if(is_string($stream)){
          $_source[] = [
          'source' => $stream,
          'mimeType' => 'video/mp4'
          ];
        }elseif(isset($stream['url'])){
          $_source[] = [
                'source' => $stream['url'],
                'mimeType' => 'video/mp4'
                ];
        }else{

          foreach($stream as $_stream){
            $_source[] = [
                'source' => $_stream['src'],
                'mimeType' => $_stream['type']
                ];
          }
        }
        echo json_encode($_source);
        ?>
      ,
      
        poster: '{{array_get($player,'cover')}}',
        height: '100%',
        width: '100%',
        plugins: {
          'core': [LevelSelector]
        },
        levelSelectorConfig: {
          title: 'Quality',
          labels: {
            2: 'FHD', // 500kbps
            1: 'HD', // 240kbps
            0: 'SD', // 120kbps
          },
          selectedLevelId: 0
        }
      });

      player_{{array_get($player,'id')}}.attachTo(playerElement_{{array_get($player,'id')}});
      {{--videojs('{{$player_id}}').videoJsResolutionSwitcher();--}}

      @endforeach
    });
  </script>
@endif

@yield('before-scripts-end')
{!! HTML::script('frontend/js/libs.edus365.js') !!}
@yield('after-scripts-end')
@yield('notify-scripts')
</body>
</html>
