<?php
/**
 * Created by PhpStorm.
 * User: hocvt
 * Date: 11/30/15
 * Time: 11:13
 */

namespace App\Http\Controllers\Api\V1\Resource;


use App\Core\MySeacher;
use Conner\Tagging\Model\Tag;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class TagController extends ResourceController
{
    public function search(Request $request)
    {
        $keyword = $request->get('keyword', '');
        $tags = [];
        if(config('database.fulltext_search.enabled')){
            $tags_searched = MySeacher::searchTag($keyword);
            foreach($tags_searched['tags'] as $tag){
                $tags[] = [
                    'name' => $tag['name'],
                ];
            }
        }else{
            $tags = Tag::where('name', 'like', '%' . $keyword . '%')->limit('6')->get(['name']);
        }

        return response()->json($tags);
    }
}