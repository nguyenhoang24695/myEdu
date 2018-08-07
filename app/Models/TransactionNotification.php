<?php
/**
 * Created by PhpStorm.
 * User: hocvt
 * Date: 1/4/16
 * Time: 10:36
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\TransactionNotification
 *
 * @property integer $id
 * @property integer $transaction_id
 * @property integer $receiver
 * @property string $preview
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\Models\TransactionNotification whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\TransactionNotification whereTransactionId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\TransactionNotification whereReceiver($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\TransactionNotification wherePreview($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\TransactionNotification whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\TransactionNotification whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class TransactionNotification extends Model
{
    protected $table = "transaction_notifications";
}