<?php
/**
 * Created by PhpStorm.
 * User: hocvt
 * Date: 1/19/16
 * Time: 08:32
 */

namespace App\Core\Money\BaoKimApi;


class BaoKimPayment
{
    /**
     * Cấu hình phương thức thanh toán với các tham số
     * E-mail Bảo Kim - E-mail tài khoản bạn đăng ký với BaoKim.vn.
     * Merchant ID - là “mã website” được Baokim cấp khi bạn đăng ký tích hợp.
     * Mã bảo mật - là “mật khẩu” được Baokim cấp khi bạn đăng ký tích hợp
     * Vd : 12f31c74fgd002b1
     * Server Bảo Kim
     * Trang ​Kiểm thử - server để test thử phương thức thanh. .toán
     * Trang thực tế - Server thực tế thực hiện thanh toán.
     * https://www.baokim.vn/payment/order/version11' => ('Trang thực tế'),
     * http://kiemthu.baokim.vn/payment/order/version11' => ('Trang kiểm thử')
     * Chọn Save configuration để áp dụng thay đổi
     * Hàm xây dựng url chuyển đến BaoKim.vn thực hiện thanh toán, trong đó có tham số mã hóa (còn gọi là public key)
     * @param $data
     * @param $order_id                Mã đơn hàng
     * @return url cần tạo
     * @internal param Email $business tài khoản người bán
     * @internal param Giá $total_amount trị đơn hàng
     * @internal param Phí $shipping_fee vận chuyển
     * @internal param Thuế $tax_fee
     * @internal param Mô $order_description tả đơn hàng
     * @internal param Url $url_success trả về khi thanh toán thành công
     * @internal param Url $url_cancel trả về khi hủy thanh toán
     * @internal param Url $url_detail chi tiết đơn hàng
     * @internal param null $payer_name
     * @internal param null $payer_email
     * @internal param null $payer_phone_no
     * @internal param null $shipping_address
     */
    public function createRequestUrl($data, $order_id)
    {
        $total_amount = str_replace('.', '', $data['total_amount']);
        $base_url = route('hone');
        $url_success = $base_url;// . '/success';
        $url_cancel = $base_url;// . '/cancel';
        $currency = 'VND'; // USD
        // Mảng các tham số chuyển tới baokim.vn
        $params = array(
            'merchant_id' => strval(config('money.gates.bao_kim.pro_merchant_id')),
            'order_id' => strval($order_id),
            'business' => strval(config('money.gates.bao_kim.pro_email')),
            'total_amount' => strval($total_amount),
            'shipping_fee' => strval('0'),
            'tax_fee' => strval('0'),
            'order_description' => strval('Thanh toán đơn hàng từ Website ' . $base_url . ' với mã đơn hàng ' . $order_id),
            'url_success' => strtolower($url_success),
            'url_cancel' => strtolower($url_cancel),
            'url_detail' => strtolower(''),
            'payer_name' => strval($data['payer_name']),
            'payer_email' => strval($data['payer_email']),
            'payer_phone_no' => strval($data['payer_phone_no']),
            'shipping_address' => strval($data['address']),
            'currency' => strval($currency),
        );
        ksort($params);

        $params['checksum'] = hash_hmac('SHA1', implode('', $params), config('money.gates.bao_kim.pro_secure_pas'));

        //Kiểm tra  biến $redirect_url xem có '?' không, nếu không có thì bổ sung vào
        $redirect_url = config('money.gates.bao_kim.pro_payment_url') . config('money.gates.bao_kim.pro_api_payment_endpoint');
        if (strpos($redirect_url, '?') === false) {
            $redirect_url .= '?';
        } else if (substr($redirect_url, strlen($redirect_url) - 1, 1) != '?' && strpos($redirect_url, '&') === false) {
            // Nếu biến $redirect_url có '?' nhưng không kết thúc bằng '?' và có chứa dấu '&' thì bổ sung vào cuối
            $redirect_url .= '&';
        }

        // Tạo đoạn url chứa tham số
        $url_params = '';
        foreach ($params as $key => $value) {
            if ($url_params == '')
                $url_params .= $key . '=' . urlencode($value);
            else
                $url_params .= '&' . $key . '=' . urlencode($value);
        }
        return $redirect_url . $url_params;
    }

    /**
     * Hàm thực hiện xác minh tính chính xác thông tin trả về từ BaoKim.vn
     * @param $url_params chứa tham số trả về trên url
     * @return true nếu thông tin là chính xác, false nếu thông tin không chính xác
     */
    public function verifyResponseUrl($url_params = array())
    {
        if (empty($url_params['checksum'])) {
            echo "invalid parameters: checksum is missing";
            return FALSE;
        }

        $checksum = $url_params['checksum'];
        unset($url_params['checksum']);

        ksort($url_params);

        if (strcasecmp($checksum, hash_hmac('SHA1', implode('', $url_params), config('money.gates.bao_kim.pro_secure_pas'))) === 0)
            return TRUE;
        else
            return FALSE;
    }
}