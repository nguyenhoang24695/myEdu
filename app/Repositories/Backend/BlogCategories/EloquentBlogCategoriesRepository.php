<?php namespace App\Repositories\Backend\BlogCategories;

use App\Models\BlogCategories;
use App\Core\BaseRepository;


/**
 * Class EloquentBlogRepository
 * @package App\Repositories\Backend\Blog
 */

class EloquentBlogCategoriesRepository extends BaseRepository implements BlogCategoriesRepositoryContract
{
	protected $model;

	public function __construct(BlogCategories $model){
		$this->model = $model;
	}
}
?>