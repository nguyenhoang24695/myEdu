@extends('frontend.layouts.default')

@section('after-scripts-end')
{!! HTML::script('frontend/js/library.js') !!}
@stop

@section('content')
<div class="container mr_top_3">
  <div class="row">
    <div class="col-md-3 aside unibee-aside">
      @include('frontend.user.includes.aside')
    </div>
    <div class="col-md-9 profile-private">
      <div class="wrap_main">
        <section>
          @include('includes.partials.library_header')
          <div class="panel ">
            <div class="panel-body">
              @if($medias)
              <div class="" style="padding-top: 10px;">
                <div class="">
                  @foreach($medias as $media)
                    <div class="media " style="border-bottom: 1px solid #ddd;">
                      <div class="media-left">
                        <a href="{{route('teacher.my_library.' . $media_type, ['id' => $media->get_media_id()])}}" target="_blank">
                          <img class="media-object" src="{{$media->thumbnail_link()}}" alt="{{$media->getTitle()}}">
                        </a>
                      </div>
                      <div class="media-body">
                        <h4 class="media-heading">
                          <a href="{{route('teacher.my_library.' . $media_type, ['id' => $media->get_media_id()])}}" target="_blank">
                            {{$media->getTitle()}}
                          </a>
                        </h4>

                        <p class="num_count_use">
                          {{$media->getSubTitle()}}
                        </p>

                        <p class="report_stt">
                          @if($media->duration)
                          <span><i class="fa fa-clock-o"></i> {{human_timesize($media->duration)}}</span>
                          @endif
                          <span><i class="fa fa-calendar"></i> {{$media->created_at}}</span>
                          @if($media->get_media_type() == 'video' && !empty($media->base_sub_path))
                            <span class="text-primary fa fa-copyright"></span>
                          @endif
                        </p>

                        <p>

                          <a class="ajax_link_delete btn btn-xs btn-warning"
                             href="{{route('teacher.my_library.delete_media', ['type' => $media_type, 'id' => $media->id])}}">
                            {{trans('common.delete')}}
                          </a>

                          <a class=" btn btn-xs btn-default" href="{{route('teacher.my_library.edit_' . $media_type, ['id' => $media->id])}}">
                            {{trans('common.edit')}}
                          </a>

                        </p>
                      </div>
                    </div>
                  @endforeach
                </div>
                <div>
                  {!! $medias->render() !!}
                </div>
              </div>
              @endif
            </div>
          </div>
        </section>
      </div>
    </div>
  </div>
</div>

@endsection