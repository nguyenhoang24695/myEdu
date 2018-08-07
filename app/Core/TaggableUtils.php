<?php
/**
 * Created by PhpStorm.
 * User: hocvt
 * Date: 12/8/15
 * Time: 11:39
 */

namespace App\Core;


use App\Exceptions\TaggableNotSupported;
use App\Models\Course;
use App\Models\TaggableContract;
use App\Models\UniTaggableContract;
use Conner\Tagging\Contracts\TaggingUtility;
use Conner\Tagging\Model\Tagged;
use Conner\Tagging\Taggable;

class TaggableUtils
{

    public $taggables;

    private $template = 'backend';
    private $limit = 10;

    /**
     * @return string
     */
    public function getTemplate()
    {
        return $this->template;
    }

    /**
     * @param string $template
     */
    public function setTemplate($template)
    {
        $this->template = $template;
    }

    /**
     * Lấy ra 10 bản ghi tương ứng với taggable class được chọn
     * @param $tag_slug
     * @param $taggable_class
     * @throws TaggableNotSupported
     */
    public function getTaggables($tag_slug, $taggable_class){
//        \Log::alert($tag_slug);
//        \Log::alert($taggable_class);
        $taggable_classes = config('tagging.taggables');

        if(!in_array($taggable_class, $taggable_classes)){
            throw new TaggableNotSupported();
        }

        $taggable_ids = Tagged::where('tag_slug', '=', $tag_slug)
            ->where('taggable_type', '=', $taggable_class)
            ->get(['id', 'taggable_id']);

        $taggable_class = "\\" . $taggable_class;

        $array_ids = $taggable_ids->map(function($a){return $a->taggable_id;});

        $this->taggables = $taggable_class::whereIn('id', $array_ids)->get();
    }


    public static function renderTaggableToView(UniTaggableContract $taggable, $template = 'backend'){
        return view($template . '.includes.taggables',
            ['title' => $taggable->getTitle(),
                'sub_title' => $taggable->getSubtitle(),
                'thumbnail' => $taggable->getThumbnail(),
                'link' => $taggable->getLink(),
            ])->render();
    }

    /**
     * Sử dụng để thực hiện hàm biến đổi chuỗi theo đúng cài đặt của tính năng tagging
     * @param $string
     * @return mixed
     */
    public static function displayer($string)
    {
        $displayer = config('tagging.displayer');
        $displayer = empty($displayer) ? '\Illuminate\Support\Str::title' : $displayer;
        return call_user_func($displayer, $string);
    }

    /**
     * Sử dụng để thực hiện hàm biến đổi chuỗi thành slug theo đúng cài đặt của tính năng tagging
     * @param $string
     * @return mixed
     */
    public static function normalizer($string)
    {
        $taggingUtility = app(TaggingUtility::class);
        $normalizer = config('tagging.normalizer');
        $normalizer = $normalizer ?: [$taggingUtility, 'slug'];
        return call_user_func($normalizer, $string);
    }
}