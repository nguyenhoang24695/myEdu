<?php
namespace App\Core\PromoCode;

use App\Core\Money\Utils\InnerTransactionManager;
use App\Models\Course;
use App\Models\Order;
use App\Models\Partner;
use App\Models\TrackingLink;
use App\Models\User;
use Rhumsaa\Uuid\Uuid;

class PromoCodeManager
{
    protected $partner;
    protected $innerPromoCode;
    protected $course;

    public function __construct()
    {
        $this->partner                   =  new Partner();
        $this->innerPromoCode            =  new InnerPromoCode();
    }

    /**
     * @description : Xử lý mã code giảm giá
     * @param : $code
     * @param : $course Model
	**/
    public function processPromoCode($code,$course)
    {
        if($course instanceof Course){
            $this->course     =   $course;
        } else {
            throw new \Exception('Kiểm tra lại object course');
        }

        //Kiểm tra xem mã code đầu vào thuộc loại nào
        //Mã code giới thiệu link
        if(Uuid::isValid($code)){
            return $this->processCodeTrackingLink($code);
        } else {
            return $this->processCodePartner($code);
        }
    }

    public function processCodePartner($code){
        //Kiểm tra chủ sở hữu MÃ CODE có phải là PARTNER hay không.
        $code_info = $this->innerPromoCode->getByCode($code);
        if(!$code_info)
        {
            $res['success'] = false;
            $res['message'] = "Mã code không tồn tại";
            return $res;
        }

        if($this->partner->check($code_info->user_id))
        {
            //Nếu Đk là Partner

            //1. Bán chính khóa học Partner sẽ nhận tối đa 70%
            //Lúc cài đặt chiết khấu 2 phải check tối đa = DISCOUNT_OF_SELLER + DMAX
            if($this->course->cou_user_id == $code_info->user_id){

                //Bán khóa học chính partner nhận luôn level cao nhất
                if($code_info->discount_2 > $code_info->discount_max){
                    $enjoy_partner = 0;
                    $enjoy_seller  = (ConfigCode::DISCOUNT_OF_SELLER + ConfigCode::DISCOUNT_DIAMOND) - $code_info->discount_2;
                } else {
                    $enjoy_partner = ConfigCode::DISCOUNT_DIAMOND - $code_info->discount_2;
                    $enjoy_seller  = ConfigCode::DISCOUNT_OF_SELLER;
                }

                return $process_code = [
                    'success'   => true,
                    'partner'   => [
                        'enjoy'    => $enjoy_partner,
                        'id'       => $code_info->user_id
                    ],
                    //Người mua khóa học
                    'buyer'   => [
                        'enjoy'    => $code_info->discount_2
                    ],
                    //người bán (giáo viên)
                    'seller'   => [
                        'enjoy'    => $enjoy_seller
                    ]
                ];

            } else {
                return $process_code = [
                    'success'   => true,
                    'partner'   => [
                        'enjoy'    => $code_info->discount_max-$code_info->discount_1,
                        'id'       => $code_info->user_id
                    ],
                    //Người mua khóa học
                    'buyer'   => [
                        'enjoy'    => $code_info->discount_1
                    ],
                    //người bán (giáo viên)
                    'seller'   => [
                        'enjoy'    => ConfigCode::DISCOUNT_OF_SELLER
                    ]
                ];
            }
        } else {
            //Nếu là member
            return $process_code = [
                'success'   => true,
                'partner'   => [
                    'enjoy'    => 0,
                    'id'       => $code_info->user_id
                ],
                //Người mua khóa học
                'buyer'   => [
                    'enjoy'    => $code_info->discount_max
                ],
                //người bán (giáo viên)
                'seller'   => [
                    'enjoy'    => ConfigCode::DISCOUNT_OF_SELLER
                ]
            ];
        }
    }

    public function processCodeTrackingLink($code){
        $code_info_link     = TrackingLink::findUUID($code);
        if(!$code_info_link)
        {
            $res['success'] = false;
            $res['message'] = "Mã code không tồn tại";
            return $res;
        }

        if($code_info_link->discount < 0){
            $res['success'] = false;
            $res['message'] = "% chiết khấu < 0";
            return $res;
        }

        //Lấy % chiết khấu của partner ở thời điểm hiện tại
        $codePartner    =   $this->innerPromoCode->getCodeByUser($code_info_link->user_id);

        //Giới thiệu khóa học của chính mình
        if($this->course->cou_user_id == $code_info_link->user_id){

            $enjoy_partner = 0;
            $enjoy_seller  = (ConfigCode::DISCOUNT_OF_SELLER + ConfigCode::DISCOUNT_DIAMOND) - $code_info_link->discount;

            return $process_code = [
                'success'   => true,
                'partner'   => [
                    'enjoy'    => $enjoy_partner,
                    'id'       => $code_info_link->user_id
                ],
                //Người mua khóa học
                'buyer'   => [
                    'enjoy'    => $code_info_link->discount
                ],
                //người bán (giáo viên)
                'seller'   => [
                    'enjoy'    => $enjoy_seller
                ]
            ];

        } else {

            return $process_code = [
                'success'   => true,
                //Người giới thiệu khóa học
                'partner'   => [
                    'enjoy'    => $codePartner->discount_max - $code_info_link->discount,
                    'id'       => $code_info_link->user_id
                ],
                //Người mua khóa học
                'buyer'   => [
                    'enjoy'    => $code_info_link->discount
                ],
                //người bán (giáo viên)
                'seller'   => [
                    'enjoy'    => ConfigCode::DISCOUNT_OF_SELLER
                ]
            ];
        }

    }

    //Tăng level và tăng tiền tích lũy, tăng số người sử dụng cho thành viên
    public function increasedLevelPartner($code,$cou_price)
    {
        $code_info = $this->innerPromoCode->getByCode($code);
        if($code_info){

            $code_info->increment('total_money',$cou_price);
            $code_info->increment('used_count');

            if($code_info->total_money  >= ConfigCode::LEVEL_DIAMOND){
                $code_info->discount_max =  ConfigCode::DISCOUNT_DIAMOND;
                $code_info->partner_level=  2;
            } else {
                $code_info->discount_max =  ConfigCode::DISCOUNT_GOLD;
                $code_info->partner_level=  1;
            }

            return $code_info->save();
        }
    }

    //Tạo code cho người mua nếu chưa có code
    public function createCodeForBuyer($buyer_id){
        return $this->innerPromoCode->findOrcreate($buyer_id);
    }
}
?>