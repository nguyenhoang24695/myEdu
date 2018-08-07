@extends('frontend.layouts.default', ['has_videojs' => true])

@section('after-styles-end')
  {!! HTML::style('frontend/plugin/summernote/summernote.css') !!}
@endsection

@section('after-scripts-end')
  {!! HTML::script('frontend/plugin/summernote/summernote.js') !!}
  {!! HTML::script('frontend/plugin/summernote/summernote_image_resize.js') !!}
  {!! HTML::script('frontend/js/teacher/course/edit_object.js') !!}
  @include('includes.partials.use_unibee_taginput')
@endsection

@section('content')
  <div class="container mr_top_3">
    <div class="row">
      @include('frontend.includes.course_building_sidebar')
      <div class="col-sm-9 col-md-9 profile-private">
        <div class="wrap_main">

          <section>
            <div class="panel unibee-box">
              <div class="panel-heading">
                <h3 class="panel-title">Thông tin tóm tắt</h3>
              </div>
              <div class="panel-body no-padding">
              </div>
            </div>
            @include('frontend.includes.course_tiny_info')


            <div class="panel">
              <div class="panel-body ">
                <p>Giúp học sinh dễ dàng tìm thấy lớp học của bạn.</p>
                {!! Form::open(['class' => 'form', 'method' => 'post', 'id' => 'frm_edit_summary']) !!}

                <div>
                  <ul class="nav nav-tabs" role="tablist">
                    <li role="presentation" class="active">
                      <a href="#display_info" aria-controls="display_info" role="tab" data-toggle="tab">Thông tin hiển thị</a>
                    </li>
                    <li role="presentation">
                      <a href="#seo_info" aria-controls="seo_info" role="tab" data-toggle="tab">
                        Thông tin SEO
                      </a>
                    </li>
                  </ul>
                  <div class="tab-content">
                    <div role="tabpanel" class="tab-pane active" id="display_info">
                      <div class="" style="padding: 10px;">
                        <div class="form-group {{ $errors->has('cou_title') ? 'has-error' : '' }}">
                          <label class="control-label">
                            {{trans('common.name')}}
                          </label>
                          <div class="">
                            {!! Form::input('text', 'cou_title', $course->cou_title,
                              ['class' => 'form-control', 'placeholder' => trans('common.name'), 'id' => 'input_course_title']) !!}
                            {!! $errors->first('cou_title', '<label class="control-label" for="inputError">:message</label>') !!}
                          </div>
                        </div>
                        <div class="form-group {{ $errors->has('cou_summary') ? 'has-error' : '' }}">
                          <label class="control-label">
                            {{trans('common.description')}}
                          </label>
                          <div class="">
                            {!! Form::textarea('cou_summary', $course->cou_summary,
                              ['class' => 'form-control summernote_editor', 'placeholder' => trans('common.description'), 'rows' => 5]) !!}
                            {!! $errors->first('cou_summary', '<label class="control-label" for="inputError">:message</label>') !!}
                          </div>
                        </div>
                        <div class="form-group {{ $errors->has('language') ? 'has-error' : '' }}">
                          <label class="control-label">
                            {{trans('common.language')}}
                          </label>
                          <div class="">
                            {!! Form::select('language', $languages, $course->language, ['class' => 'selectpicker select-control', 'placeholder' => 'Category name']) !!}
                            {!! $errors->first('language', '<label class="control-label" for="inputError">:message</label>') !!}
                          </div>
                        </div>
                        <div class="form-group {{ $errors->has('cou_cate_id') ? 'has-error' : '' }}">
                          <label class="control-label">
                            {{trans('common.category')}}
                          </label>
                          <div class="">
                            {!! Form::select('cou_cate_id', $category_list, $course->cou_cate_id, ['class' => 'selectpicker select-control', 'placeholder' => 'Category name']) !!}
                            {!! $errors->first('cou_cate_id', '<label class="control-label" for="inputError">:message</label>') !!}
                          </div>
                        </div>
                        <div class="form-group {{ $errors->has('tags') ? 'has-error' : '' }}">
                          <label class="control-label">
                            {{trans('common.tags')}}
                          </label>
                          <div class="">
                            {!! Form::text('course_tags', $course->course_tags, ['class' => 'form-control unibee-taginput-tags', 'id' => 'input_course_tags', 'placeholder' => trans('common.tags')]) !!}
                            {!! $errors->first('course_tags', '<label class="control-label" for="inputError">:message</label>') !!}
                            <div data-watch="#input_course_title" data-taginput="#input_course_tags" data-limit="" class="taginput_suggestion_container"></div>
                          </div>
                        </div>
                      </div>

                    </div>
                    <div role="tabpanel" class="tab-pane" id="seo_info">
                      <div class="" style="padding: 10px;">
                        <div class="form-group">
                          <label class="control-label">
                            {{trans('common.sub_summary')}}
                          </label>
                          <div class="">
                            {!! Form::textarea('sub_summary', $course->sub_summary,
                                ['class' => 'form-control summernote_tiny_editor', 'placeholder' => trans('common.sub_summary'), 'rows' => 5]) !!}
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <button type="submit" class="btn btn-primary">{{trans('common.save')}}</button>
                </form>
              </div>
            </div>

          </section> <!-- /section -->

        </div>
      </div>
    </div>
  </div> <!-- /container -->
@endsection