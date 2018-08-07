<?php
/**
 * Lớp này support các thao tác cho một order, hóa đơn của các giao dịch như nạp thẻ, chuyển khoản, ...
 * User: hocvt
 * Date: 1/9/16
 * Time: 11:11
 */

namespace App\Core\Money\Utils;


use App\Core\Money\BaoKimApi\Payment;
use App\Core\Money\Contracts\SellableContract;
use App\Core\Money\Exceptions\ConfigErrorException;
use App\Core\Money\Exceptions\UnSupportPaymentGateException;
use App\Core\PromoCode\PromoCodeManager;
use App\Events\Frontend\OrderNotification;
use App\Exceptions\GeneralException;
use App\Models\BankPayment;
use App\Models\Course;
use App\Models\MobileCard;
use App\Models\Order;
use App\Models\User;
use stringEncode\Exception;

class OrderManager
{
    private $inner_transaction_manager;
    protected $promo_code;

    /**
     * OrderManager constructor.
     */
    public function __construct()
    {
        $this->inner_transaction_manager = new InnerTransactionManager();
        $this->promo_code                = new PromoCodeManager();
    }


    public function processOrder(Order $order, array $options = [])
    {
        if($order->status != Constant::PENDING_ORDER){
            throw new GeneralException("Hóa đơn đã được xử lý, không xử lý lại");
        }
        $item_class = $order->item_type;
        /** @var SellableContract $item */
        $item = $item_class::find($order->item_id);
        $payment_gate = $this->getPaymentGate($order->payment_method);
        if($item instanceof MobileCard){
            $return = $this->processCardOrder($order, $item);
        }elseif($item instanceof Course){
            $return = $this->processBuyCourseOrder($order, $item);
        }elseif($item instanceof BankPayment){
            $return = $this->processBankPayment($order, $item, $options);
        }else{
            /** @todo huy order neu khong duoc ho tro */
            throw new \Exception("Chưa hỗ trợ xử lý loại hóa đơn này");
        }

        /** @todo Gửi thông báo Notification */
        /*if(isset($return['success']) && $return['success'] == true){
            event(new OrderNotification($order));
        }*/

        return $return;
    }

    public function cancelOrder(Order $order, array $options = []){
        if($order->status != Constant::PENDING_ORDER){
            throw new GeneralException("Chỉ có thể hủy đơn hàng đang chờ xử lý");
        }
        /** @var SellableContract $item */
        $item = $order->getItemObject();
        $return = [
            'success' => false,
            'message' => 'Error!',
        ];
        if($item instanceof MobileCard){
            /** @var MobileCard $item */
            $item->status = Constant::ERROR_CARD;
            $order->status = Constant::REJECTED_ORDER;
            try{
                \DB::beginTransaction();
                $order->save();
                $item->save();
                \DB::commit();
                $return['success'] = true;
                $return['message'] = trans('common.saved');
            }catch(\Exception $ex){
                $return['message'] = trans('common.unsaved');
            }
        }elseif($item instanceof Course){
            $order->status = Constant::REJECTED_ORDER;
            if($order->save()){
                $return['success'] = true;
                $return['message'] = trans('common.saved');
            }
        }elseif($item instanceof BankPayment){
            //return $this->processBankPayment($order, $item, $options);
//            /** @var MobileCard $item */
//            $item->status = Constant::ERROR_CARD;
            $order->status = Constant::REJECTED_ORDER;
            try{
                \DB::beginTransaction();
                $order->save();
//                $item->save();
                \DB::commit();
                $return['success'] = true;
                $return['message'] = trans('common.saved');
            }catch(\Exception $ex){
                $return['message'] = trans('common.unsaved');
            }
        }else{
            /** @todo huy order neu khong duoc ho tro */
            throw new \Exception("Chưa hỗ trợ xử lý loại hóa đơn này");
        }
    }

    public function revertOrder(Order $order)
    {

    }

    private function getPaymentGate($gate){
        if($gate == 'bao_kim'){
            return new Payment();
        }
    }

