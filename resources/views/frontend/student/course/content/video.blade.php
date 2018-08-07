{!! $has_secondary ? "<div class='col-md-6'>" : "" !!}
<?php $player_id = 'preview_my_video_' . $video->id; //add_multi_source_player($player_id); ?>
@include('includes.partials.response_video_player', ['player_id' => $player_id,
                                                      'cover_img' => $cover_image,
                                                      'autoplay' => false,
                                                      'addition' => ['url' => $video->get_sub_link()],
                                                      'stream_link' => $video_player == 'clappr' ? $video->makeUrlSetLink() : $video->get_stream_link()])
{!! $has_secondary ? "</div>" : "" !!}

