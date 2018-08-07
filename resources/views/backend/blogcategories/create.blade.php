@extends ('backend.layouts.master')

@section ('title', 'Quản lý danh mục Blog')

@section('page-header')
    <h1>
        Quản lý danh mục Blog
    </h1>
@endsection

@section ('breadcrumbs')
    <li><a href="{!!route('backend.dashboard')!!}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
    <li class="active">Quản lý danh mục Blog</li>
@stop

@section('content')
	<div class="row">
		<div class="col-md-12">
			<div class="box">
				<div class="box-header with-border">
					<h3 class="box-title">Thêm mới.</h3>
				</div>
				<form class="form-horizontal" method="POST" action="{{url('/admin/blogcate')}}" enctype="multipart/form-data">
           <div class="box-body">
              
              <div class="form-group {{ $errors->has('parent') ? 'has-error' : '' }}">
                <label class="col-sm-2 control-label">Danh mục cha</label>
                <div class="col-sm-10">
                  <select class="form-control" name="parent">
                    <option value="0">Chọn danh mục cha</option>
                    @foreach ($list as $key => $value)
                      <option value="{{ $value->id }}">{{ $value->blc_title }}</option>
                    @endforeach
                  </select>
                  {!! $errors->first('parent', '<label class="control-label" for="inputError">:message</label>') !!}
                </div>
              </div>

             <div class="form-group {{ $errors->has('title') ? 'has-error' : '' }}">
               <label class="col-sm-2 control-label">Tên danh mục</label>
               <div class="col-sm-10">
                 <input name="title" type="text" class="form-control" placeholder="Tên danh mục">
                 {!! $errors->first('title', '<label class="control-label" for="inputError">:message</label>') !!}
               </div>
             </div>

           </div><!-- /.box-body -->
           <div class="box-footer">
             <button type="reset" class="btn btn-default">Hủy</button>
             <button type="submit" class="btn btn-info pull-right">Thêm mới</button>
           </div><!-- /.box-footer -->
           {!! csrf_field() !!}
         </form>
			</div>
		</div>
	</div>
@stop