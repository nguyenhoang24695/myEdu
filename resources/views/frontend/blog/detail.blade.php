@extends('frontend.layouts.default')

@section('content')

<div class="container blog-index">
	<div class="row">
		<div class="col-md-8 blog-thumnai-new">
			<div class="panel unibee-box">
				<div class="panel-heading">
					<h1 class="panel-title" title="{{ $blog->blo_title }}">
						{!! 
							link_to_route('blog.show', 
								$blog->blo_title,
								[$blog->id,str_slug($blog->blo_title,'-')],
								['target'=>"_blank","title"=>$blog->blo_title]) 
						!!}
					</h1>
				</div>
				<div class="panel-body">
					<div class="blog-info-basic">
				   		<div class="info">
				   			<span class="date">
								<i class="glyphicon glyphicon-calendar"></i>
								{{ with($blog->created_at)->format('d/m/Y H:i') }}
							</span>
				   		</div>
				   		<div class="info">
					   		<span class="nomal"> Viết bởi</span>
							<img class="avata" src="{{ $blog->user->showAvatar() }}" alt="{{ $blog->user->name }}">
							<a href="{{ $blog->user->showLinkProfile() }}" class="name_bloger" title="{{ $blog->user->name }}">{{ $blog->user->name }}</a>
				   		</div>
				   		<div class="info">
				   			<span class="nomal">Danh mục</span>
							<a href="{{ route('blog.categories',['id'=>$blog->blo_cate,'title'=>str_slug($blog->category->blc_title,'-')]) }}" class="name_bloger" target="_blank" title="{{ $blog->category->blc_title }}">{{ $blog->category->blc_title }}</a>
				   		</div>
				   </div>
				   <div class="summary">
						<p><strong>{{ $blog->blo_summary }}</strong></p>
					</div>
					<div class="content">
						{!! $blog->blo_content !!}
					</div>
				</div>
			</div>

			<div class="line-pattern"></div>
			<div class="comment-blog title-more">
				<div class="title_blog_more">
					<p class="title_more">Bình luận bài viết</p>
					<div class="line_small"></div>
					<div class="line_long"></div>
				</div>
				<div class="comment_fb">
					<div class="fb-comments" data-href="{{ route('blog.show', ['id' => $blog->id, 'title' => str_slug($blog->blo_title)]) }}" data-width="100%" data-numposts="5"></div>
				</div>
			</div>

			<div class="line-pattern"></div>
			<div class="blog-new title-more">
				<div class="title_blog_more">
					<p class="title_more">Blog mới cập nhật</p>
					<div class="line_small"></div>
					<div class="line_long"></div>
				</div>
				<div class="panel unibee-box unibee-box-blog blog-new">
					<div class="panel-body">
						@foreach ($blog_new as $data)
							<?php
								$pic_cover = App\Core\MyStorage::get_image_blog_link($data->blo_disk,$data->blo_path,"blog_small");
							?>
						   <div class="media media-list-blog col-xs-12 col-sm-6 col-md-6">
							  <div class="media-left media-middle">
							    <a href="{{ route('blog.show',['id'=>$data->id,'title'=>str_slug($data->blo_title,'-')]) }}" title="{{ $data->blo_title }}">
							      <img class="media-object img-responsive" src="{{ $pic_cover }}" alt="{{ $data->blo_title }}">
							    </a>
							  </div>
							  <div class="media-body">
							    <h4 class="media-heading" title="{{ $data->blo_title }}">
							    	<a title="{{ $data->blo_title }}" href="{{ route('blog.show',['id'=>$data->id,'title'=>str_slug($data->blo_title,'-')]) }}">{{ $data->blo_title }}</a>
							    </h4>
							    <div class="date">
									<span class="glyphicon glyphicon-calendar"></span>
									<span class="blog_date">{{ with($data->created_at)->format('d/m/Y H:i') }}</span>
								</div>
							  </div>
							</div>
						@endforeach
					</div>
				</div>
			</div>
		</div>

		<div class="col-md-4">
			@include('frontend.blog.includes.aside', ['data_view' => $blog_view])
		</div>

	</div>
</div>
@endsection


@section('after-scripts-end')
	<div id="fb-root"></div>
	<script>(function(d, s, id) {
	  var js, fjs = d.getElementsByTagName(s)[0];
	  if (d.getElementById(id)) return;
	  js = d.createElement(s); js.id = id;
	  js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.4&appId=468692039883445";
	  fjs.parentNode.insertBefore(js, fjs);
	}(document, 'script', 'facebook-jssdk'));</script>
@endsection