<?php
/**
 * Class này làm nhiệm vụ thực hiện các thao tác giao dịch giữa các người dùng, giữa người dùng với hệ thống,
 * các giao dịch này mang tính bao quát hơn ví dụ 1 người nạp thẻ, chuyển tiền cho người dùng khác,
 * chuyển từ tài khoản doanh thu sang tài khoản quyền mua, ...
 * User: hocvt
 * Date: 1/4/16
 * Time: 15:23
 */

namespace App\Core\Money\Utils;


use App\Core\Money\BaoKimApi\Payment;
use App\Core\Money\Contracts\WalletContract;
use App\Core\Money\Exceptions\UnSupportedProviderException;
use App\Models\BankPayment;
use App\Models\Course;
use App\Models\MobileCard;
use App\Models\MoneyCard;
use App\Models\Order;
use App\Models\User;

class TransactionManager
{

    private $helper;
    private $order_manager;

    /**
     * TransactionManager constructor.
     */
    public function __construct()
    {
        $this->helper = new Helper();
        $this->order_manager = new OrderManager();
    }

    /**
     * Thao tác nạp tiền
     * @param User $user
     *
     */
    public function recharge(User $user, $data){

    }

    public function buyCourse(WalletContract $walletContract, Course $course, $promote_code = '', $buyer = null){
        if($buyer == null){
            $buyer = $course->user;
        }
        // tạo order
        \DB::beginTransaction();
        // Tạo order
        $order = new Order();
        $order->buyer = $walletContract->id; // nguoi mua la tk nhan thanh toan da duoc cai dat trong config
        $order->seller = $buyer->id; // nguoi ban chinh la nguoi dung
        $order->item_type = $course->getMorphClass();// get class de tao lai object sau nay khi can
        $order->item_id = $course->id;
        $order->item_price = $course->cou_price;
        $order->type = Constant::BUY_ORDER;// đơn hàng mua bán trong hệ thống
        $order->created_by = $walletContract->id;
        $order->payment_method = 'inner';
        $order->promote_code = $promote_code;
        /** payment_transaction_id duoc cap nhat khi thanh toan */
        $order->save();
        \DB::commit();
        // Thanh toan order vua tao
        return $this->order_manager->processOrder($order);
    }

    /**
     * @param User $user
     * @param $card_pin
     * @param $card_serial
     * @param $card_provider
     * @return array
     * @throws \Exception
     */
    public function rechargeByMobileCard(User $user, $card_pin, $card_serial, $card_provider){
        // xác định người nhận giao dịch
        /*$card_provider = MobileCard::validSupportProvider($card_provider);
        if(!$card_provider){
            throw new \Exception("Không hỗ trợ nhà mạng");
        }*/
        // lấy thông tin nguoi log thanh toan(mua lai the cua nguoi dung),
        // lay default khi ko co cai dat rieng cho nha mang
        $buyer_info = config('money.'.config("app.id").'.card_account.' . $card_provider, config('money.'.config("app.id").'.card_account.default'));
        $buyer = User::where('email', $buyer_info['email'])->first();
        if(!$buyer){
            return['success' => false,
                'message' => 'Chưa hỗ trợ thanh toán, liên hệ quản trị với mã lỗi INVALID_CARD_BUYER',
            ];
        };
        \DB::beginTransaction();
        // Tạo mobile card
        $mobile_card = new MobileCard();
        $mobile_card->pin = $card_pin;
        $mobile_card->provider = $card_provider;
        $mobile_card->serial = $card_serial;
        $mobile_card->user_id = $user->id;
        $mobile_card->gate = $buyer_info['gate'];
        $mobile_card->discount = $buyer_info['discount'];
        $mobile_card->save();
        // Tạo order
        $order = new Order();
        $order->buyer = $buyer->id; // nguoi mua la tk nhan thanh toan da duoc cai dat trong config
        $order->seller = $user->id; // nguoi ban chinh la nguoi dung
        $order->item_type = $mobile_card->getMorphClass();// get class de tao lai object sau nay khi can
        $order->item_id = $mobile_card->id;
        $order->type = Constant::RECHARGE_ORDER;// nap tai khoan
        $order->created_by = $user->id;
        $order->payment_method = $buyer_info['gate'];
        /** payment_transaction_id duoc cap nhat khi thanh toan */
        $order->save();
        \DB::commit();
        // Thanh toan order vua tao
        return $this->order_manager->processOrder($order);

        // Tạo notification
    }

