<?php
/**
 * Created by PhpStorm.
 * User: hocvt
 * Date: 1/4/16
 * Time: 14:53
 */

namespace App\Models;



use App\Core\Money\Utils\Constant;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Order
 *
 * @property integer $id
 * @property string $payment_method
 * @property integer $payment_transaction_id
 * @property integer $type
 * @property integer $seller
 * @property integer $buyer
 * @property integer $created_by
 * @property string $item_type
 * @property integer $item_id
 * @property integer $item_price
 * @property string $promote_code
 * @property integer $status
 * @property integer $approved_by
 * @property integer $reverted_by
 * @property string $reverted_at
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \App\Models\User $buyingUser
 * @property-read \App\Models\User $sellingUser
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Transaction[] $innerTransactions
 * @property-read mixed $status_string
 * @property-read mixed $item_name
 * @property-read mixed $type_string
 * @property-read mixed $preview_string
 * @property-read mixed $code
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Order whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Order wherePaymentMethod($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Order wherePaymentTransactionId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Order whereType($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Order whereSeller($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Order whereBuyer($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Order whereCreatedBy($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Order whereItemType($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Order whereItemId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Order whereItemPrice($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Order wherePromoteCode($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Order whereStatus($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Order whereApprovedBy($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Order whereRevertedBy($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Order whereRevertedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Order whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Order whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Order extends Model
{
    protected $table = "orders";
    const CODE_PREFIX = 'UOID';

    /**
     * Liên kết đến người mua
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function buyingUser()
    {
        return $this->belongsTo(User::class, 'buyer');
    }

    /**
     * Liên kết đến người bán
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function sellingUser()
    {
        return $this->belongsTo(User::class, 'seller');
    }

    public function innerTransactions()
    {
        return $this->hasMany(Transaction::class, 'order_id');
    }

    public function getStatusStringAttribute(){
        return Constant::orderStatus($this->status);
    }

    public function getItemNameAttribute(){
        return Constant::sellableString($this->item_type);
    }

    public function getTypeStringAttribute(){
        return Constant::orderType($this->type);
    }

    public function getItemObject(){
        try{
            $class = $this->item_type;
            return $class::find($this->item_id);
        }catch (\Exception $ex){
            return null;
        }
    }

    public function getPreviewStringAttribute(){

    }

    public function make_guide_payment_link()
    {
        if($this->item_type == BankPayment::class)
            return route('user.financial.payment_guide', ['order_id' => $this->id]);
        else return "";
    }

    public function getCodeAttribute(){
        if($this->exists){
            return self::CODE_PREFIX . $this->id;
        }else{
            return '';
        }
    }

    public static function findByCode($code){
        $id = intval(str_replace(self::CODE_PREFIX, '', $code));
        return self::find($id);
    }

}