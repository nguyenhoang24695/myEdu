<?php namespace App\Repositories\Backend\Blog;

use App\Models\Blog;
use App\Core\BaseRepository;

/**
 * Class EloquentBlogRepository
 * @package App\Repositories\Backend\Blog
 */

class EloquentBlogRepository extends BaseRepository implements BlogRepositoryContract
{
	protected $model;

	public function __construct(Blog $model){
		$this->model = $model;
	}
}
?>