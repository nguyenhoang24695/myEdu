<?php
/**
 * Created by PhpStorm.
 * User: hocvt
 * Date: 12/10/15
 * Time: 09:42
 */

namespace App\Core;

use App\Core\Contracts\TaggableForIndex;
use App\Core\SearchEngine\TaggableIndexTrait;
use App\Core\SearchEngine\TagIndexTrait;
use Conner\Tagging\Model\Tag;

/**
 * Class MyIndexer
 * @package App\Core
 * @deprecated Không nên sử dụng nữa, sẽ được xóa sau khi sửa toàn bộ các code sử dụng class này. Sử dụng package link
 * @link https://github.com/elasticquent/Elasticquent
 */
class MyIndexer extends MySearchEngine
{
    use TaggableIndexTrait;
    use TagIndexTrait;

    public static function getIndexInfo($index='course'){
        try{
            $return = "";
            switch($index){
                case "course":
                    $return = self::getClient()->getIndexInfo(self::indexer('course'));
                    break;
                case "user":
                    $return = self::getClient()->getIndexInfo(self::indexer('user'));
                    break;
            }
        } catch (\Exception $ex){
            $return = "Không tìm thấy index UNIBEE.".strtoupper($index);
        }
        return $return;
    }

    public static function deleteIndexInfo($index='course'){
        switch($index){
            case "course":
                return self::getClient()->deleteIndex(self::indexer('course'));
                break;
            case "user":
                return self::getClient()->deleteIndex(self::indexer('user'));
                break;
        }
    }

    public static function createIndexInfo($index='course'){
        switch($index){
            case "course":
                return self::getClient()->createIndexInfo(self::indexer('course'));
                break;
            case "user":
                return self::getClient()->createIndexInfo(self::indexer('user'));
                break;
        }
    }

    /**
     * index Course.
     *
     * @return \Illuminate\Http\Response
     */
    public static function indexCourse($course){
        $body = [
            'name'      => $course->cou_title,
            'slug'      => $course->slug,
            'sub_title' => $course->cou_sub_title,
            'summary'   => strip_tags($course->cou_summary),
            'price'		=> (int) $course->cou_price,
            'user_id'   => (int) $course->cou_user_id,
            'cate_id'   => (int) $course->cou_cate_id,
            'active'	=> (int) $course->cou_active,
            'user_count'=> (int) $course->user_count
        ];
        $client = self::getClient();
        return $client->add(self::indexer('course'), $body, $course->id);
    }

    /**
     * index user.
     *
     * @return \Illuminate\Http\Response
     */
    public static function indexUser($user){
        $body = [
            'name'      => $user->name,
            'full_name' => $user->full_name,
            'slug'      => str_slug($user->name,'-'),
            'email'     => $user->email,
            'unit_name' => $user->unit_name,
            'phone'		=> $user->phone
        ];
        $client = self::getClient();
        return $client->add(self::indexer('user'), $body, $user->id);
    }
}