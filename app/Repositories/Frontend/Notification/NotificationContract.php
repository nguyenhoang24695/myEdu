<?php

namespace App\Repositories\Frontend\Notification;

interface NotificationContract{

    public function create($attributes);

    public function getById($id,$condition=[]);

    public function isMarkReadNotification($id);

    public function deleteNotification($id);
}

?>