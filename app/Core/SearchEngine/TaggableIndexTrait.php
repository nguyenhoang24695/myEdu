<?php
/**
 * Created by PhpStorm.
 * User: hocvt
 * Date: 12/24/15
 * Time: 15:21
 */

namespace App\Core\SearchEngine;


use App\Core\Contracts\SearchEngine;
use App\Core\Contracts\TaggableForIndex;
use App\Models\Course;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

trait TaggableIndexTrait
{
    public function getIndexTaggableInfo($type){
        if($type == 'course'){
            $type = Course::INDEX_TYPE;
        }
    }

    public static function indexTaggable(TaggableForIndex $taggableForIndex)
    {
        $_index = self::TAGGABLE;
        $_type = $taggableForIndex->indexType();
        $index = $_index . '.' . $_type;
        /** @var SearchEngine $client */
        $client = self::getClient();
        if($client->forceExist($index)){
            $client->add($index, $taggableForIndex->indexData(), $taggableForIndex->id);
        }else{
            throw new \Exception(" Không tạo được index '" . $_index . '.' . $_type . "'");
        }

    }
    public static function indexTaggables(Collection $taggableForIndexs)
    {
        if($taggableForIndexs->count() == 0){
            return true;
        }
        $first_el = $taggableForIndexs->first();
        if(!($first_el instanceof TaggableForIndex)){
            throw new \Exception("Phải truyền vào mảng Instance của TaggableForIndex");
        }

        $_index = self::TAGGABLE;
        $_type = $first_el->indexType();
        $index = $_index . '.' . $_type;

        /** @var SearchEngine $client */
        $client = self::getClient();
        if(!$client->forceExist($index)){
            throw new \Exception(" Không tạo được index '" . $_index . '.' . $_type . "'");
        }

        $bodies = [];
        foreach($taggableForIndexs as $taggableForIndex){
            /** @var TaggableForIndex $taggableForIndex */
            $bodies[] = $taggableForIndex->indexData() + ['id' => $taggableForIndex->id];
        }
        return $client->addBulk($index, $bodies);
    }

    /**
     * Cập nhật thông tin một bản ghi
     * @param TaggableForIndex $taggableForIndex
     */
    public static function updateTaggable(TaggableForIndex $taggableForIndex){
        $_index = self::TAGGABLE;
        $_type = $taggableForIndex->indexType();
        $index = $_index . '.' . $_type;
    }

    /**
     * Xóa một/một vài bản ghi của 1 type, hoặc xóa 1 type, hoặc xóa toàn bộ index taggable
     * @param null $type
     * @param array|null $ids
     * @return mixed
     */
    public static function deleteTaggableIndex($type = null, array $ids = null){
        /** @var SearchEngine $client */
        $client = self::getClient();
        if($type == null){
            return $client->deleteIndex(self::TAGGABLE);
        }else if($ids == null){
            return $client->deleteIndex(self::TAGGABLE . "." . $type);
        }else{
            return $client->delete(self::TAGGABLE . "." . $type, $ids);
        }
    }
}