<?php
/**
 * Created by PhpStorm.
 * User: hocvt
 * Date: 1/7/16
 * Time: 10:40
 */

namespace App\Core\Money\BaoKimApi;


use App\Core\Money\Contracts\MobilePayment;
use App\Models\BankPayment;
use App\Models\MobileCard;
use App\Models\Order;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Http\Request;
use phpDocumentor\Reflection\Location;
use Redirect;
use Response;

class Payment implements MobilePayment
{
    private $config = [
        'http_user' => '',//Tài khoản đăng ký truy cập API.
        'http_password' => '',//Mật khẩu tài khoản đăng ký truy cập API
        'merchant_id' => '',//Mã website đăng ký
        'payment_url' => '',//
        'card_url' => '',
        'api_username' => '',//Tài khoản đăng ký sử dụng API.
        'api_password' => '',//Mật khẩu tài khoản đăng ký truy cập API
        'secure_code' => '',//Mật khẩu website
        'bpn_link' => '',
    ];

    private $supported_provider = [
        'VINA', 'MOBI', 'VIETEL', 'VTC', 'GATE'
    ];

    private $curl_options;
    private $curl_normal_options; // request ko can xac thuc

    private $http;

    /**
     * Payment constructor.
     */
    public function __construct()
    {
        $this->config = config('money.gates.1_pay');
        $this->http = new Client();
//        $this->http->setDefaultOption('debug', true);
        $this->curl_options = [
            'CURLOPT_CONNECTTIMEOUT' => 0,
            'CURLOPT_TIMEOUT' => 30,
            'CURLOPT_POST' => 1,
            'CURLOPT_USERAGENT' => $_SERVER['HTTP_USER_AGENT'],
            'CURLOPT_SSL_VERIFYPEER' => false,
            'CURLOPT_SSL_VERIFYHOST' => 2,
            'CURLOPT_RETURNTRANSFER' => 1,
            //'CURLOPT_USERPWD' => $this->config['http_user'] . ':' . $this->config['http_password'],
        ];

        $this->curl_normal_options = [
            'CURLOPT_VERBOSE' => 1,
            'CURLOPT_HEADER' => false,
            'CURLINFO_HEADER_OUT' => true,
            'CURLOPT_HTTPAUTH' => CURLAUTH_DIGEST | CURLAUTH_BASIC,
//            'CURLOPT_POSTFIELDS' => 1,
        ];

//        curl_setopt($ch, CURLOPT_URL,'https://www.baokim.vn/bpn/verify');
//
//        curl_setopt($ch, CURLOPT_VERBOSE, 1);
//
//        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
//
//        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
//
//        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//
//        curl_setopt($ch, CURLOPT_POST, 1);
//
//        curl_setopt($ch, CURLOPT_POSTFIELDS, $req);
    }

    /**
     * Build du lieu gui di cho cong thanh toan
     * @param MobileCard $mobileCard
     * @return array
     */
    private function buildCardData(MobileCard $mobileCard)
    {
        $transRef = md5($mobileCard->provider . $mobileCard->id); //merchant's transaction reference
        \Log::info($transRef);
        $access_key = $this->config['access_key']; //require your access key from 1pay
        $secret = $this->config['secret']; //require your secret key from 1pay
        $type = $mobileCard->provider;
        $pin = $mobileCard->pin;
        $serial = $mobileCard->serial;
        $data = "access_key=" . $access_key . "&pin=" . $pin . "&serial=" . $serial . "&transRef=" . $transRef . "&type=" . $type;
        $signature = hash_hmac("sha256", $data, $secret);
        $data.= "&signature=" . $signature;
        return $data;
    }

    function execPostRequest($url, $data)
    {

        // open connection
        $ch = curl_init();

        // set the url, number of POST vars, POST data
        curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,0);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        // execute post
        $result = curl_exec($ch);

