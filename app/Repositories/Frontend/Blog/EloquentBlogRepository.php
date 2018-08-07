<?php namespace App\Repositories\Frontend\Blog;

use App\Models\Blog;
use App\Core\BaseRepository;
use App\Core\MyStorage;
use App\Exceptions\GeneralException;


/**
 * Class EloquentBlogRepository
 * @package App\Repositories\Backend\Blog
 */

class EloquentBlogRepository extends BaseRepository implements BlogContract
{
	protected $model;

	public function __construct(Blog $model){
		$this->model = $model;
	}

   /**
   * Lấy ra danh sách blog theo cate
   **/
   public function getBlogByCategoryId($condition = [],$orderby=['id'=>'DESC'],$id_in=[]){
      $pageSize     = 20;
      $data_cate  = $this->model;

      foreach ($condition as $key => $value)
      {
        if ($value == ''){
          unset($condition[$key]);
        }
        // Nếu là aray quy định
        if (is_array($value)) {
            $op     = array_get($value, 'operator');
            $val    = array_get($value, 'value');
            $column = $key;
            $data_cate = $data_cate->where($column, $op, $val); 
        } else {
          $data_cate = $data_cate->where($key,$value);
        }

      }

      $data_cate = $data_cate->whereIn('blo_cate', $id_in);

      foreach ($orderby as $key => $value) {
        $data_cate = $data_cate->orderby($key,$value);
      }

      $data_cate->with("user");

      return $data_cate->paginate($pageSize);
   }

   //Lấy tổng số danh sách bài viết theo user
   public function getTotalBlog($id){
    return $this->model
                ->where('blo_user_id', $id)
                ->where('blo_active', 1)
                ->count();
   }

    public function add($data)
    {
        return $this->model->create($data);
    }

    public function delete($id)
    {
        return $this->model->whereId($id)->delete();
    }
}
?>