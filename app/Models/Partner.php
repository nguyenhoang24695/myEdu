<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\Partner
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $marketing_mouth Truyền miệng
 * @property string $marketing_website Website
 * @property string $marketing_social Mạng xã hội
 * @property string $marketing_ads Phương tiện quảng cáo
 * @property string $marketing_other Khác
 * @property string $views_website Lượt truy cập website
 * @property string $access Biết đến qua kênh nào
 * @property boolean $active
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property \Carbon\Carbon $deleted_at
 * @property string $address_website Địa chỉ website
 * @property string $address_social
 * @property string $marketing_other_detail
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Partner whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Partner whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Partner whereMarketingMouth($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Partner whereMarketingWebsite($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Partner whereMarketingSocial($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Partner whereMarketingAds($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Partner whereMarketingOther($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Partner whereViewsWebsite($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Partner whereAccess($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Partner whereActive($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Partner whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Partner whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Partner whereDeletedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Partner whereAddressWebsite($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Partner whereAddressSocial($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Partner whereMarketingOtherDetail($value)
 * @mixin \Eloquent
 */
class Partner extends Model
{
    use SoftDeletes;

    protected $table    = "partners";

    protected $guarded  = ["id"];

    protected $dates    = ['deleted_at'];

    public $timestamps  = true;

    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }

    //Kiểm tra xem đối tượng có phải là partner hay ko
    public function check($user_id)
    {
        return $this->where('user_id', $user_id)->where('active', 1)->count() > 0;
    }
}
