<?php namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Repositories\Backend\Blog\BlogRepositoryContract;

class BlogController extends Controller
{
    protected $blog;

    public function __construct(BlogRepositoryContract $blog)
    {
        $this->blog = $blog;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index($module,Request $request)
    {
        $blo_title = $request->get('search');
        $param     = ['blo_title' => ['operator' => 'LIKE','value' => "%%$blo_title%%"]];

        if($module   == "list"){
            $blog    = $this->blog->getAllWithPaginate($param);
        } elseif ($module == "pending") {
            $param     = ['blo_title' => ['operator' => 'LIKE','value' => "%%$blo_title%%"],
                          'public'    => ['operator' => '=','value' => 1],
                          'blo_active'=> ['operator' => '=','value' => 0]];
            $blog    = $this->blog->getAllWithPaginate($param);
        } elseif ($module == "active") {
            $param     = ['blo_title' => ['operator' => 'LIKE','value' => "%%$blo_title%%"],
                          'blo_active'=> ['operator' => '=','value' => 1]];
            $blog    = $this->blog->getAllWithPaginate($param);
        } elseif ($module == "delete") {
            $blog    = $this->blog->getAllWithOnlyTrashedPaginate($param);
        } else {
            $blog    = $this->blog->getAllWithPaginate($param);
        }

        return view('backend.blog.index',compact('blog','module'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function active($id){
        $blogbyid   =  $this->blog->getById($id);
        $value      =  abs($blogbyid->blo_active - 1);
        $this->blog->update(["blo_active" => $value],["id" => $id]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function hot($id){
        $blogbyid   =  $this->blog->getById($id);
        $value      =  abs($blogbyid->hot - 1);
        $this->blog->update(["hot" => $value],["id" => $id]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        $this->blog->deleteCondition(["id" => $id]);
        return redirect()->route('blog.index')->withFlashSuccess('Xóa bản ghi thành công');
    }
}
