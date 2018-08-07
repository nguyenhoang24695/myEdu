<?php
/**
 * Created by PhpStorm.
 * User: hocvt
 * Date: 1/6/16
 * Time: 14:20
 */

namespace App\Models;


use App\Core\Money\Contracts\SellableContract;
use App\Core\Money\Utils\Constant;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\MobileCard
 *
 * @property integer $id
 * @property string $transaction_id
 * @property string $pin
 * @property string $serial
 * @property string $provider
 * @property integer $price
 * @property integer $user_id
 * @property string $gate
 * @property integer $discount
 * @property integer $status
 * @property integer $real_price
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \App\Models\User $user
 * @property-read mixed $status_string
 * @method static \Illuminate\Database\Query\Builder|\App\Models\MobileCard whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\MobileCard whereTransactionId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\MobileCard wherePin($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\MobileCard whereSerial($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\MobileCard whereProvider($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\MobileCard wherePrice($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\MobileCard whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\MobileCard whereGate($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\MobileCard whereDiscount($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\MobileCard whereStatus($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\MobileCard whereRealPrice($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\MobileCard whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\MobileCard whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class MobileCard extends Model implements SellableContract
{
    protected $table = 'mobile_cards';

    public static function validSupportProvider($provider)
    {
        //$provider = mb_strtoupper($provider);
        if(in_array($provider, config('money.'.config("app.id").'.validated_card_provider'))){
            return $provider;
        }else{
            return false;
        }
    }

    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }

    public function getOwner()
    {
        return $this->user;
    }

    public function getPrice()
    {
        return $this->price;
    }

    public function getStatusStringAttribute(){
        return Constant::cardStatus($this->status);
    }
}