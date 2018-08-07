<?php
/**
 * Created by PhpStorm.
 * User: MyPC
 * Date: 27/04/2016
 * Time: 1:42 CH
 */

namespace App\Core\PromoCode;
use App\Models\TrackingLink;
use Illuminate\Cookie\CookieJar;


use app\Core\BaseRepository;

class InnerTrackingLink extends BaseRepository
{
    protected $cookie;
    protected $cookie_name;
    protected $time_set;
    protected $time_curent;

    public function __construct(CookieJar $cookieJar){
        $this->cookie       =   $cookieJar;
        $this->cookie_name  =   "UUID-COURSE";
        $this->time_set     =   30; //30 ngày
        $this->time_curent  =   time(); // Thời gian hiện tại
    }


    /**
     * Lưu key là ID khóa học trong trường hợp
     * 1 khóa học nhiều partner chia sẻ, sẽ tính cho partner đầu tiên chia sẻ
     * @param $course
     * @param string $uuid
     *
     * @return mixed
     */
    public function processUUID($course, $uuid = ""){
        $data_cookie  =   $this->getCookieUUID();

        //Lấy dữ liệu từ local xử lý trước
        $arr_key_off = []; // key chứ mã code hết hạn
        if(isset($data_cookie[$course->id]) && !empty($data_cookie[$course->id])){

            //Lấy thông tin code để tính % chiết khấu

            //## kiểm tra lần lượt thời gian hết hạn của mã code thứ tự 0->
            //## mã code chỉ tồn tại trong 30 ngày
            //## hết hạn thì lấy mã code tiếp theo

            $getCode     = "";
            $discount    = "";

            foreach($data_cookie[$course->id] as $key => $value){
                if(isset($value['time_expired'])){
                    $date_diff     = $this->time_curent - $value['time_expired'];

                    //Vượt quá 30 ngày thì loại bỏ
                    if(floor($date_diff/86400) <= $this->time_set){
                        $getCode  = $value['id'];
                        break;
                    } else {
                        $arr_key_off[] = $key;
                    }
                }
            }

            if($getCode     != ""){
                $discount    = TrackingLink::findUUID($getCode);

                //Nếu uuid đầu tiên vs cái đang xem của cùng 1 partner thì lấy cái hiện tại
                //Và update lại giá trị cookie
                if($discount && $uuid != ""){
                    $discount_curent   = TrackingLink::findUUID($uuid);
                    if($discount_curent){
                        if($discount_curent->user_id == $discount->user_id && $getCode != $uuid){
                            $data_cookie[$course->id][0]    =  ['id' => $uuid, 'time_expired' => time()];
                            $this->setCookieUUID($data_cookie);
                            return $discount_curent;
                        }
                    }
                }
            }

        } else {
            $discount   = TrackingLink::findUUID($uuid);
        }

        //Đoạn này sẽ kiểm tra và lưu cookie khi vào link chia sẻ
        // ## Nếu chua có thì lưu cookie
        // ## có rùi thì append vào dánh sách và lưu lại

        if($uuid != ""){

            if(!is_array($data_cookie)) {
                $data_cookie[$course->id][] = ['id' => $uuid, 'time_expired' => time()-20*86200];
            } else {
                if(isset($data_cookie[$course->id]) && !empty($data_cookie[$course->id])){
                    $arr_is_val = [];
                    foreach($data_cookie[$course->id] as $isval){
                        if(isset($isval['id'])){
                            $arr_is_val[] = $isval['id'];
                        }
                    }
                    if(!in_array($uuid,$arr_is_val)){
                        array_push($data_cookie[$course->id], ['id' => $uuid, 'time_expired' => time()-5*86200]);
                    }
                } else {
                    $data_cookie[$course->id][] = ['id' => $uuid, 'time_expired' => time()];
                }
            }
            $this->setCookieUUID($data_cookie);
        }

        //Loại bỏ những link đã hết hạn sử dụng
        if(!empty($arr_key_off)){
            foreach($arr_key_off as $key_off){
                if(isset($data_cookie[$course->id][$key_off])){
                    unset($data_cookie[$course->id][$key_off]);
                }
            }
            $reset_cookie[$course->id] = array_values($data_cookie[$course->id]);
            $this->setCookieUUID($reset_cookie);
        }

        if($discount){
            return $discount;
        }

    }

    private function setCookieUUID($data){
        $this->cookie->queue(cookie($this->cookie_name,$data,$this->time_set));
    }

    private function getCookieUUID(){
        return \Cookie::get($this->cookie_name);
    }
}