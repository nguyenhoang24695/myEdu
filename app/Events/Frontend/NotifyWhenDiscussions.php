<?php
/**
 * Created by PhpStorm.
 * User: MyPC
 * Date: 21/04/2016
 * Time: 2:39 CH
 */

namespace App\Events\Frontend;

use App\Events\Event;
use Illuminate\Queue\SerializesModels;

class NotifyWhenDiscussions extends Event
{
    use SerializesModels;

    public $discussions;
    public $type;

    public function __construct($discussions,$type = 'comment')
    {
        $this->discussions    =   $discussions;
        $this->type           =   $type;
    }

}