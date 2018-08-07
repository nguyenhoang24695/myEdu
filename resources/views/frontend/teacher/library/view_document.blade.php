@extends('frontend.teacher.library.layout')

@section('after-scripts-end')
  {!! HTML::script('frontend/js/library.js') !!}
@stop

@section('sub_content')
  <div class="panel wiget_box">
    <div class="panel-heading">
      <h3 class="panel-title">{{$document->doc_title}}</h3>
    </div>
    <div class="panel-body ">
      <div class="col-md-12">
        <iframe width="100%" height="600" src="{{onlineDocumentView($download_link)}}" class="embed-responsive-item"></iframe>
        <a href="{{$download_link}}">Tải xuống</a>
      </div>
      <div class="col-md-12">
        <ul>
          <li>
            Tên : {{$document->doc_title}}
          </li>
          <li>
            Mô tả : {{$document->doc_description}}
          </li>
          <li>
            Ngày tạo : {{$document->created_at}}
          </li>
          <li>
            Người tạo : {{$document->user()->first()->name}}
          </li>
        </ul>
      </div>
    </div>
  </div>
@endsection