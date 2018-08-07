<?php namespace App\Repositories\Frontend\Blog;

/**
 * Interface BlogRepositoryContract
 * @package App\Repositories\Backend\Blog
 */
interface BlogContract {
	public function add($data);
	public function delete($id);
}

?>