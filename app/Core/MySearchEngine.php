<?php
/**
 * Lớp này chỉ để khai báo những thức chúng của indexer và sercher, để sử dụng search engine xem MySearcher và MyIndexer
 * để sử dụng cho các trường hợp cần
 * User: hocvt
 * Date: 12/11/15
 * Time: 08:42
 */

namespace App\Core;


use App\Core\Contracts\SearchEngine;
use App\Core\SearchEngine\ElasticSearch;

class MySearchEngine
{
    //const TAG_INDEX = 'unibeedev_tagging.tag';
    //const TAGGABLE = 'unibeedev_taggable';

    //const COURSE    = 'unibeedev_search.course';
    //const USER      = 'unibeedev_search.user';

    const MAX_HIT = 100;

    /**
     * @return SearchEngine
     */
    public static function getClient(){
        if(config('database.fulltext_search.search_engine') == 'elastic'){
            return new ElasticSearch();
        }
    }

    public static function indexer($type = "tag"){
        switch($type) {
            case "tag":
                return config('app.id').'_tagging.tag';
                break;
            case "course":
                return config('app.id').'_search.course';
                break;
            case "user":
                return config('app.id').'_search.user';
                break;
        }
    }

}