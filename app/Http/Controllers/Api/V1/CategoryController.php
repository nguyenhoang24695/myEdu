<?php
/**
 * Created by PhpStorm.
 * User: hocvt
 * Date: 9/10/15
 * Time: 08:29
 */

namespace App\Http\Controllers\Api\V1;


use App\Models\Category;
use App\Repositories\Frontend\Category\CategoryContract;
use App\Repositories\Frontend\Course\CourseContract;
use App\Transformer\CategoryTransformer;
use App\Transformer\CourseTransformer;
use Illuminate\Http\Request;

class CategoryController extends BaseController
{
    protected $category;
    protected $course;
    /**
     * CategoryController constructor.
     */
    public function __construct(CategoryContract $category, CourseContract $course)
    {
        $this->category = $category;
        $this->courses  = $course;
    }

    public function getCategory()
    {
        $districts = $this->category->getAll();
        return $this->response->collection($districts, new CategoryTransformer());
    }

    public function search($kw)
    {

    }

    public function nestedList($root_id)
    {

    }

    public function getChild($root)
    {

        /** @var Category $root_cat */
        $root_cat = Category::find(intval($root));

        if (!$root) {
            abort(404, "Không tìm thấy danh mục");
        }
        \Cache::forget('child_cat_of_' . $root);
        $childs = \Cache::remember('child_cat_of_' . $root, 5, function () use ($root_cat) {
            $childs = $root_cat->descendants()->orderBy("lft", "asc")->get(["id", "cat_title", "depth"]);

            foreach ($childs as &$child) {
                $child['name'] = str_repeat("--", $child['depth'] - 1) . $child['cat_title'];
                $child['link'] = route('category.show', ['id' => $child->id, 'title' => str_slug($child->cat_title)]);
            }

            return $childs;
        });

        return response()->json($childs);

    }
}