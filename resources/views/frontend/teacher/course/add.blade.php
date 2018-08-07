@extends('frontend.layouts.default')

@section('after-styles-end')
  {!! HTML::style('frontend/plugin/summernote/summernote.css') !!}
  {{--{!! HTML::style('frontend/plugin/summernote/summernote-bs3.css') !!}--}}
@endsection

@section('after-scripts-end')
  {!! HTML::script('frontend/plugin/summernote/summernote.js') !!}
  {!! HTML::script('frontend/plugin/summernote/summernote_image_resize.js') !!}
  <script>
    $(document).ready(function(){
      var $editors = $('textarea.summernote_editor');
      if($editors.length > 0)
        $editors.summernote({
          height: 200,
          toolbar: [
            ['style', ['fontsize','bold', 'italic', 'underline', 'strikethrough', 'fontname', 'clear']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['insert', ['link', 'video', 'table', 'hr']],//'resizedDataImage',
            ['color', ['color']],
            ['misc', ['fullscreen', 'codeview']]
          ]
        });
    });
  </script>
  @include('includes.partials.use_unibee_taginput')
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
                  <div class="panel unibee-box" >
                    <div class="panel-heading">
                      <h3 class="panel-title">{{trans('course.add_new')}}</h3>
                    </div>
                    <div class="panel-body no-padding">
                    </div>
                  </div>
                  
                  <div class="panel">
                      <div class="panel-body">
                        <div class="row">
                          {!! Form::open(['name' => 'frm_add_course', 'class' => '', 'method' => 'post', 'url' => route('teacher.save_new_course')]) !!}
                          <div class="col-xs-12">
                            <div class="form-group {{ $errors->has('cou_title') ? 'has-error' : '' }}">
                              <label>{{trans('course.name')}}</label>
                              {!! Form::text('cou_title', null, ['class' => 'form-control',
                                                                  'id' => 'input_course_title' ,
                                                                  'placeholder' => trans('course.name')]) !!}
                              {!! $errors->first('cou_title', '<label class="control-label" for="inputError">:message</label>') !!}
                            </div>

                            <div class="form-group {{ $errors->has('cou_summary') ? 'has-error' : '' }}">
                              <label>{{trans('course.description')}}</label>
                              {!! Form::textarea('cou_summary', null, ['class' => 'form-control summernote_editor', 'placeholder' => trans('course.description')]) !!}
                              {!! $errors->first('cou_summary', '<label class="control-label" for="inputError">:message</label>') !!}
                            </div>

                            <div class="form-group {{ $errors->has('language') ? 'has-error' : '' }}">
                              <label class="control-label">
                                {{trans('common.language')}}
                              </label>
                              <div class="">
                                {!! Form::select('language', $languages, null, ['class' => 'selectpicker select-control', 'placeholder' => 'Category name']) !!}
                                {!! $errors->first('language', '<label class="control-label" for="inputError">:message</label>') !!}
                              </div>
                            </div>

                            <div class="form-group {{ $errors->has('cou_cate_id') ? 'has-error' : '' }}">
                              <label>{{trans('course.category')}}</label>
                              {!! Form::select('cou_cate_id', $category_list, null, ['class' => 'selectpicker select-control']) !!}
                              {!! $errors->first('cou_cate_id', '<label class="control-label" for="inputError">:message</label>') !!}
                            </div>

                            <div class="form-group {{ $errors->has('course_tags') ? 'has-error' : '' }}">
                              <label>{{trans('common.tags')}}</label>
                              {!! Form::text('course_tags', null, ['class' => 'form-control unibee-taginput-tags', 'id' => 'input_course_tags']) !!}
                              {!! $errors->first('course_tags', '<label class="control-label" for="inputError">:message</label>') !!}
                              <div data-watch="#input_course_title" data-taginput="#input_course_tags" data-limit="" class="taginput_suggestion_container"></div>
                            </div>

                            <div class="form-group">
                              <button type="submit" class="btn btn-success">Tạo mới</button>
                            </div>

                          </div>
                          {!! Form::close() !!}
                        </div><!-- row -->
                      </div>
                  </div>
                </section>
            </div>
        </div>
    </div>
</div>
@endsection