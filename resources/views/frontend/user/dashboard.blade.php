@extends('frontend.layouts.default')

@section('after-styles-end')
    {!! HTML::style('frontend/plugin/summernote/summernote.css') !!}
@endsection

@section('after-scripts-end')
    {!! HTML::script('frontend/js/profile.js') !!}
    {!! HTML::script('frontend/plugin/summernote/summernote.js') !!}
    {!! HTML::script('frontend/plugin/summernote/summernote_image_resize.js') !!}
    {!! HTML::script('frontend/js/teacher/course/edit_object.js') !!}
@endsection

@section('content')
	<div class="container">
        <div class="row">
            <div class="col-md-3 aside unibee-aside">
                @include('frontend.user.includes.aside')
            </div>
            <div class="col-md-9 profile-private">
                <div class="wrap_main">
                    @if(isset($module) && $module != "" && in_array($module, $arr_module))
                        @include('frontend.user.includes.'.$module)
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
