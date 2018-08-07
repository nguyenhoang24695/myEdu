<?php
/**
 * Created by PhpStorm.
 * User: hocvt
 * Date: 1/19/16
 * Time: 08:31
 */

namespace App\Core\Money\BaoKimApi;


class BaoKimPaymentPro
{
    /**
     * Call API GET_SELLER_INFO
     *  + Create bank list show to frontend
     * @return string
     */
    public function get_seller_info()
    {
        $param = array(
            'business' => config('money.gates.bao_kim.pro_email'),
        );
        $call_restfull = new CallRestful();
        $call_API = $call_restfull->call_API("GET", $param, config('money.gates.bao_kim.pro_api_seller_info_endpoint') );
        if (is_array($call_API)) {
            if (isset($call_API['error'])) {
                echo  "<strong style='color:red'>call_API" . json_encode($call_API['error']) . "- code:" . $call_API['status'] . "</strong> - " . "System error. Please contact to administrator";die;
            }
        }

        $seller_info = json_decode($call_API, true);
        if (!empty($seller_info['error'])) {
            echo "<strong style='color:red'>Seller_info" . json_encode($seller_info['error']) . "</strong> - " . "System error. Please contact to administrator"; die;
        }

        $banks = $seller_info['bank_payment_methods'];

        return $banks;
    }


    /**
     * Call API PAY_BY_CARD
     *  + Get Order info
     *  + Sent order, action payment
     *
     * @param $data
     * @param $order_id
     * @param int $transaction_mode_id
     * @param string $back_link
     * @return mixed
     */
    public function pay_by_card($data, $order_id, $transaction_mode_id = 1, $back_link = '')
    {
        $base_url = route('home');
        $back_link = empty($back_link) ? $base_url : $back_link;
        $url_success = $back_link;
        $url_cancel = $back_link;
        $total_amount = str_replace('.','',$data['total_amount']);

        $params['business'] = strval(config('money.gates.bao_kim.pro_email'));
        $params['bank_payment_method_id'] = intval($data['bank_payment_method_id']);
        $params['transaction_mode_id'] = $transaction_mode_id; // 2- trực tiếp
        $params['escrow_timeout'] = 3;

        $params['order_id'] = $order_id;
        $params['total_amount'] = $total_amount;
        $params['shipping_fee'] = '0';
        $params['tax_fee'] = '0';
        $params['currency_code'] = 'VND'; // USD

        $params['url_success'] = $url_success;
        $params['url_cancel'] = $url_cancel;
        $params['url_detail'] = '';

        $params['order_description'] = 'Thanh toán đơn hàng từ Website '. $base_url . ' với mã đơn hàng ' . $order_id;
        $params['payer_name'] = $data['payer_name'];
        $params['payer_email'] = $data['payer_email'];
        $params['payer_phone_no'] = $data['payer_phone_no'];
        $params['payer_address'] = $data['address'];

        $call_restfull = new CallRestful();
        $result = json_decode($call_restfull->call_API("POST", $params, config('money.gates.bao_kim.pro_api_pay_by_card_endpoint')), true);

        return $result;
    }

    public function generateBankImage($banks,$payment_method_type){
        $html = '';

        foreach ($banks as $bank) {
            if ($bank['payment_method_type'] == $payment_method_type) {
                $html .= '<li><img class="img-bank"   id="' . $bank['id'] .  '" src="' .  $bank['logo_url'] . '" title="' .  $bank['name'] . '"/></li>';
            }
        }
        return $html;
    }

    public function filterBankList($banks,$payment_method_type){
        $_banks = [];

        foreach ($banks as $bank) {
            if ($bank['payment_method_type'] == $payment_method_type) {
                $_banks[] = $bank;
            }
        }
        return $_banks;
    }
}