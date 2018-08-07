@extends('frontend.layouts.default')

@section('content')

	<div class="container blog-index">
		<div class="row">
			<div class="col-md-8">
				{{-- Danh sách blog nổi bật --}}
				<ul class="blog-hot list-unstyled">
				    @foreach ($blog_hot as $hot)
					    <li class="col-sm-6 col-md-6">
					    	<a href="{{ route('blog.show',['id'=>$hot->id,'title'=>str_slug($hot->blo_title,'-')]) }}">
								<img alt="{{ $hot->blo_title }}" src="{!! App\Core\MyStorage::get_image_blog_link($hot->blo_disk,$hot->blo_path,'blog_medium'); !!}" class="img-responsive" >
								<div class="text_hot"><span>{{ $hot->blo_title }}</span></div>
							</a>
					    </li>
				    @endforeach
				</ul>

				{{-- Danh sách blog mới cập nhật --}}

				<div class="blog-thumnai-new">
					@foreach ($blog as $value)
						<div class="panel unibee-box">
							<div class="panel-heading">
								<h3 class="panel-title" title="{{ $value->blo_title }}">
									{!! 
										link_to_route('blog.show', 
											$value->blo_title,
											[$value->id,str_slug($value->blo_title,'-')],
											['target'=>"_blank","title"=>$value->blo_title]) 
									!!}
								</h3>
							</div>
							<div class="panel-body">
							   <div class="blog-info-basic">
							   		<div class="info">
							   			<span class="date">
											<i class="glyphicon glyphicon-calendar"></i>
											{{ with($value->created_at)->format('d/m/Y H:i') }}
										</span>
							   		</div>
							   		<div class="info">
								   		<span class="nomal"> Viết bởi</span>
										<img class="avata" src="{{ $value->user->showAvatar() }}" alt="{{ $value->user->name }}">
										<a href="{{ $value->user->showLinkProfile() }}" class="name_bloger" title="{{ $value->user->name }}">{{ $value->user->name }}</a>
							   		</div>
							   		<div class="info">
							   			<span class="nomal">Danh mục</span>
										<a href="{{ route('blog.categories',['id'=>$value->blo_cate,'title'=>str_slug($value->category->blc_title,'-')]) }}" class="name_bloger" target="_blank" title="{{ $value->category->blc_title }}">{{ $value->category->blc_title }}</a>
							   		</div>
							   </div>
							   <div class="blog-summary">
							   		<img class="blog-cover" alt="{{ $value->blo_title }}" src="{!! App\Core\MyStorage::get_image_blog_link($value->blo_disk,$value->blo_path,'blog_large'); !!}">
							   		<div class="txt-summary">{{ $value->blo_summary }}</div>
							   </div>
							</div>
						</div>
					@endforeach
				</div>
				{!! $blog->render() !!}
			</div>
			<div class="col-md-4">
				@include('frontend.blog.includes.aside', ['data_view' => $blog_view])
			</div>
		</div>
	</div>
@endsection