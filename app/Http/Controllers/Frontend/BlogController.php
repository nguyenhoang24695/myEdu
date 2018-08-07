<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Core\MyStorage;
use App\Http\Controllers\Controller;
use App\Http\Requests\Frontend\Blog\StoreBlogRequest;
use App\Http\Controllers\Api\V1\Resource\ResourceController;
use App\Repositories\Frontend\Blog\BlogContract;
use App\Repositories\Frontend\BlogCategories\BlogCategoriesContract;
use App\Http\Controllers\Frontend\Auth;
use Image;
use Input;

class BlogController extends Controller
{

    protected $blog;
    protected $blog_cate;

    //Domain server chứa ảnh hiển thị blog
    protected $http_path = "http://edus365.com";

    public function __construct(BlogContract $blog,BlogCategoriesContract $blog_cate)
    {
        $this->blog      = $blog;
        $this->blog_cate = $blog_cate;
        $this->http_path = url('/');//'http://'.$_SERVER['SERVER_NAME'];
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //Lấy danh sách blog phân trang
        $param     = ['blo_active' => ['operator' => '=', 'value' => 1]];
        $pageSize  = 20;
        $orderby   = ['created_at' => 'DESC'];
        $width     = ['user','category'];

        $blog      = $this->blog->getAllWithPaginate($param,$pageSize,$orderby,$width);

        //Lấy danh sách blog hot
        $param_hot = ['blo_active' => ['operator' => '=', 'value' => 1],
                      'hot'        => ['operator' => '=', 'value' => 1]];

        $blog_hot  = $this->blog->getAllWithPaginate($param_hot,4,$orderby);

        //Lấy blog xem nhiều
        $blog_view = $this->getBlogView();
        return view("frontend.blog.index",compact("blog","blog_view","blog_hot"));
    }

    /**
     * Display a listing category of the resource.
     * Select in (1,2,3)
     * @return \Illuminate\Http\Response
     */

    public function categories($id){

        $id_cat_in      = [$id];
        $param          = ['blo_active' => ['operator' => '=', 'value' => 1]];
        $orderby        = ['id'=>'DESC'];

        $category       = $this->blog_cate->getById($id,['blc_active'=>1]);
        $blog_cate_all  = $this->blog->getBlogByCategoryId($param,$orderby,$id_cat_in);
        $blog_view      = $this->getBlogView();
        
        return view("frontend.blog.categories",compact("blog_cate_all","blog_view","category"));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        \SEO::setTitle(trans('meta.blog.create'));
        //Lấy danh mục blog
        $blog_cate = $this->blog_cate->getAllWithCondition(['blc_active'=>1]);
        return view("frontend.blog.create",compact("blog_cate"));
    }