    /**
     * Nạp tiền bằng hình thức chuyển khoản, bao gồm thực hiện thanh toán online và chuyển khoản theo hướng dẫn
     * @param User $u
     * @param int $amount VND
     * @param $bank_info [bank_id, bank_gate, bank_name, bank_short_name,
     *                    bank_account_name, bank_account_number, bank_payment_method, bank_payment_method_id]
     * @return array
     * @throws \Exception
     */
    public function rechargeByBankATM(User $u, $amount, $bank_info){
        // xác định người nhận giao dịch
        $pay_gate = array_get($bank_info, 'bank_gate');
        if($pay_gate == '1_pay'){
            $buyer_info = config('money.'.config("app.id").'.bank_card.default');
        }elseif($pay_gate == 'manual'){
            // xác định tk đối ứng tương ứng ngan hàng đã chọn
            $buyer_info = config('money.'.config("app.id").'.bank_exchange.' . $bank_info['bank_short_name']);
        }else{
            \Log::alert("Không hỗ cổng thanh toán " . $pay_gate);
            throw new \Exception("Không hỗ trợ cổng thanh toán");
        }

        $buyer = User::where('email', $buyer_info['email'])->first();
        if(!$buyer){
            return['success' => false,
                'message' => 'Chưa hỗ trợ thanh toán, liên hệ quản trị với mã lỗi INVALID_BANK_CARD_BUYER',
            ];
        };
        \DB::beginTransaction();
        // Tạo bank card
        $bank_card = new BankPayment();
        $bank_card->gate = $pay_gate;
        $bank_card->bank_payment_method = array_get($bank_info, 'bank_payment_method');
        /*$bank_card->bank_payment_method_id = array_get($bank_info, 'bank_payment_method_id');
        $bank_card->bank_id             = array_get($bank_info, 'bank_id');
        $bank_card->bank_name           = array_get($bank_info, 'bank_name');
        $bank_card->bank_short_name     = array_get($bank_info, 'bank_short_name');
        $bank_card->bank_account_name   = array_get($bank_info, 'bank_account_name');
        $bank_card->bank_account_number = array_get($bank_info, 'bank_account_number');*/
        // payer info
        $bank_card->payer_name      = array_get($bank_info, 'payer_name');
        $bank_card->payer_email     = array_get($bank_info, 'payer_email');
        $bank_card->payer_address   = array_get($bank_info, 'payer_address');
        $bank_card->payer_phone_no  = array_get($bank_info, 'payer_phone_no');
        // price
        $bank_card->price = $amount;
        // valid input info
        //$bank_card->validInfo();
        $bank_card->save();

        // Tạo order
        $order = new Order();
        $order->buyer = $buyer->id; // nguoi mua la tk nhan thanh toan da duoc cai dat trong config
        $order->seller = $u->id; // nguoi ban chinh la nguoi dung
        $order->item_type = $bank_card->getMorphClass();// get class de tao lai object sau nay khi can
        $order->item_id = $bank_card->id;
        $order->item_price = $bank_card->price;
        $order->type = Constant::RECHARGE_ORDER;// nap tai khoan
        $order->created_by = $u->id;
        $order->payment_method = $buyer_info['gate'];
        /** payment_transaction_id duoc cap nhat khi thanh toan */
        $order->save();
        \DB::commit();

        // get payment link if using bao_kim gate
        /*if($bank_card->gate == '1_pay' && $bank_card->bank_payment_method == 'direct'){
            $baokim_payment = new Payment();
            $res = $baokim_payment->getPaymentLink($bank_card, $order, true, array_get($bank_info, 'back_link'));
            if($res['success'] == true){
                $bank_card->transaction_id = $res['rvid'];
                $bank_card->bank_payment_link = $res['redirect_url'];
            }else{
                $order->status = Constant::REJECTED_ORDER;
                $order->save();
                throw new \Exception("Lỗi lấy liên kết thanh toán từ Bảo Kim : " . $res['error']);
            }
            $bank_card->save();
        }elseif($bank_card->gate == 'manual'){
            $bank_card->bank_payment_link = $order->make_guide_payment_link();
            $bank_card->save();
        }

        return [
            'success' => true,
            'method' => $bank_card->bank_payment_method,
            'next_link' => $bank_card->bank_payment_link,
        ];*/

        return $this->order_manager->processOrder($order);
    }

    public function rechargeByBankExchange($u, $amount){

    }
}