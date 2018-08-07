@extends('api.ads.layout')

@section('title')
  Quảng cáo khóa học
  @endsection

@section('after_style_end')
  {!! HTML::style(url('adsv1/font_icon/styles.css')) !!}
  {!! HTML::style(url('adsv1/template1.css')) !!}
  @endsection
@section('after_script_end')
  {!! HTML::script(url('adsv1/template1.js')) !!}
  @endsection

@section('content')
  <div class="ca_container">
    @foreach($m_courses as $m_course)
    <div class="box box-wide">
      <div class="box-image">
        <a target="_blank" href="{{$m_course->course->get_public_view_link(['partner' => '123doc.org'])}}">
          <img src="{{$m_course->course->get_cached_image('cc_small')}}" />
        </a>
      </div>
      <div class="box-info">
        <div class="box-header">
          <h3 class='box-heading'>
            <a target="_blank" href="{{$m_course->course->get_public_view_link(['partner' => '123doc.org'])}}">
              {{$m_course->title}}
            </a>
          </h3>
          <div class="box-price">
            {{human_money($m_course->course->cou_price)}}
          </div>
        </div>
        <div class="box-rating">
          {!! genRating1($m_course->course->rating, false) !!}

          <div class="user-count"> {{$m_course->course->user_count}} Học viên </div>
        </div>
        <div class="box-brief">
          {{$m_course->description}}
        </div>
      </div>
    </div>
      @endforeach
  </div>
  @endsection