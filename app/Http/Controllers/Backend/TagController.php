<?php
/**
 * Created by PhpStorm.
 * User: hocvt
 * Date: 11/24/15
 * Time: 14:49
 */

namespace App\Http\Controllers\Backend;


use App\Core\MySeacher;
use App\Core\TaggableUtils;
use App\Exceptions\GeneralException;
use App\Http\Controllers\Controller;
use Conner\Tagging\Contracts\TaggingUtility;
use Conner\Tagging\Model\Tag;
use Conner\Tagging\Model\Tagged;
use Conner\Tagging\Taggable;
use Elasticsearch\Common\Exceptions\NoNodesAvailableException;
use Illuminate\Http\Request;

class TagController extends Controller
{
    static $taggingUtility;

    function __construct()
    {
        static::$taggingUtility = app(TaggingUtility::class);
    }


    public function getIndex(Request $request){
        $keyword = $request->get('keyword', '');
        if($keyword == ''){
            $tags = Tag::orderBy('name', 'asc')->simplePaginate(50);
        }else{
            if(config('database.fulltext_search.enabled')){
                try{
                    $tags_searched = MySeacher::searchTag($keyword);
                    $tag_ids = [];
                    foreach($tags_searched['tags'] as $tag){
                        $tag_ids[] = $tag['id'];
                    }
                    $tags = Tag::whereIn('id', $tag_ids)->get();
                }catch (NoNodesAvailableException $ex){
                    throw new GeneralException("ERROR : " . $ex->getMessage());
                }
            }else{
                $tags = Tag::where('name', 'like', '%' . $keyword . '%')->limit('6')->get(['name']);
            }
        }

        javascript()->put([
            'edit_tag_link' => route('backend.tags.reslug'),
        ]);

        return view('backend.tags.index', ['tags' => $tags]);
    }

    public function reindex(){

    }

    public function reslug(Request $request)
    {
        $id = $request->get('id');
        /** @var Tag $tag */
        $tag = Tag::find($id);
        if(!$tag){
            abort(404);
        }

        $taggeds = Tagged::where('tag_slug', '=', $tag->slug)->orWhere('tag_name', '=', $tag->name);

        $tag_name = $tag->name;

        // check posted tag name
        $new_tag_name = $request->get('content', '');
        if($new_tag_name != ''){
            $new_slug = TaggableUtils::normalizer($new_tag_name);
            $new_name = $new_tag_name;
            if(TaggableUtils::normalizer($new_tag_name) != $tag->slug && auth()->user()->id != 1){
                return response()->json([
                    'success' => false,
                    'message' => 'Bạn chỉ có thể sửa dấu cho đúng tiếng việt, không thể thay cấu trúc từ khóa'
                ]);
            }
        }else{
            $new_slug = TaggableUtils::normalizer($tag->name);
            $new_name = TaggableUtils::displayer($tag->name);
        }

        // valid slug
        if(Tag::where('id', '<>', $tag->id)->where('slug', $new_slug)->exists()){
            return response()->json([
                'success' => false,
                'message' => 'Trùng slug, có thể từ khóa bị trùng, hãy tìm thử chuỗi không dấu sau "'
                    . $new_slug . '" và thực hiện thay đổi tag cho các bài học trong phần sửa thông tin',
            ]);
        }

        \DB::transaction(function () use($tag, $taggeds, $new_slug, $new_name){
            $tag->slug = $new_slug;
            $tag->count = $taggeds->count();
            $tag->name = $new_name;
            $tag->save();
            $taggeds->update(['tag_slug' => $new_slug, 'tag_name' => $new_name]);
        });

        if($request->ajax()){
            return response()->json(['success' => true, 'tag' => $tag->toArray(), 'message' => trans('common.saved')]);
        }else{
            return redirect()->back();
        }

    }

    public function tagDetail($id){
        /** @var Tag $tag */
        $tag = Tag::find($id);
        if(!$tag){
            abort(404);
        }

        $taggeds = Tagged::where('tag_slug', '=', $tag->slug)->with('taggable')->get();

        $taggable_util = new TaggableUtils();
        $taggable_util->getTaggables($tag->slug, 'App\Models\Course');

        return view('backend.tags.detail', ['tag' => $tag, 'taggables' => $taggable_util->taggables]);
    }
}