    private function processCardOrder(Order $order, MobileCard $mobileCard){
        $gate = $mobileCard->gate;
        if($gate == '1_pay'){
            $payment = new Payment();
        }else{
            throw new UnSupportPaymentGateException("Không hỗ trợ cổng thanh toán này, liên hệ quản trị với mã lỗi ERROR_PAYMENT_GATE");
        }
        // xác định tài khoản doanh thu( đối với tài khoản doanh thu cần chính xác cả id và email )
        $revenue_acc = $this->getRevenueAccount();
        // xác định tài khoản nhận thanh toán thẻ
        $mobile_acc = $order->buyingUser;
        if(!$mobile_acc){
            throw new ConfigErrorException("Lỗi thanh toán, liên hệ quản trị với mã lỗi ERROR_CARD_ACC");
        }
        // xác định người nạp thẻ
        $user = $order->sellingUser;

        // thực hiện xử lý thẻ qua cổng thanh toán tương ứng
        $res = $payment->processCard($mobileCard);
        if($res['success'] == false){
            $order->status = Constant::REJECTED_ORDER;
            $order->save();
            $mobileCard->status = Constant::ERROR_CARD;
            $mobileCard->save();
            return $res;
        }
        else
        {
            // thực hiện cập nhật MobileCard, thực hiện transaction liên quan, cập nhật order, notification
            $mobileCard->price = $res['card']['amount'];
            $mobileCard->transaction_id = $res['card']['transaction_id'];
            $mobileCard->status = 1;
            $mobileCard->real_price = $mobileCard->price * (100 - $mobileCard->discount) / 100;
            $mobileCard->save();
            $order->status = Constant::APPROVED_ORDER;
            $order->item_price = $mobileCard->price;
            $mobile_acc = $mobile_acc->fresh();
            $revenue_acc = $revenue_acc->fresh();
            $user = $user->fresh();
            \DB::beginTransaction();
            // chuyển từ tài khoản đối ứng sang tài khoản nhận thanh toán thẻ
            $this->inner_transaction_manager->transfer($mobile_acc, $revenue_acc, $mobileCard->real_price, $order, true);
            // cộng tk quyền mua cho người nạp
            $this->inner_transaction_manager->increaseSecondaryWallet($user, $mobileCard->real_price, $order);
            // cập nhật trạng thái order
            $order->save();
            \DB::commit();
            return $res;
        }
    }

    /**
     * @return User tài khoản doanh thu
     * @throws ConfigErrorException
     */
    private function getRevenueAccount(){
        $revenue_acc = User::where('id', config('money.'.config("app.id").'.revenue_account.id'))
            ->where('email', config('money.'.config("app.id").'.revenue_account.email'))
            ->first();
        if(!$revenue_acc){
            throw new ConfigErrorException("Lỗi thanh toán, liên hệ quản trị với mã lỗi ERROR_REVENUE_ACC");
        }
        return $revenue_acc;
    }

    /**
     * @param Order $order
     * @param Course $course
     * @return array
     * @throws ConfigErrorException
     */
    public function processBuyCourseOrder(Order $order, Course $course){
        /** @todo thực hiện xử lý khi có promote code */
        // xác định tài khoản doanh thu( đối với tài khoản doanh thu cần chính xác cả id và email )
        $revenue_acc = $this->getRevenueAccount();
        $res = [
            'success' => false,
            'message' => 'Lỗi!',
        ];
        $seller = $order->sellingUser;
        $owner = $course->user;
        $buyer = $order->buyingUser;
        $order->status = Constant::APPROVED_ORDER;
        $promote_code     = [];
        if($order->promote_code != "")
        {
            $promote_code   =   $this->promo_code->processPromoCode($order->promote_code,$course);
            $partner        =   User::where('id',$promote_code['partner']['id'])->first();
        }

        try {
            // refresh wallets
            $owner = $owner->fresh();
            $buyer = $buyer->fresh();
            $revenue_acc = $revenue_acc->fresh();
            //$partner = $partner->fresh();
            //Xử lý mã giảm giá
            \DB::beginTransaction();
            if($order->promote_code != ""){

                //Có mã giảm giá.
                if(!empty($promote_code) && $promote_code['success'] == true){

                    //Thực hiện giao dịch cho Buyer
                    if($promote_code['buyer']['enjoy'] > 0){
                        $cou_price  =   $course->cou_price - ($course->cou_price*$promote_code['buyer']['enjoy'])/100;
                        $this->inner_transaction_manager->decreaseSecondaryWallet($buyer, $cou_price, $order);

                        //Tạo code cho người mua nếu chưa có
                        $this->promo_code->createCodeForBuyer($buyer->id);
                    }

                    //Thực hiện giao dịch cho partner
                    if($promote_code['partner']['enjoy'] > 0){
                        $money_for_partner    =   ($course->cou_price*$promote_code['partner']['enjoy'])/100;
                        $this->inner_transaction_manager->transfer($revenue_acc, $partner, $money_for_partner, $order, true);
                        //Tích lũy $ và tăng level cho partner
                        $this->promo_code->increasedLevelPartner($order->promote_code,$course->cou_price);
                    }

                    //Thực hiện giao dịch cho seller (giáo viên)
                    if($promote_code['seller']['enjoy'] > 0){
                        $money_for_seller    =   ($course->cou_price*$promote_code['seller']['enjoy'])/100;
                        $this->inner_transaction_manager->transfer($revenue_acc, $owner, $money_for_seller, $order, true);
                    }

                    $res['success'] = true;
                    $res['message'] = "Mua khóa học thành công";
                    $buyer->registerCourse($course, $order);
                    $order->save();

                }

            } else {

                //không dùng mã.
                $this->inner_transaction_manager->decreaseSecondaryWallet($buyer, $course->cou_price, $order);
                $money_for_owner = $order->item_price * config('money.'.config("app.id").'.course_price_percent_for_owner', 40) / 100;
                $this->inner_transaction_manager->transfer($revenue_acc, $owner, $money_for_owner, $order, true);
                $res['success'] = true;
                $res['message'] = "Mua khóa học thành công";
                $buyer->registerCourse($course, $order);
                $order->save();
            }
            \DB::commit();
        }catch (\Exception $ex){
            \Log::error($ex->getMessage());
            $order->status = Constant::REJECTED_ORDER;
            $order->save();
            $res['message'] = $ex->getMessage();
        }

        return $res;
    }

