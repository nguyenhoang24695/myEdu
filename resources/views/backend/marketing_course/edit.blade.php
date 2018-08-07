@extends ('backend.layouts.master')

@section ('title', 'Quản lý khóa học chạy marketing')

@section('after-scripts-end')
  {{--{!! HTML::script('backend/js/searchindex.js') !!}--}}
@endsection

@section('page-header')
  <h1>
    Danh sách khóa học chạy marketing
  </h1>
@endsection

@section ('breadcrumbs')
  <li><a href="{!!route('backend.dashboard')!!}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
  <li><a href="{{route('backend.marketing_course.index')}}">Marketing courses</a></li>
  <li class="active">Edit</li>
@stop

@section('content')
  {!! Form::open() !!}
  <button class="btn btn-xs btn-primary">Save all</button>
  @foreach($m_courses as $m_course)
  <div class="box">
    <div class="box-header with-border">
      <h3 class="box-title">{{$m_course->title}}</h3>
    </div>
    <div class="box-body">
      <div class="row">
        <div class="col-md-5 col-sm-4 col-lg-3">
          <img src="{{$m_course->course->get_cached_image('cc_small')}}" class="img-responsive"/>
          <div>
            {!! Form::file('image[' . $m_course->id . ']') !!}
          </div>
        </div>
        <div class="col-md-7 col-sm-8 col-lg-9">
          <div class="form-group">
            <label class="">Tiêu đề </label>
            {!! Form::text('title[' . $m_course->id . ']', $m_course->title, ['class' => 'form-control']) !!}
          </div>
          <div class="form-group">
            <label class="">Từ khóa chính xác(phân cách dấu ,) </label>
            {!! Form::textarea('exact_keyword[' . $m_course->id . ']', $m_course->exact_keyword, ['class' => 'form-control', 'rows' => 3]) !!}

          </div>
        </div>
      </div>

      <div class="form-group">
        <label class="">Mô tả </label>
        {!! Form::textarea('description[' . $m_course->id . ']', $m_course->description, ['class' => 'form-control', 'rows' => 3]) !!}

      </div>
      <div class="form-group">
        <label class="">Từ khóa liên quan </label>
        {!! Form::textarea('similar_keyword[' . $m_course->id . ']', $m_course->similar_keyword, ['class' => 'form-control', 'rows' => 3]) !!}
      </div>
    </div>
  </div>
  @endforeach
  <button class="btn btn-xs btn-primary">Save all</button>
  {!! Form::close() !!}
@endsection