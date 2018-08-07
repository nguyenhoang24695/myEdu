<?php
/**
 * Created by PhpStorm.
 * User: hocvt
 * Date: 1/4/16
 * Time: 10:34
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Transaction
 *
 * @property integer $id
 * @property integer $order_id
 * @property integer $type
 * @property integer $order_status
 * @property integer $from_acc
 * @property integer $to_acc
 * @property integer $amount
 * @property integer $created_by
 * @property integer $from_acc_remain
 * @property integer $to_acc_remain
 * @property string $acc_type
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \App\Models\User $fromUser
 * @property-read \App\Models\User $toUser
 * @property-read \App\Models\Order $order
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Transaction whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Transaction whereOrderId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Transaction whereType($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Transaction whereOrderStatus($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Transaction whereFromAcc($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Transaction whereToAcc($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Transaction whereAmount($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Transaction whereCreatedBy($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Transaction whereFromAccRemain($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Transaction whereToAccRemain($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Transaction whereAccType($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Transaction whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Transaction whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Transaction extends Model
{
    protected $table = "transactions";

    public function fromUser(){
        return $this->belongsTo(User::class, 'from_acc');
    }

    public function toUser(){
        return $this->belongsTo(User::class, 'to_acc');
    }

    public function order(){
        return $this->belongsTo(Order::class, 'order_id');
    }

}