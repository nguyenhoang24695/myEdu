<?php
/**
 * Created by PhpStorm.
 * User: hocvt
 * Date: 12/11/15
 * Time: 17:09
 */

namespace App\Listeners\Observer;


use App\Core\MyIndexer;
use App\Models\EsTag;
use Conner\Tagging\Model\Tag;

class TagObserver
{

    public function created(Tag $tag)
    {
        if(config('database.fulltext_search.enabled')){
            $esTag = EsTag::find($tag->id);
            $esTag->addToIndex();
        }
    }
    public function deleting(Tag $tag)
    {
        if(config('database.fulltext_search.enabled')){
            $esTag = EsTag::find($tag->id);
            $esTag->removeFromIndex();
        }
    }
    public function updated(Tag $tag)
    {
        if(config('database.fulltext_search.enabled')){
            $esTag = EsTag::find($tag->id);
            $esTag->updateIndex();
        }
    }
}