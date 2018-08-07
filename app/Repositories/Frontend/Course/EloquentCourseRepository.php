<?php
/**
 * Created by PhpStorm.
 * User: hocvt
 * Date: 9/9/15
 * Time: 17:30
 */

namespace App\Repositories\Frontend\Course;


use App\Core\BaseRepository;
use App\Models\Course;
use Eloquence\Database\Model;

class EloquentCourseRepository extends BaseRepository implements CourseContract
{
    /**
     * EloquentCourseRepository constructor.
     */
    protected $model;
    public function __construct(Course $course)
    {
      $this->model = $course;
    }

    public function getCourseByCategoryId($condition = [],$orderby=['courses.id'=>'DESC'],$id_in=[],$limit=20){
      $pageSize     = $limit;
      $data_course  = $this->model->with('user');

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
            $data_course = $data_course->where($column, $op, $val); 
        } else {
          $data_course = $data_course->where($key,$value);
        }

      }

      $data_course = $data_course->whereIn('cou_cate_id', $id_in);

      foreach ($orderby as $key => $value) {
        $data_course = $data_course->orderby($key,$value);
      }

      return $data_course->paginate($pageSize);
      
    }

    public function getTotalCourse($id){
      return $this->model
                ->where('cou_user_id', $id)
                ->where('cou_active', 1)
                ->count();
    }

    public function incrementView($id)
    {
        return $this->model->where('id', $id)->increment('cou_views');
    }

    public function getHotCourseCategoryId($orderby=['courses.rating'=>'ASC'],$id_in=[],$limit=7){
        $data_course  = $this->model->with('user');

        $data_course = $data_course->whereIn('cou_cate_id', $id_in);
        $data_course = $data_course->where('rating', '>', 0);
        $data_course = $data_course->take($limit);

        foreach ($orderby as $key => $value) {
            $data_course = $data_course->orderby($key,$value);
        }

        return $data_course->get();

    }
}