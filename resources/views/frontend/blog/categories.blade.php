@extends('frontend.layouts.default')

@section('content')

	<div class="container blog-index">
		<div class="row">
			<div class="col-md-8">
				<div class="blog-thumnai-new">
					@foreach ($blog_cate_all as $value)
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
				{!! $blog_cate_all->render() !!}
			</div>
			<div class="col-md-4">
				@include('frontend.blog.includes.aside', ['data_view' => $blog_view])
			</div>
		</div>
	</div>
@endsection