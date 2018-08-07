<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\NotificationSetting
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $notify_type
 * @property boolean $enable_email
 * @property boolean $enable_profile
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\Models\NotificationSetting whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\NotificationSetting whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\NotificationSetting whereNotifyType($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\NotificationSetting whereEnableEmail($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\NotificationSetting whereEnableProfile($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\NotificationSetting whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\NotificationSetting whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class NotificationSetting extends Model
{

    protected $table        = "notification_setting";

    protected $primaryKey   = 'id';

    public $timestamps      = true;
}
