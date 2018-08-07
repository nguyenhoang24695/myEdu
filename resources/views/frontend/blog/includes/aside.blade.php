<div class="panel unibee-box unibee-box-blog">
	<div class="panel-heading">
		<h3 class="panel-title">Blog xem nhiều</h3>
	</div>
	<div class="panel-body">
		@foreach ($data_view as $data)
			<?php
				$pic_cover = App\Core\MyStorage::get_image_blog_link($data->blo_disk,$data->blo_path,"blog_small");
			?>
		   <div class="media media-list-blog media-list-view">
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