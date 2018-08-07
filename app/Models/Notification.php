<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\Notification
 *
 * @property integer $id
 * @property string $type
 * @property integer $object_id
 * @property integer $user_id
 * @property string $body
 * @property string $url
 * @property boolean $read
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $subject
 * @property string $object_type
 * @property \Carbon\Carbon $sent_at
 * @property integer $sender_id
 * @property string $deleted_at
 * @property-read \App\Models\User $user
 * @property-read \App\Models\User $sender
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Notification whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Notification whereType($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Notification whereObjectId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Notification whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Notification whereBody($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Notification whereUrl($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Notification whereRead($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Notification whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Notification whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Notification whereSubject($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Notification whereObjectType($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Notification whereSentAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Notification whereSenderId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Notification whereDeletedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Notification unread()
 * @mixin \Eloquent
 */
class Notification extends Model
{
    use SoftDeletes;

    protected $table        = "notification";

    protected $primaryKey   = 'id';

    protected $dates        = ['deleted_at'];

    public $timestamps      = true;

    protected $fillable     = ['user_id', 'type', 'subject', 'body', 'object_id', 'object_type', 'sent_at', 'read','url'];

    private $relatedObject = null;

    public function getDates()
    {
        return ['created_at', 'updated_at', 'sent_at'];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function scopeUnread($query)
    {
        return $query->where('read', '=', 0);
    }

    public function withSubject($subject)
    {
        $this->subject = $subject;

        return $this;
    }

    public function withBody($body)
    {
        $this->body = $body;

        return $this;
    }

    public function withType($type)
    {
        $this->type = $type;

        return $this;
    }

    public function regarding($object)
    {
        if(is_object($object))
        {
            $this->object_id   = $object->id;
            $this->object_type = get_class($object);
        }

        return $this;
    }

    public function from($user)
    {
        $this->sender()->associate($user);

        return $this;
    }

    public function to($user)
    {
        $this->user()->associate($user);

        return $this;
    }

    public function send()
    {
        $this->sent_at = new Carbon();
        $this->save();

        return $this;
    }

    public function hasValidObject()
    {
        try
        {
            $object = call_user_func_array($this->object_type . '::findOrFail', [$this->object_id]);
        }
        catch(\Exception $e)
        {
            return false;
        }
     
        $this->relatedObject = $object;
     
        return true;
    }
     
    public function getObject()
    {
        if(!$this->relatedObject)
        {
            $hasObject = $this->hasValidObject();
     
            if(!$hasObject)
            {
                throw new \Exception(sprintf("Không tìm thấy object (%s có ID %s) associated với notification gửi.", $this->object_type, $this->object_id));
            }
        }
     
        return $this->relatedObject;
    }

    public function url_detail()
    {
        $url     = "";
        $type    = $this->type;
        if($type == 'message') {
            $url = url('dashboard/notification_detail?id='.$this->id);
        }
        return $url;
    }

    //Lấy ảnh avatar người gửi notify
    public function getObjImage()
    {
        return $this->sender->showAvatar();
    }
}
