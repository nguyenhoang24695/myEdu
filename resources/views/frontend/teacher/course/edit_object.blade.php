@extends('frontend.layouts.default', ['has_videojs' => true])

@section('after-styles-end')
  <style>
    li.an_audience,li.an_requirement,li.an_goal{
      margin-bottom: 5px;
    }
  </style>
@endsection

@section('after-scripts-end')
  {!! HTML::script('frontend/js/teacher/course/edit_object.js') !!}
@endsection

@section('content')
  <div class="container mr_top_3">
    <div class="row">
      @include('frontend.includes.course_building_sidebar')
      <div class="col-sm-9 col-md-9 profile-private main main_lecture">
        <div class="wrap_main">

          <section>
            <div class="panel unibee-box">
              <div class="panel-heading">
                <h3 class="panel-title">Yêu cầu và mục tiêu khóa học</h3>
              </div>
              <div class="panel-body no-padding">
              </div>
            </div>
            @include('frontend.includes.course_tiny_info')
            <div class="panel reset-panel">
              <div class="panel-body ">
                {!! Form::open(['class' => 'obj_course', 'method' => 'post', 'id' => 'frm_edit_object']) !!}

                @if(config('feature_toggle.show_course_audience'))
                <div class="form-group">
                  <label for="">Ai nên học khóa học này</label>

                  <p class="help-block">Để nâng cao chất lượng đào tạo bạn hãy cho biết đối tượng nào phù hợp với khóa
                    học này.</p>
                  <ul class="list-unstyled">
                    @foreach($audience as $an_audience)
                      <li class="an_audience">
                        <div class="input-group">
                          <span class="input-group-addon"><i class="fa fa-circle"></i></span>
                          <input type="text" name="cou_audience[]" value="{{$an_audience}}" class="form-control"
                                 readonly="readonly">
											      <span class="input-group-addon">
											      	<i class="fa fa-trash fa-3x"></i>
											      </span>
                        </div>
                        <!-- /input-group -->
                      </li>
                    @endforeach
                    <li id="add_an_audience">
                      <div class="input-group">
                          <span class="input-group-addon">
                            <i class="fa fa-circle"></i>
                          </span>
                        <input type="text" name="cou_audience[]" class="form-control add_cou_audience"/>
                          <span class="input-group-btn">
                            <button class="btn" type="button">{{trans('common.add')}}</button>
                          </span>
                      </div>
                      <!-- /input-group -->
                    </li>
                    <li class="an_audience an_audience_tmp" style="display: none">
                      <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-circle"></i></span>
                        <input type="text" name="cou_audience[]" value="" class="form-control" disabled="disabled"
                               readonly="readonly">
                          <span class="input-group-addon">
                            <i class="fa fa-trash fa-3x"></i>
                          </span>
                      </div>
                      <!-- /input-group -->
                    </li>

                  </ul>
                </div>
                @endif

                <div class="form-group">
                  <label for="">Yêu cầu trước khi học khóa học</label>
                  <p class="help-block">Những yêu cầu cần thiết để học sinh xác định khóa học có phù hợp với kiến thức nền tảng hiện tại của mình không.</p>
                  <ul class="list-unstyled">
                    @foreach($requirements as $requirement)
                      <li class="an_requirement">
                        <div class="input-group">
                          <span class="input-group-addon"><i class="fa fa-circle"></i></span>
                          <input type="text" name="cou_requirement[]" value="{{$requirement}}" class="form-control"
                                 readonly="readonly">
											      <span class="input-group-addon">
											      	<i class="fa fa-trash fa-3x"></i>
											      </span>
                        </div>
                        <!-- /input-group -->
                      </li>
                    @endforeach
                    <li id="add_an_requirement">
                      <div class="input-group">
                          <span class="input-group-addon">
                            <i class="fa fa-circle"></i>
                          </span>
                        <input type="text" name="cou_requirement[]" class="form-control add_cou_requirement"/>
                          <span class="input-group-btn">
                            <button class="btn" type="button">{{trans('common.add')}}</button>
                          </span>
                      </div>
                      <!-- /input-group -->
                    </li>
                    <li class="an_requirement an_requirement_tmp" style="display: none">
                      <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-circle"></i></span>
                        <input type="text" name="cou_requirement[]" value="" class="form-control" disabled="disabled"
                               readonly="readonly">
                          <span class="input-group-addon">
                            <i class="fa fa-trash fa-3x"></i>
                          </span>
                      </div>
                      <!-- /input-group -->
                    </li>

                  </ul>
                </div>

                <div class="form-group">
                  <label for="">Mục tiêu khóa học</label>
                  <p class="help-block">Giúp người học biết được những gì mình đạt được sau khi hoàn thành khóa học</p>
                  <ul class="list-unstyled">
                    @foreach($goals as $goal)
                      <li class="an_goal">
                        <div class="input-group">
                          <span class="input-group-addon"><i class="fa fa-circle"></i></span>
                          <input type="text" name="cou_goal[]" value="{{$goal}}" class="form-control"
                                 readonly="readonly">
											      <span class="input-group-addon">
											      	<i class="fa fa-trash fa-3x"></i>
											      </span>
                        </div>
                        <!-- /input-group -->
                      </li>
                    @endforeach
                    <li id="add_an_goal">
                      <div class="input-group">
                          <span class="input-group-addon">
                            <i class="fa fa-circle"></i>
                          </span>
                        <input type="text" name="cou_goal[]" class="form-control add_cou_goal"/>
                          <span class="input-group-btn">
                            <button class="btn" type="button">{{trans('common.add')}}</button>
                          </span>
                      </div>
                      <!-- /input-group -->
                    </li>
                    <li class="an_goal an_goal_tmp" style="display: none">
                      <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-circle"></i></span>
                        <input type="text" name="cou_goal[]" value="" class="form-control" disabled="disabled"
                               readonly="readonly">
                          <span class="input-group-addon">
                            <i class="fa fa-trash fa-3x"></i>
                          </span>
                      </div>
                      <!-- /input-group -->
                    </li>

                  </ul>
                </div>

                @if(config('feature_toggle.show_course_skill_level'))
                <div class="form-group">
                  <label for="">Học lực phù hợp</label>

                  <p class="help-block">Để nâng cao chất lượng đào tạo bạn hãy cho biết đối tượng nào phù hợp với khóa
                    học này.</p>

                  <div class="input-group">
                    @foreach(config('course.learning_capacity') as $k => $v)
                      <label class="radio-inline">
                        <input {{$course->cou_skill_level == $v ? "checked" : ""}} type="radio" name="cou_skill_level"
                               value="{{$v}}"/>
                        {{trans('course.learning_capacity.'.$k)}}
                      </label>
                    @endforeach
                  </div>
                </div>
                @endif

                <button type="submit" class="btn btn-primary">{{trans('common.save')}}</button>
                </form>
              </div>
            </div>

          </section>
          <!-- /section -->

        </div>
      </div>
    </div>
  </div> <!-- /container -->
@endsection