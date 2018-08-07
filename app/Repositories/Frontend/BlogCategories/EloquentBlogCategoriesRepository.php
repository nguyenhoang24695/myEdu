<?php namespace App\Repositories\Frontend\BlogCategories;

use App\Models\BlogCategories;
use App\Core\BaseRepository;




/**
 * Class EloquentBlogRepository
 * @package App\Repositories\Backend\Blog
 */

class EloquentBlogCategoriesRepository extends BaseRepository implements BlogCategoriesContract
{
	protected $model;

	public function __construct(BlogCategories $model){
		$this->model = $model;
	}

}
?>