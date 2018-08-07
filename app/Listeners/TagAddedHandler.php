<?php
/**
 * Handle khi 1 khoa hoc duoc tag them 1 tag moi
 * User: hocvt
 * Date: 12/11/15
 * Time: 16:57
 */

namespace App\Listeners;


use App\Core\MyIndexer;
use Conner\Tagging\Events\TagAdded;
use Conner\Tagging\Model\Tag;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class TagAddedHandler implements ShouldQueue
{
    use InteractsWithQueue;


    /**
     * TagAddedHandler constructor.
     */
    public function __construct()
    {
    }

    public function handle(TagAdded $event)
    {

    }
}