    /**
     * Show list the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function listing(Request $request)
    {
        \SEO::setTitle(trans('meta.dashboard.blog'));

        $blo_title = $request->get('search');
        $param     = ['blo_title'  => ['operator' => 'LIKE','value' => "%%$blo_title%%"],
                      'blo_user_id'=> ['operator' => '=', 'value' => auth()->user()->id]];
        $public    = $request->get('public');
        if($public == 'on'){
          $param     = ['blo_title'  => ['operator' => 'LIKE','value' => "%%$blo_title%%"],
                        'blo_user_id'=> ['operator' => '=', 'value' => auth()->user()->id],
                        'blo_active' => ['operator' => '=','value' => 1]];
          
        } elseif ($public == 'off') {
          $param     = ['blo_title'  => ['operator' => 'LIKE','value' => "%%$blo_title%%"],
                        'blo_user_id'=> ['operator' => '=', 'value' => auth()->user()->id],
                        'blo_active' => ['operator' => '=','value' => 0]];
        }

        $blog      = $this->blog->getAllWithPaginate($param);
        return view("frontend.blog.listing",compact('blog'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreBlogRequest $request,ResourceController $valid)
    {
        $blo_title   = $request->get("blo_title");
        $blo_summary = $request->get("blo_summary");
        $blo_content = $request->get("blo_content");
        $blo_cate    = $request->get("blo_cate");
        $redirect    = $request->get("redirect");
        $public      = $request->get("public");

        $data = $this->blog->create([
                                      'blo_title'    => $blo_title
                                    , 'blo_summary'  => $blo_summary
                                    , 'blo_content'  => $blo_content
                                    , 'blo_user_id'  => auth()->user()->id
                                    , 'blo_cate'     => $blo_cate
                                    , 'public'       => $public
                                    ]);

         if ($request->hasFile('blo_path')) {

            // valid file
            $file_uploaded = $request->file('blo_path');
            $valid_result = $valid->validUploadedFile($file_uploaded, 'image');
            if($valid_result['valid'] == false){
                return redirect('blog/create')->withFlashDanger($valid_result['message']);
            }

            //save new image
            $disk       = MyStorage::getDisk('blog');
            $filename   = $data->id.time() . '.' . $file_uploaded->getClientOriginalExtension();
            $full_path  = 'cover/'.$data->id.'/'.$filename;

            $saved = $disk->writeStream($full_path,
            Image::make($request->file('blo_path'))
                ->resize(1080, 1080, function ($constraint) {
                    $constraint->aspectRatio();
                })
                ->stream()
                ->detach());

            if($saved){

                // remove old image
                try{
                    $old_disk = MyStorage::getDisk($data->blo_disk);
                    if($old_disk && $data->blo_path != '' && $old_disk->has($data->blo_path)){
                        $old_disk->delete($data->blo_path);
                    }
                }catch (FileNotFoundException $e){
                    // do nothing
                }

                $saved = $this->blog->update(['blo_path' => $full_path, 'blo_disk' => 'blog'],['id' => $data->id,'blo_user_id' => auth()->user()->id]);

                if($saved){

                  if($redirect == 1){
                    return redirect('blog/listing')->withFlashSuccess("Đăng blog thành công");
                  } elseif ($redirect == 2) {
                    return redirect(route('blog.show',['id'=>$data->id,'title'=>str_slug($data->blo_title)]))->withFlashSuccess("Đăng blog thành công");
                  } else {
                    return redirect('blog/create')->withFlashSuccess("Đăng blog thành công");
                  }

                }
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //Lấy blog mới nhất
        $blog_new  = $this->getBlogNew();
        //Lấy blog lượt xem nhiều nhất
        $blog_view = $this->getBlogView();

        $param     = [];
        //Lấy chi tiết blog
        $blog      = $this->blog->getById($id,$param);

        //Nếu thành viên đăng nhập, và là người tạo blog thì cho xem luôn
        if (auth()->user() && auth()->user()->id == $blog->blo_user_id) {
          return view("frontend.blog.detail",compact('blog','blog_new','blog_view'));
        } else {
          $param     = ['blo_active'=>1];
          //Lấy chi tiết blog
          $blog      = $this->blog->getById($id,$param);
          return view("frontend.blog.detail",compact('blog','blog_new','blog_view'));
        }
    }

    function showProtected($username,$id){

        //Lấy blog mới nhất
        $blog_new  = $this->getBlogNew();
        //Lấy blog lượt xem nhiều nhất
        $blog_view = $this->getBlogView();
        $param     = [];
        //Lấy chi tiết blog
        $param     = ['public'=>['operator' => '=', 'value' => 2]];
        //Lấy chi tiết blog
        $blog      = $this->blog->getById($id,$param);
        return view("frontend.blog.detail",compact('blog','blog_new','blog_view'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $param    = ['id' => $id,'blo_user_id' => auth()->user()->id];
        $blog     = $this->blog->getById($id,$param);
        $content  = $this->replace_img_src($blog->blo_content,"..",$this->http_path);

        //Lấy danh mục blog
        $blog_cate = $this->blog_cate->getAllWithCondition(['blc_active'=>1]);

        return view("frontend.blog.edit",compact('blog','content','blog_cate'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id,ResourceController $valid)
    {
        $blo_title   = $request->get("blo_title");
        $blo_summary = $request->get("blo_summary");
        $blo_content = $request->get("blo_content");
        $blo_cate    = $request->get("blo_cate");
        $redirect    = $request->get("redirect");
        $public      = $request->get("public");

        $data        = $this->blog->getById($id);
        $content     = $this->replace_img_src($blo_content,$this->http_path,"..");

        $this->blog->update(["blo_title"    => $blo_title
                            ,"blo_summary"  => $blo_summary
                            ,"blo_content"  => $content
                            ,"blo_cate"     => $blo_cate
                            ,"public"       => $public],

                            ["id"           => $id
                            ,"blo_user_id"  => auth()->user()->id]);

        if ($request->hasFile('blo_path')) {

            // valid file
            $file_uploaded = $request->file('blo_path');
            $valid_result = $valid->validUploadedFile($file_uploaded, 'image');
            if($valid_result['valid'] == false){
                return redirect('blog/create')->withFlashDanger($valid_result['message']);
            }

            //save new image
            $disk       = MyStorage::getDisk('blog');
            $filename   = $data->id.time() . '.' . $file_uploaded->getClientOriginalExtension();
            $full_path  = 'cover/'.$data->id.'/'.$filename;

            $saved = $disk->writeStream($full_path,
            Image::make($request->file('blo_path'))
                ->resize(1080, 1080, function ($constraint) {
                    $constraint->aspectRatio();
                })
                ->stream()
                ->detach());

            if($saved){

                // remove old image
                try{
                    $old_disk = MyStorage::getDisk($data->blo_disk);
                    if($old_disk && $data->blo_path != '' && $old_disk->has($data->blo_path)){
                        $old_disk->delete($data->blo_path);
                    }
                }catch (FileNotFoundException $e){
                    // do nothing
                }

                $saved = $this->blog->update(['blo_path' => $full_path, 'blo_disk' => 'blog'],['id' => $data->id, 'blo_user_id' => auth()->user()->id]);

            }
        }

        if($redirect == 2){
          return redirect(route('blog.show',['id'=>$data->id,'title'=>str_slug($blo_title)]))->withFlashSuccess("Cập nhật thành công");
        } else {
          return redirect()->route('blog.listing')->withFlashSuccess('Cập nhật thành công');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->blog->deleteCondition(["id"           => $id
                                     ,"blo_user_id"  => auth()->user()->id]);

        return redirect()->route('blog.listing')->withFlashSuccess('Xóa bản ghi thành công');
    }

    public function dirPathBlog(){
        return date('Y/m/d');
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @param  ResourceController  $valid
     * Upload ảnh nội dung blog
     */
    public function saveimage(Request $request,ResourceController $valid){

        if(Input::file()){

            $file_uploaded = $request->file('file');
            $valid_result = $valid->validUploadedFile($file_uploaded, 'image');
            if($valid_result['valid'] == false){
                return $valid_result['message'];
            }

            //save new image
            $disk       = MyStorage::getDisk('blog');
            $filename   = str_random(20).time() . '.' . $file_uploaded->getClientOriginalExtension();
            $full_path  = 'static/'.$this->dirPathBlog().'/'.$filename;

            $saved = $disk->writeStream($full_path,
            Image::make($request->file('file'))
                ->resize(1080, 1080, function ($constraint) {
                    $constraint->aspectRatio();
                })
                ->stream()
                ->detach());
            if($saved){
                $path_show  =   $this->getPicStatic($full_path);
                return $path_show;
            }

        } else {
            return ' Uploading image failed.';
        }
    }

