@foreach($external_sources as $source)
  @if($source->source_type == 'youtube')
      @if(config('app.video_player') == 'videojs')
        <div class="embed-responsive embed-responsive-16by9">
          <video
            controls
            contextmenu="false"
            id="video_yt_{{$source->id}}" class="video-js vjs-default-skin embed-responsive-item"
            autoplay
            data-setup='{ "techOrder": ["youtube"], "sources": [{ "type": "video/youtube", "src": "{{$source->content}}"}], "Youtube": { "ytControls": 2 } }'
            >
            {{--<source type="video/youtube" src="{{$source->content}}"/>--}}
            <p class="vjs-no-js">To view this video please enable JavaScript, and consider upgrading to a web browser that
              <a href="http://videojs.com/html5-video-support/" target="_blank">supports HTML5 video</a></p>
          </video>
          <?php add_multi_source_player('video_yt_' . $source->id); ?>
        </div>
      @elseif(config('app.video_player') == 'mediaelement')
        <video
          controls
          id="video_yt_{{$source->id}}" class=""
          autoplay
          preload="none">
          <source type="video/youtube" src="{{$source->content}}" />
          <p class="vjs-no-js">To view this video please enable JavaScript, and consider upgrading to a web browser that
            <a href="http://videojs.com/html5-video-support/" target="_blank">supports HTML5 video</a></p>
        </video>
      @endif
    <?php break; ?>
  @endif
@endforeach