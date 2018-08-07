<?php

namespace App\Repositories\Backend\Reviews;


use App\Core\BaseRepository;
use App\Models\Reviews;
use DB;

class EloquentReviewsRepository extends BaseRepository implements ReviewsContract
{
	/**
     * EloquentCourseRepository constructor.
     */
	protected $model;
	
    public function __construct(Reviews $reviews)
    {
        $this->model = $reviews;
    }

    public function getListReviews()
    {

        $data = $this->getAllWithPaginate();

        return $data;
    }
}
?>