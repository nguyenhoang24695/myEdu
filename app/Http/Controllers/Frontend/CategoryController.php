<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Category;
use App\Repositories\Frontend\Category\EloquentCategoryRepository;
use App\Repositories\Frontend\Course\EloquentCourseRepository;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Frontend\Category\CategoryContract;
use App\Repositories\Frontend\Course\CourseContract;


class CategoryController extends Controller
{
    /** @var EloquentCategoryRepository */
    private $categories;
    /** @var EloquentCourseRepository  */
    private $courses;

    public function __construct(CategoryContract $categories, CourseContract $courses)
    {
        $this->categories = $categories;
        $this->courses    = $courses;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id, Request $request)
    {

        $price    =  $request->get('price');
        $trend    =  $request->get('trend');

        //Áp dụng lọc
        $where     = ['cou_active' => 1];
        $id_cat_in = [];

        if($price == "cdesc"){
            $orderby['cou_price'] = "DESC";
        } elseif ($price == "casc") {
            $orderby['cou_price'] = "ASC";
        } elseif ($price == "cfree") {
            $orderby['id'] = "DESC";
            $where  = ['cou_price' => 0, 'cou_active' => 1];
        } else {
            $orderby     =  ['created_at' => 'desc'];
        }

        if($trend == "cviews"){
            $orderby['cou_views'] = "DESC";
        }

        //Phân trang lọc
        $appended    = ['trend' => $request->get('trend'),'price' => $request->get('price')];

        /** @var Category $category */

        if($id > 0){
            //Danh mục theo ID

            $breadcrumb  = true;
            $id_cat_in   = [$id];
            $category    = $this->categories->getById($id,['cat_active'=>1]);
            \SEO::setTitle($category->cat_title);

            if($category->parent_id > 0){
                $parent_info = $this->categories->getById($category->parent_id,['cat_active'=>1]);
                $children    = $parent_info->children()->get();
            } else {
                $children   = $category->children()->get();
                foreach ($children as $key => $value) {
                    $id_cat_in[] = $value->id;
                }
            }

        } else {

            //Tất cả danh mục
            $breadcrumb  = false;
            $category    = $this->categories->getAllWithCondition(['cat_active'=>1]);
            \SEO::setTitle("Tất cả danh mục");

            foreach ($category as $key => $value) {
                $id_cat_in[] = $value->id;

                if($value->parent_id == 0){
                    $children[] = $value;
                }
            }
            
            $category->id        = 0;
            $category->cat_title = "Tất cả danh mục";
        }
        
        $data_course = $this->courses->getCourseByCategoryId($where,$orderby,$id_cat_in);
        $data_course_hot = $this->courses->getHotCourseCategoryId($orderby, $id_cat_in);

        return view('frontend.category.index',compact('data_course','data_course_hot','category','appended','children','breadcrumb'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
