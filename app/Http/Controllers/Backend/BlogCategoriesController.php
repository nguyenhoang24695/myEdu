<?php namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\Access\BlogCategories\CreateBlogcategoriesRequest;
use App\Repositories\Backend\BlogCategories\BlogCategoriesRepositoryContract;

class BlogCategoriesController extends Controller
{
    protected $blogcategories;

    public function __construct(BlogCategoriesRepositoryContract $blogcategories)
    {
        $this->blogcategories = $blogcategories;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $blc_title = $request->get('search');
        $param          = ['blc_title' => ['operator' => 'LIKE','value' => "%%$blc_title%%"], 'deleted_at' => ['operator' => '=','value' => NULL]];
        $blogcate     = $this->blogcategories->getAllWithPaginate($param);
        return view('backend.blogcategories.index',compact('blogcate'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $list  = $this->blogcategories->getAll();
        return view('backend.blogcategories.create',compact('list'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(CreateBlogcategoriesRequest $request)
    {
        $blc_title       =   $request->get('title');
        $blc_parent_id   =   $request->get('parent');
        $this->blogcategories->create(['blc_title' => $blc_title,'blc_parent_id' => $blc_parent_id]);
        return redirect()->route('blogcate.create')->withFlashSuccess('Thêm mới thành công');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        if($id > 0){
            $info   =   $this->blogcategories->getById($id);
            return $info->blc_title;
        } else {
            return "-";
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        $list       = $this->blogcategories->getAll();
        $catebyid   = $this->blogcategories->getById($id);
        return view('backend.blogcategories.edit',compact('list','catebyid'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(CreateBlogcategoriesRequest $request, $id)
    {
        $blc_title       =   $request->get('title');
        $blc_parent_id   =   $request->get('parent');
        $this->blogcategories->update(["blc_title" => $blc_title,"blc_parent_id" => $blc_parent_id],["id" => $id]);
        return redirect()->route('blogcate.index')->withFlashSuccess('Cập nhật thành công');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function active($id){
        $catebyid     =  $this->blogcategories->getById($id);
        $value      =  abs($catebyid->blc_active - 1);
        $this->blogcategories->update(["blc_active" => $value],["id" => $id]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        
        $this->blogcategories->deleteCondition(["id" => $id]);
        return redirect()->route('blogcate.index')->withFlashSuccess('Xóa bản ghi thành công');
    }
}
