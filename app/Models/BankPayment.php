<?php
/**
 * Created by PhpStorm.
 * User: hocvt
 * Date: 1/23/16
 * Time: 09:21
 */

namespace App\Models;


use App\Core\Money\Contracts\SellableContract;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * App\Models\BankPayment
 *
 * @property integer $id
 * @property string $gate
 * @property integer $bank_id
 * @property string $bank_name
 * @property string $bank_short_name
 * @property string $bank_account_name
 * @property string $bank_account_number
 * @property integer $price
 * @property string $payer_name
 * @property string $payer_email
 * @property string $payer_phone_no
 * @property string $payer_address
 * @property integer $bank_payment_method
 * @property string $bank_payment_link
 * @property string $transaction_id
 * @property string $other_info
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property integer $bank_payment_method_id
 * @method static \Illuminate\Database\Query\Builder|\App\Models\BankPayment whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\BankPayment whereGate($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\BankPayment whereBankId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\BankPayment whereBankName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\BankPayment whereBankShortName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\BankPayment whereBankAccountName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\BankPayment whereBankAccountNumber($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\BankPayment wherePrice($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\BankPayment wherePayerName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\BankPayment wherePayerEmail($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\BankPayment wherePayerPhoneNo($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\BankPayment wherePayerAddress($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\BankPayment whereBankPaymentMethod($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\BankPayment whereBankPaymentLink($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\BankPayment whereTransactionId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\BankPayment whereOtherInfo($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\BankPayment whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\BankPayment whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\BankPayment whereBankPaymentMethodId($value)
 * @mixin \Eloquent
 */
class BankPayment extends Model implements SellableContract
{
    protected $table = 'bank_payments';

    protected $valid_payment_methods = ['direct', 'guide'];
    protected $valid_gates = ['1_pay', 'manual'];

    public function validInfo(){
        if(!in_array($this->gate, $this->getValidGates())){
            throw new \Exception("Không hỗ trợ cổng thanh toán " . $this->gate . " chỉ hỗ trợ 'bao_kim,manual'");
        }
        if($this->gate == 'manual'){
            if($this->bank_payment_method != 'guide'){
                throw new \Exception("Hình thức chuyển tiền chỉ dùng phương thức guide ");
            }
        }
        if($this->gate == 'bao_kim'){
            if($this->bank_id < 1){
                throw new \Exception("Chưa có id ngân hàng, id này lấy từ Baokim khi tạo link thanh toán");
            }

        }
    }

    /**
     * @return null|Order
     */
    public function getOrder(){
        if(!$this->exists){
            return null;
        }
        return Order::where('item_type', $this->morphClass)->where('item_id', $this->id)->first();
    }

    /**
     * @return array
     */
    public function getValidPaymentMethods()
    {
        return $this->valid_payment_methods;
    }

    /**
     * @param array $valid_payment_methods
     */
    public function setValidPaymentMethods($valid_payment_methods)
    {
        $this->valid_payment_methods = $valid_payment_methods;
    }

    /**
     * @return array
     */
    public function getValidGates()
    {
        return $this->valid_gates;
    }

    /**
     * @param array $valid_gates
     */
    public function setValidGates($valid_gates)
    {
        $this->valid_gates = $valid_gates;
    }


    /**
     * @return User|mixed
     */
    public function getOwner()
    {
        $order = $this->getOrder();
        return $order == null ? null : $order->sellingUser;
    }


    /**
     * @return int|mixed
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     *
     */
    public function checkStatusPayment(){
        if($this->gate == 'manual'){
            return 0;// alway pending for manual gate
        }elseif($this->gate == 'bao_kim'){
            /** @todo thuc hien kiem tra qua bao kim */
            return 0;
        }
    }

    public function cancelPayment(){
        if($this->gate == 'manual'){
            return true;// allway true for manual gate
        }elseif($this->gate == 'bao_kim'){
            /** @todo gui yeu cau huy don hang len bao kim */
            return true;
        }
    }


}