<?php namespace App\Transformer;
use App\Models\Category;
use Dingo\Api\Contract\Http\Request;
use League\Fractal\TransformerAbstract;

/**
 * Created by PhpStorm.
 * User: MyPC
 * Date: 08/01/2017
 * Time: 3:58 CH
 */
class CategoryTransformer extends TransformerAbstract
{
    /**
     * Turn this item object into a generic array
     *
     * @return array
     */
    public function transform(Category $category)
    {
        return [
            'id'          => (int) $category->id,
            'name'        => $category->cat_title,
            'order'       => $category->cat_order,
            'parent'      => $category->cat_parent_id,
            'active'      => $category->cat_active,
            'total_course'=> $category->course_count,
            'hot'         => $category->hot,
            'disk'        => $category->disk,
            'avata'       => $category->avata_path
        ];
    }
}