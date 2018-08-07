<?php

namespace App\Repositories\Frontend\Notification;

use App\Core\BaseRepository;
use App\Models\Notification;

class EloquentNotificationRepository extends BaseRepository implements NotificationContract {

    protected $model;

    public function __construct(Notification $Notification)
    {
        $this->model = $Notification;
    }

    public function isMarkReadNotification($id)
    {
        $notifyByid = $this->getById($id);
        $notifyByid->update(["read"  => 1]);
        return $notifyByid;
    }

    public function deleteNotification($id)
    {
        $notifyByid = $this->getById($id);
        $notifyByid->forceDelete();
        return $notifyByid;
    }
}

?>