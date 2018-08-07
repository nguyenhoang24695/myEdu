<?php
/**
 * Created by PhpStorm.
 * User: hocvt
 * Date: 2/18/16
 * Time: 09:36
 */

namespace App\Http\Controllers\Api\V1;


use App\Core\Money\BaoKimApi\BaoKimPaymentPro;
use App\Core\Money\BaoKimApi\Payment;
use App\Core\Money\Utils\OrderManager;
use App\Models\Order;
use Illuminate\Http\Request;

class BaoKimController extends ApiController
{
    public function bpn(Request $request)
    {
//        \Log::error("BPN nhận được get \n " . print_r($request->query(), true));
//        \Log::error("BPN nhận được post \n " . print_r($request->all(), true));
        $bk_pro = new Payment();
        $requested  = $bk_pro->verifyBPN($request);
        if($requested === false){
            \Log::error('Xác thực sai từ BPN');
        }
        // thực hiện xác nhân đơn hàng
        $order = Order::findByCode($request->get('order_id'));
        $order_manage = new OrderManager();
        if(!$order){
            \Log::error("Khong tim thay hoa don tuong ung voi request \n " . print_r($request->all(), true));
        }
        $transaction_status = $request->get('transaction_status', 0);

        if(in_array($transaction_status, [5,6,7,8,15])){// đơn hàng hủy
            $order_manage->cancelOrder($order);
        }elseif(in_array($transaction_status, [4])){ // đơn hàng thành công
            $order_manage->processOrder($order, [
                'transaction_id' => $request->get('transaction_id', ''),
                'other_info' => print_r($request->all(), true)
            ]);
        }else{// trạng thái khác => bỏ qua
            \Log::notice("Khong xu ly BPN tra ve request \n " . print_r($request->all(), true));
        }
    }
}