    /**
    * @param $condition,$limit
    * Lấy blog mới nhất theo limit
    **/
    public function getBlogNew(){
      $param     = ['blo_active' => 1];
      $orderby   = ['id' => 'DESC'];
      $limit     = 10;
      return $this->blog->getCondition($param,$orderby,$limit);
    }

    /**
    * @param $condition,$limit
    * Lấy blog xem nhiểu nhất
    **/
    public function getBlogView(){
      $param     = ['blo_active' => 1];
      $orderby   = ['blo_views' => 'DESC'];
      $limit     = 10;
      return $this->blog->getCondition($param,$orderby,$limit);
    }

    /**
    * @param $static_path;
    ** Lấy ảnh nội dung blog
    **/
    public function getPicStatic($static_path){
      return $this->http_path.'/blogs/'.$static_path;
    }

    /**
    * @param $content
    * Thay thế đường dẫn ảnh khi sửa blog để có thể hiển thị ảnh trong summernote
    * Hiện tại đang để ảnh vs đường dẫn src = '../imge/filename';
    * Cần đổi sang dạng src = http://domain/img/filename; thì trong sumernote mới hiển thị dc
    **/
    function replace_img_src($content,$find="",$rep="") {
        $doc = new \DOMDocument();
        libxml_use_internal_errors(true);
        $doc->loadHTML('<?xml encoding="UTF-8">'.$content);
        
        $tags = $doc->getElementsByTagName('img');
        foreach ($tags as $tag) {
            $old_src = $tag->getAttribute('src');
            $old_src = str_replace($find, "", $old_src);

            $new_src_url = $rep.$old_src;
            $tag->setAttribute('src', $new_src_url);
        }

        //Xóa bỏ html body khi xử lý xong
        $arr_find  = array('<html>', '</html>', '<body>', '</body>');
        $arr_rep   = array('', '', '', '');
        $data_save = str_replace($arr_find,$arr_rep,$doc->saveHTML($doc->documentElement));

        return $data_save;
    }

}
