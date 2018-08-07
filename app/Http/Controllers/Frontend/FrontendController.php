<?php namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Course;

/**
 * Class FrontendController
 * @package App\Http\Controllers
 */
class FrontendController extends Controller
{
    protected $courses;
    protected $cate;

    public function __construct(Course $courses, Category $cate)
    {
        $this->courses  =   $courses;
        $this->cate     =   $cate;
    }

    /**
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $data_course = $this->courses->with('user')
                                     ->where('cou_active', 1)
                                     ->orderby('courses.id','DESC')
                                     ->take(11)
                                     ->get();

        //lấy danh mục nổi bật mới nhất
        $cat_hot    = $this->cate->where('cat_active',1)
                                 ->where('hot',1)
                                 ->orderby('course_count','DESC')
                                 ->take(4)
                                 ->get();

        return view('frontend.'.config('app.id').'.index',compact('data_course','cat_hot'));
    }
}