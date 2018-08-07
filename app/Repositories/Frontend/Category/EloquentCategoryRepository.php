<?php namespace App\Repositories\Frontend\Category;

use App\Models\Category;
use App\Core\BaseRepository;


/**
 * Class EloquentBlogRepository
 * @package App\Repositories\Backend\Blog
 */

class EloquentCategoryRepository extends BaseRepository implements CategoryContract
{
	protected $model;

	public function __construct(Category $model){
		$this->model = $model;
	}

	public function getTree($status = 1)
    {
	     // TODO: Implement getTree() method.
	     if($status != null){
	         return $this->model->where("cat_active", "=", $status)->get()->toHierarchy();
	     }else{
	         return $this->model->all()->toHierarchy();
	     }
	}
}
?>