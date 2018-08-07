<?php
namespace App\Repositories\Frontend\Discussion;

use App\Core\BaseRepository;
use App\Models\Discussion;

class EloquentDiscussionRepository extends BaseRepository implements DiscussionContract
{
    /**
     * EloquentCourseRepository constructor.
     */
    protected $model;

    public function __construct(Discussion $discussion)
    {
      $this->model = $discussion;
    }


    public function getListDiscussionWidthSimplePaginate($cou_id,$content_id=0){
    	$pageSize = 6;
        if($content_id > 0){
            $data = $this->model->where('active',1)
                                ->where('deleted_at',Null)
                                ->where('parent_id',0)
                                ->where('cou_id',$cou_id)
                                ->where('content_id',$content_id)
                                ->orderBy('created_at','DESC')
                                ->with('user')
                                ->simplePaginate($pageSize);
        } else {
            $data = $this->model->where('active',1)
                                ->where('deleted_at',Null)
                                ->where('parent_id',0)
                                ->where('cou_id',$cou_id)
                                ->orderBy('created_at','DESC')
                                ->with('user')
                                ->simplePaginate($pageSize);
        }
    	return $data;
    }

    public function voteUp($id){
        $data = $this->model->where(['id' => $id])->increment('vote_up', 1);
        return $data;
    }

    public function report($id){
        $data = $this->model->where(['id' => $id])->increment('report', 1);
        return $data;
    }

}
?>