        // close connection
        curl_close($ch);
        return $result;
    }

    /**
     * Thuc hien nap the 1 the mobile, du lieu tra ve dang array[success, message, card[transaction_id, amount]]
     * @param MobileCard $mobileCard
     * @return array
     */
    public function processCard(MobileCard $mobileCard)
    {
        $res = null;
        try{
            $res = $this->execPostRequest($this->config['card_url'], $this->buildCardData($mobileCard));
        }catch(ClientException $ex){
            $res = $ex->getResponse();
        }catch (\Exception $ex){;
            return [
                'success' => false,
                'message' => "Lỗi nạp thẻ, vui lòng liên hệ quản trị."
            ];
        }finally{
            if($res != null){
                $response_data = json_decode($res, true);
                if($response_data['status'] == 00){// nạp thẻ thành công
                    return [
                        'success' => true,
                        'card' => [
                            'transaction_id' => $response_data['transRef'], // mã giao dịch gửi đi
                            'amount' => $response_data['amount'], // mệnh giá thẻ
                        ],
                        'message' => trans($response_data['description']),
                    ];
                }else{
                    return [
                        'success' => false,
                        'card' => [
                            'transaction_id' => $response_data['transRef'], // mã giao dịch gửi đi
                            'amount' => $response_data['amount'], // mệnh giá thẻ
                        ],
                        'message' => $response_data['description'],
                    ];
                }
            }else{
                /*$transRef = $mobileCard->provider . $mobileCard->id;
                $access_key = $this->config['access_key'];
                $secret = $this->config['secret'];
                $type = $mobileCard->provider;
                $pin = $mobileCard->pin;
                $serial = $mobileCard->serial;
                $data_ep = "access_key=" . $access_key . "&pin=" . $pin . "&serial=" . $serial . "&transId=&transRef=" . $transRef . "&type=" . $type;
                $signature_ep = hash_hmac("sha256", $data_ep, $secret);
                $data_ep.= "&signature=" . $signature_ep;
                $query_api_ep = execPostRequest('https://api.1pay.vn/card-charging/v5/query', $data_ep);
                $decode_cardCharging=json_decode($query_api_ep,true);
                $description_ep = $decode_cardCharging["description"];
                $status_ep = $decode_cardCharging["status"];
                $amount_ep = $decode_cardCharging["amount"];*/
                return [
                    'success' => false,
                    'message' => "Lỗi kết nối khi nạp thẻ, vui lòng liên hệ quản trị!"
                ];
            }
        }
    }

    private function buildBankData(Order $order, BankPayment $bankPayment)
    {
        $access_key = $this->config['access_key']; //require your access key from 1pay
        $secret = $this->config['secret']; //require your secret key from 1pay
        $return_url = "http://myedu.com.vn/dashboard/payment/by-bank";
        $command = 'request_transaction';
        $amount = $bankPayment->price;
        $order_id = $order->id;
        $order_info = "Nạp tiền từ ngân hàng";

        $data = "access_key=".$access_key."&amount=".$amount."&command=".$command."&order_id=".$order_id."&order_info=".$order_info."&return_url=".$return_url;
        $signature = hash_hmac("sha256", $data, $secret);
        $data.= "&signature=".$signature;

        return $data;
    }

    public function processBank(Order $order, BankPayment $bankPayment)
    {
        $res = null;
        try{
            $res = $this->execPostRequest($this->config['bank_url'], $this->buildBankData($order, $bankPayment));
            $decode_bankCharging=json_decode($res,true);  // decode json
            $bankPayment->transaction_id = $decode_bankCharging["trans_ref"];
            $bankPayment->save();
            return $pay_url = $decode_bankCharging["pay_url"];
            //Ex: {"pay_url":"http://api.1pay.vn/bank-charging/sml/nd/order?token=LuNIFOeClp9d8SI7XWNG7O%2BvM8GsLAO%2BAHWJVsaF0%3D", "status":"init", "trans_ref":"16aa72d82f1940144b533e788a6bcb6"}
        }catch(ClientException $ex){
            $res = $ex->getResponse();
        }catch (\Exception $ex){;
            return [
                'success' => false,
                'message' => "Lỗi nạp thẻ, vui lòng liên hệ quản trị."
            ];
        }/*finally{
            if($res != null){
                $response_data = json_decode($res, true);
                if($response_data['status'] == 00){// nạp thẻ thành công
                    return [
                        'success' => true,
                        'card' => [
                            'transaction_id' => $response_data['transRef'], // mã giao dịch gửi đi
                            'amount' => $response_data['amount'], // mệnh giá thẻ
                        ],
                        'message' => trans($response_data['description']),
                    ];
                }else{
                    return [
                        'success' => false,
                        'card' => [
                            'transaction_id' => $response_data['transRef'], // mã giao dịch gửi đi
                            'amount' => $response_data['amount'], // mệnh giá thẻ
                        ],
                        'message' => $response_data['description'],
                    ];
                }
            }else{
                return [
                    'success' => false,
                    'message' => "Lỗi kết nối khi nạp thẻ, vui lòng liên hệ quản trị!"
                ];
            }
        }*/
    }

    /**
     * Thực hiện lấy link thank toán từ Bảo Kim
     * @param BankPayment $bankPayment
     * @param Order $order
     * @param boolean|true $direct
     * @param string $back_link
     * @return mixed
     * @throws \Exception
     */
    public function getPaymentLink(BankPayment $bankPayment, Order $order, $direct = true, $back_link = ''){
        if(!$direct){
            throw new \Exception("Not support ");
        }
        $baokim = new BaoKimPaymentPro();
        $data = [
            'bank_payment_method_id'    => $bankPayment->bank_payment_method_id,
            'total_amount'              => $bankPayment->price,
            'payer_name'                => $bankPayment->payer_name,
            'payer_email'               => $bankPayment->payer_email,
            'payer_phone_no'            => $bankPayment->payer_phone_no,
            'address'                   => $bankPayment->payer_address,

        ];
        \Log::alert($bankPayment->toArray());
        \Log::alert($data);
        $res = $baokim->pay_by_card($data, $order->code, 1, $back_link);
        \Log::alert($res);
        if(isset($res['error'])){
            $res['success'] = false;
        }else{
            $res['success'] = true;
        }
        return $res;
    }

    /**
     * @param Request $request
     * @return bool|Request
     */
    public function verifyBPN(Request $request)
    {
        $res = null;
        try{
            $res = $this->http
                ->request('POST' ,$this->config['bpn_link'],
                    [
                        //'debug' => true,
                        'form_params' => $request->all(),
                    ]);
        }catch(ClientException $ex){
            return false;
        }catch (\Exception $ex){
            \Log::error("Lỗi nạp thẻ Bảo Kim : " . $ex->getMessage());
            return false;
        }
        return $request;
    }
}