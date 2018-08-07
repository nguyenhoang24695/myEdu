<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\PromoCode
 *
 * @property integer $id
 * @property string $code Mã code phải duy nhất, chứa (4-6) ký tự
 * @property integer $user_id
 * @property integer $discount_1 Chiết khấu khóa học người khác
 * @property integer $discount_2 Chiết khấu khóa học của chính partner
 * @property integer $discount_max Giới hạn % chiết khấu phụ thuộc vào total_money
 * @property integer $used_count Tổng số người sử dụng mã code
 * @property integer $total_money Tổng số tiền sử dụng mã code
 * @property boolean $partner_level
 * @property boolean $total_edit_code Tổng số lần sửa mã code
 * @property boolean $active
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property \Carbon\Carbon $deleted_at
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Query\Builder|\App\Models\PromoCode whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\PromoCode whereCode($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\PromoCode whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\PromoCode whereDiscount1($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\PromoCode whereDiscount2($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\PromoCode whereDiscountMax($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\PromoCode whereUsedCount($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\PromoCode whereTotalMoney($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\PromoCode wherePartnerLevel($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\PromoCode whereTotalEditCode($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\PromoCode whereActive($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\PromoCode whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\PromoCode whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\PromoCode whereDeletedAt($value)
 * @mixin \Eloquent
 */
class PromoCode extends Model
{
	use SoftDeletes;
	
    protected $table    = "promo_codes";

    protected $guarded  = ["id"];

    protected $dates    = ['deleted_at'];

    public $timestamps  = true;

    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }

}
