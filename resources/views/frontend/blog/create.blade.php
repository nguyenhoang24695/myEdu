@extends('frontend.layouts.default')

@section('after-styles-end')
   {!! HTML::style('frontend/plugin/summernote/summernote.css') !!}
@endsection

@section('content')

 <div class="container">
    <div class="row">
        <div class="col-sm-3 col-md-3 aside unibee-aside">
            @include('frontend.user.includes.aside')
        </div>
        <div class="col-sm-9 col-md-9 profile-private">
            <div class="wrap_main">
                <section>
                  <div class="panel unibee-box">
                    <div class="panel-heading">
                      <h3 class="panel-title">Tạo mới Blog</h3>
                    </div>
                    <div class="panel-body ">
                    </div>
                  </div>
                  <div class="line-pattern"></div>
                  <div class="panel create_blog">
                      <div class="panel-body ">
                        <form  enctype="multipart/form-data" method="POST" action="{{ route('blog.store') }}" >
                          <div class="form-group">
                            <label >Chọn danh mục</label>
                            <select class="selectpicker select-control" name="blo_cate">
                              <option value="">Chọn danh mục</option>
                              @foreach ($blog_cate as $key => $value)
                                <option value="{{ $value->id }}">{{ $value->blc_title }}</option>
                              @endforeach
                            </select>

                            {!! $errors->first('blo_cate', '<label class="control-label control-label-error" for="inputError">:message</label>') !!}
                          </div>
        								  <div class="form-group">
        								    <label >Tiêu đề blog</label>
        								    <input type="text" class="form-control" name="blo_title" placeholder="Tiêu đề blog" value="{{ Request::old('blo_title') }}">

        								    {!! $errors->first('blo_title', '<label class="control-label control-label-error" for="inputError">:message</label>') !!}

        								  </div>
        								  <div class="form-group">
        								    <label >Mô tả ngắn</label>
                            <textarea name="blo_summary" class="form-control" rows="3" placeholder="Mô tả ngắn">{{ Request::old('blo_summary') }}</textarea>

        								    {!! $errors->first('blo_summary', '<label class="control-label control-label-error" for="inputError">:message</label>') !!}
                            
        								  </div>
        								  <div class="form-group">
        								    <label >Ảnh đại diện</label>
        								    <input type="file" name="blo_path" class="form-control">
        								    {!! $errors->first('blo_path', '<label class="control-label control-label-error" for="inputError">:message</label>') !!}
        								  </div>
        								  <div class="form-group">
        								    <label >Nội dung</label>
        								    <textarea name="blo_content" class="form-control summernote">{{ Request::old('blo_content') }}</textarea>
        								  </div>
                          <div class="form-group">
                            <label >Chuyển hướng</label>
                            <div class="radio">
                              <label style="margin-right: 10px">
                                <input type="radio" name="redirect" value="0" checked>
                                Tiếp tục viết blog
                              </label>
                              <label style="margin-right: 10px">
                                <input type="radio" name="redirect" value="1" >
                                Về danh sách
                              </label>
                              <label>
                                <input type="radio" name="redirect" value="2" >
                                Xem chi tiết
                              </label>
                            </div>
                          </div>
                          <div class="form-group">
                            <label >Hoàn thành và đăng</label>
                            <div class="radio">
                              <label style="margin-right: 10px" data-toggle="tooltip" data-placement="top" title="BQT sẽ duyệt bài của bạn và đưa lên trang chủ">
                                <input type="radio" name="public" value="1" >
                                Công khai
                              </label>
                              <label style="margin-right: 10px" data-toggle="tooltip" data-placement="top" title="Blog của bạn sẽ chỉ xuất hiện ở trang cá nhân của bạn">
                                <input type="radio" name="public" value="2" >
                                Ẩn danh
                              </label>
                              <label data-toggle="tooltip" data-placement="top" title="Sẽ không ai biết đến Blog của bạn">
                                <input type="radio" name="public" value="0" checked>
                                Riêng tư
                              </label>
                            </div>
                          </div>
        								  <button type="submit" class="btn btn-success">Đăng bài</button>
        								  {!! csrf_field() !!}
        								</form>
                      </div>
                  </div>
                </section>
            </div>
        </div>
    </div>
</div>
@endsection

@section('after-scripts-end')
	{!! HTML::script('frontend/plugin/summernote/summernote.js') !!}
	{!! HTML::script('frontend/js/blog.js') !!}
@endsection