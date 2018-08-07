<?php 

namespace App\Repositories\Frontend\Reviews;


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

    /**
     * Lấy danh sách reviews
     * @param : $cou_id;
     */

    public function getListReviews($cou_id){
        $param     = ['rev_active' => 1,'rev_cou_id' => $cou_id];
        $orderby   = ['id' => 'DESC'];
        $page_size = 20;
        return $this->getAllWithPaginate($param,$page_size,$orderby,'user');
    }

    /**
     * Lấy tổng số Rating
     * Sum(rating)
     * @param : $cou_id;
     */

    public function getSumRating($cou_id){
        $sum = $this->model->where('rev_active', 1)->where('rev_cou_id',$cou_id)->sum('rating');
        return $sum;
    }

    /**
    * Lấy số lần đánh giá
    * count(rating)
    * @param : $cou_id;
    * @return : $count;
    **/

    public function getCountRating($cou_id){
        $count = $this->model->where('rev_active', 1)->where('rev_cou_id',$cou_id)->count();
        return $count;
    }

    /**
    * Tính bình quân lượt đánh giá (2.0,3.0...)
    * avg (rating)
    * @param: $sum,$count
    * @return: $avg_rate;
    **/

    public function getAvgRating($cou_id,$count_rate = 0){
        if($count_rate == 0){
            $count_rate = 1;
        }
        $sum_rate      = $this->getSumRating($cou_id);
        $avg_rate      = round($sum_rate / $count_rate);
        return $avg_rate;
    }

    /**
     * Lấy tổng số lượt đánh giá từ 1->5 sao
     * groupby(rating)
     * @param : $cou_id;
     */

    public function getGroupbyRating($cou_id){
        $data = $this->model
                     ->select(DB::raw('count(rating) as total, rating'))
                     ->where('rev_active', 1)
                     ->where('rev_cou_id',$cou_id)
                     ->groupBy('rating')
                     ->having('rating', '>', 0)
                     ->get();
        return $data;
    }

    /**
    * Lấy tỷ lệ % theo số lượt đánh giá
    * avg (rating)
    * @param: $cou_id;
    * @return: $arr_all
    **/

    public function getPercentFollowRating($cou_id,$count_rating=0){
        $arr_value_rating   = [];
        if($count_rating    == 0){
            $count_rating   = $this->getCountRating($cou_id);
        }

        $groupby            = $this->getGroupbyRating($cou_id);
        foreach ($groupby as $key => $value) {
            $per_rating     = round(($value->total/$count_rating)*100); 
            $arr_value_rating[$value->rating] = ['totalRating'=>$value->total,'perRating'=>$per_rating];
        }

        //Tạo mảng sắp xếp giảm dần từ 5->1
        //Thêm giá trị mặc định 0 cho những rating ko có giá trị
        for ($i=5; $i > 0 ; $i--) {
            if(isset($arr_value_rating[$i])){
                $arr_value_rating[$i] = $arr_value_rating[$i];
            } else {
                $arr_value_rating[$i] = ['totalRating'=>0,'perRating'=>0];
            }
        }

        return $arr_value_rating;
    }

    public function getHtmlRating($avg){
        return $this->model->genRating($avg);
    }
}
?>