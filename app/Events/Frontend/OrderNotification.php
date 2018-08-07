<?php
/**
 * Created by PhpStorm.
 * User: MyPC
 * Date: 18/04/2016
 * Time: 4:44 CH
 */

namespace App\Events\Frontend;

use App\Events\Event;
use Illuminate\Queue\SerializesModels;

class OrderNotification extends Event
{
    use SerializesModels;

    public $order;

    public function __construct($order)
    {
        $this->order    =   $order;
    }
}