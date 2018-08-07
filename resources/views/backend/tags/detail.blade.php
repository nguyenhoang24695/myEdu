@extends('backend.layouts.master')

@section('content')
  <h4>Các bản ghi sử dụng tag <i class=""> {{$tag->name}} </i></h4>
  <table class="table table-striped">
    @foreach($taggables as $taggable)
      {!! \App\Core\TaggableUtils::renderTaggableToView($taggable) !!}
      @endforeach
  </table>
@endsection