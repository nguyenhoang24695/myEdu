{!! $has_secondary ? "<div class='col-md-6'>" : "" !!}
<?php $player_id = 'preview_my_video_' . $video->id; ?>
@include('includes.partials.response_video_player', ['player_id' => $player_id,
                                                    'cover_img' => $cover_image,
                                                    'autoplay' => true,
                                                    'addition' => ['url' => $video->get_sub_link()],
                                                    'stream_link' => $video_player == 'clappr' ? $video->makeUrlSetLink() : $video->get_stream_link()])
                                                    {!! $has_secondary ? "</div>" : "" !!}

{{--<link href="http://myedu.com.vn/frontend/plugin/videojs/video-js.min.css" media="all" type="text/css" rel="stylesheet" >
<link href="http://myedu.com.vn/frontend/plugin/videojs/videojs-resolution-switcher.css" media="all" type="text/css" rel="stylesheet" >
<script src="http://myedu.com.vn/frontend/plugin/videojs/video.min.js" type="text/javascript"></script>
<script src="http://myedu.com.vn/frontend/plugin/videojs/videojs.hls.js" type="text/javascript"></script>
<script src="http://myedu.com.vn/frontend/plugin/videojs/videojs-resolution-switcher.js" type="text/javascript"></script>
<script src="http://myedu.com.vn/frontend/plugin/videojs/youtube.min.js" type="text/javascript"></script>
<script src="http://myedu.com.vn/frontend/plugin/videojs/ie8/videojs-ie8.js" type="text/javascript"></script>
<script src="http://myedu.com.vn/frontend/plugin/viblast/viblast.js" type="text/javascript"></script>--}}

<script type="text/javascript">
    $(document).ready(function(){
        <?php $_multisource_players = get_multi_source_players();?>
        @foreach($_multisource_players as $player_id)
        videojs('{{$player_id}}').videoJsResolutionSwitcher();
        @endforeach
    });
</script>