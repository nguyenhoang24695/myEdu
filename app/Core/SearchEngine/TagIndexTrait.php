<?php
/**
 * Created by PhpStorm.
 * User: hocvt
 * Date: 12/25/15
 * Time: 10:23
 */

namespace App\Core\SearchEngine;

use Conner\Tagging\Model\Tag;

trait TagIndexTrait
{
    /**
     * @param Tag $tag
     * @return bool
     * @throws \Exception
     */
    public static function indexTag(Tag $tag){
        $client = self::getClient();
        if(!$client->forceExist(self::indexer('tag'))){
            throw new \Exception(" Không tạo được index '" . self::indexer('tag') . "'");
        }
        $body = [
            'name' => $tag->name,
            'slug' => $tag->slug,
            'suggested' => intval($tag->suggest),
            'count' => empty($tag->count) ? 0 : $tag->count
        ];
        return $client->add(self::indexer('tag'), $body, $tag->id);
    }

    /**
     * @param $tags
     * @return int
     * @throws \Exception
     */
    public static function indexTags($tags){
        /** @var ElasticSearch $client */
        $client = self::getClient();
        if(!$client->forceExist(self::indexer('tag'))){
            throw new \Exception(" Không tạo được index '" . self::indexer('tag') . "'");
        }
        $bodies = [];
        foreach($tags as $tag){
            $bodies[] = [
                'name' => $tag->name,
                'slug' => $tag->slug,
                'suggested' => intval($tag->suggest),
                'count' => empty($tag->count) ? 0 : $tag->count,
                'id' => $tag->id
            ];
        }
        return $client->addBulk(self::indexer('tag'), $bodies);
    }

    /**
     * @param $tag
     * @return mixed
     * @throws \Exception
     */
    public static function updateTag($tag){
        $client = self::getClient();
        if(!$client->forceExist(self::indexer('tag'))){
            throw new \Exception(" Không tạo được index '" . self::indexer('tag') . "'");
        }
        $body = [
            'name' => $tag->name,
            'slug' => $tag->slug,
            'suggested' => intval($tag->suggest),
            'count' => empty($tag->count) ? 0 : $tag->count
        ];
        return $client->update(self::indexer('tag'), $tag->id, $body);
    }

    /**
     * @param $ids
     * @return mixed
     */
    public static function deleteTags($ids){
        return self::getClient()->delete(self::indexer('tag'), $ids);
    }

    public static function deleteTagIndex(){
        return self::getClient()->deleteIndex(self::indexer('tag'));
    }

    public static function getTagIndexInfo(){
        try{
            $return = self::getClient()->getIndexInfo(self::indexer('tag'));
        }catch (\Exception $ex){
            $return = "Không tìm thấy index TAGGING.TAG";
        }
        return $return;
    }
}