    public function processBankPayment(Order $order, BankPayment $bankPayment, array $options = [])
    {
        /*$transaction_id = array_get($options, 'transaction_id', $bankPayment->transaction_id);
        $other_info = array_get($options, 'other_info', $bankPayment->other_info);
        $user_loging = auth()->user();
        if(empty($transaction_id)){
            return [
                'success' => false,
                'message' => 'Bạn cần nhập mã giao dịch trả về từ cổng thanh toán/ngân hàng để hoàn thành thao tác',
            ];
        }*/

        $gate = $bankPayment->gate;
        if($gate == '1_pay'){
            $payment = new Payment();
        }else{
            throw new UnSupportPaymentGateException("Không hỗ trợ cổng thanh toán này, liên hệ quản trị với mã lỗi ERROR_PAYMENT_GATE");
        }

        /** @todo thực hiện xử lý khi có promote code */
        // xác định tài khoản doanh thu( đối với tài khoản doanh thu cần chính xác cả id và email )
        $revenue_acc = $this->getRevenueAccount();
        $res = $payment->processBank($order, $bankPayment);

        /*$seller = $order->sellingUser; // nguoi nap tien
        $buyer = $order->buyingUser; // tk nhan thanh toan cua he thong

        if($res['success'] == false){
            $order->status = Constant::REJECTED_ORDER;
            $order->save();
            $bankPayment->other_info = "Thẻ lỗi";
            $bankPayment->save();
            return $res;
        }
        $bankPayment->bank_name = $res['card_name'];
        $bankPayment->bank_short_name = $res['card_type'];
        $bankPayment->save();
        $order->status = Constant::APPROVED_ORDER;
        $order->item_price = $bankPayment->price;
        $mobile_acc = $buyer->fresh();
        $revenue_acc = $revenue_acc->fresh();
        $user = $seller->fresh();
        \DB::beginTransaction();
        // chuyển từ tài khoản đối ứng sang tài khoản nhận thanh toán thẻ
        $this->inner_transaction_manager->transfer($mobile_acc, $revenue_acc, $bankPayment->price, $order, true);
        // cộng tk quyền mua cho người nạp
        $this->inner_transaction_manager->increaseSecondaryWallet($user, $bankPayment->price, $order);
        // cập nhật trạng thái order
        $order->save();
        $res['success'] = true;
        $res['message'] = 'Xác nhận đơn hàng thành công';
        \DB::commit();*/
        return $res;
    }

    public function makeRechargeBankCardOrder($user, $amount){
        $revenue_acc = $this->getRevenueAccount();
        $res = [
            'success' => false,
            'message' => 'Lỗi!',
        ];
    }
}