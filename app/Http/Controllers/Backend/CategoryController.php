<?php
/**
 * Created by PhpStorm.
 * User: hocvt
 * Date: 9/4/15
 * Time: 14:39
 */

namespace App\Http\Controllers\Backend;


use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Repositories\Backend\Category\CategoryContract;
use App\Http\Controllers\Api\V1\Resource\ResourceController;
use App\Core\MyStorage;
use Image;
use Input;

class CategoryController extends Controller
{

    /** @var CategoryContract $categories */
    private $categories;
    const LIST_INDENT = '===';

    /**
     * CategoryController constructor.
     * @param CategoryContract $categories
     */
    public function __construct(CategoryContract $categories)
    {
        $this->categories = $categories;
    }

    /**
     * Trang danh sách danh mục, nhận request để tìm kiếm
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $where = $this->getSearchFilter($request);
        $data['is_searching'] = count($where) > 0;

        javascript()->put([
            'admin_category_delete_confirm' => trans('admin.category.delete_confirm'),
            'common_delete' => trans('common.delete'),
            'common_cancel' => trans('common.cancel'),
            'common_yes' => trans('common.yes'),
            'is_searching' => $data['is_searching'],
            'list_indent'    => self::LIST_INDENT
        ]);
        $data['categories'] = Category::searching($where)->orderBy('lft', 'asc')->get();
        if(!$data['is_searching']){
            $data['tree_categories'] = Category::getNestedList('cat_title', null, $this::LIST_INDENT);
        }
        $data['total_categories'] = count($data['categories']);
        //dd($data['categories']);die();
        return view('backend.category.index', $data);
    }

    /**
     * Trang chi tiết thông tin 1 danh mục
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function detail($id){

        /** @var Category $category */
        $category = Category::find($id);

        if(!$category){
            abort(404);
        }

        $data['category'] = $category;
        $data['category_path'] = $category->ancestors()->get(['cat_title','id']);
        $data['category_sibling'] = $category->siblings()->get(['cat_title', 'id']);
        return view('backend.category.detail', $data);
    }

    /**
     * Tạo 1 danh mục mới. Mặc định, danh mục inactive sau khi tạo, trở về trang danh sách để kích hoạt
     * @param Request $request
     * @param null $cat_id
     * @return \Illuminate\View\View
     */
    public function create(Request $request,ResourceController $valid, $cat_id = null){

        $data['parent_id'] = $cat_id;
        $data['category_list'] = ['' => trans('common.root')] + Category::getNestedList('cat_title', 'id', self::LIST_INDENT);

        if($request->isMethod('post')){
            $this->validate($request, [
                'cat_title' => 'required',
            ]);
            $new_category = new Category();
            $new_category->cat_title = $request->input('cat_title');
            $new_category->hot       = $request->get('hot',0);
            if(intval($request->input('parent_id')) < 1){
                $new_category->parent_id = null;
            }else{
                $new_category->parent_id = $request->input('parent_id');
            }

            //Up ảnh đại diện
            if ($request->hasFile('avata_path')) {
                $file_uploaded = $request->file('avata_path');
                $valid_result = $valid->validUploadedFile($file_uploaded, 'image');
                if($valid_result['valid'] == false){
                    return redirect()->route('backend.category_index')->withFlashDanger($valid_result['message']);
                }

                $disk       = MyStorage::getDisk('public');
                $filename   = time() . '.' . $file_uploaded->getClientOriginalExtension();
                $full_path  = 'categories/'.$filename;

                $saved = $disk->writeStream($full_path,
                Image::make($request->file('avata_path'))
                    ->resize(1080, 1080, function ($constraint) {
                        $constraint->aspectRatio();
                    })
                    ->stream()
                    ->detach());

                if($saved){
                     $new_category->disk        = "public";
                     $new_category->avata_path  = $full_path;
                }

            }

            if($new_category->save()){
                //return redirect()->route('backend.category_index')->withFlashSuccess(trans('common.saved'));
            }
        }

        return view('backend.category.create', $data);
    }

    /**
     * Sửa thông tin 1 danh mục
     * @param Request $request
     * @param $id
     * @return \Illuminate\View\View
     */
    public function edit(Request $request,ResourceController $valid, $id){
        /** @var Category $category */
        $category = Category::find($id);

        if(!$category) {
            abort(404);
        }



        if($request->isMethod('post')){
            $this->validate($request, [
                'cat_title' => 'required',
            ]);
            $category->cat_title = $request->input('cat_title');
            $category->hot       = $request->get('hot',0);
            if(intval($request->input('parent_id')) < 1){
                $category->parent_id = null;
            }else{
                $category->parent_id = $request->input('parent_id');
            }

            //Up ảnh đại diện
            if ($request->hasFile('avata_path')) {
                $file_uploaded = $request->file('avata_path');
                $valid_result = $valid->validUploadedFile($file_uploaded, 'image');
                if($valid_result['valid'] == false){
                    return redirect()->route('backend.category_index')->withFlashDanger($valid_result['message']);
                }

                $disk       = MyStorage::getDisk('public');
                $filename   = time() . '.' . $file_uploaded->getClientOriginalExtension();
                $full_path  = 'categories/'.$filename;

                $saved = $disk->writeStream($full_path,
                Image::make($request->file('avata_path'))
                    ->resize(1080, 1080, function ($constraint) {
                        $constraint->aspectRatio();
                    })
                    ->stream()
                    ->detach());

                if($saved){
                    // remove old image
                    try{
                        $old_disk = MyStorage::getDisk($category->disk);
                        if($old_disk && $category->avata_path != '' && $old_disk->has($category->avata_path)){
                            $old_disk->delete($category->avata_path);
                        }
                    }catch (FileNotFoundException $e){
                        // do nothing
                    }

                     $category->disk        = "public";
                     $category->avata_path  = $full_path;
                }

            }

//            dd($category->update());

            if($category->update()){
                return redirect()->route('backend.category_index')->withFlashSuccess(trans('common.saved'));
            }
        }

        $data['category'] = $category;
        $data['category_list'] = ['' => trans('common.root')] + Category::getNestedList('cat_title', 'id', self::LIST_INDENT);

        return view('backend.category.edit', $data);
    }

    /**
     * Xóa 1 danh mục, không thể xóa danh mục còn danh mục con hoặc còn khóa học bên trong.
     * @param $id
     * @return mixed
     * @throws \Exception
     */
    public function delete($id){
        /** @var Category $category */
        $category = Category::find($id);

        if(!$category){
            abort(404);
        }

        // check course, include deleted course
        if(Course::whereCouCateId($id)->exists() || Category::whereParentId($id)->exists()){
            return redirect()->route('backend.category_index')->withFlashWarning(trans('common.delete_msgs.have_relative_data'));
        }

        $check_delete = $category->delete();

        if($check_delete){
            return redirect()->route('backend.category_index')->withFlashSuccess(trans('common.delete_msgs.success'));
        }else{
            return redirect()->route('backend.category_index')->withFlashWarning(trans('common.delete_msgs.false'));
        }
    }

    public function checkMoving($id, $direction){
        $result = ['move_title' => trans('common.move'),'success' => false, 'movable' => false, 'message' => 'Unknown error!!!'];
        /** @var Category $category */
        $category = Category::find($id);
        if(!$category){
            abort(404);
        }
        switch($direction){
            case 'up':
                /** @var Category $sibling */
                if($sibling = $category->getLeftSibling()){
                    $result['success'] = true;
                    $result['movable'] = true;
                    $result['message'] = trans('admin.category.move_up_msg',
                        ['cat1' => $category->cat_title, 'cat2' => $sibling->cat_title]);
                }else{
                    $result['message'] = trans('admin.category.cant_move');
                }
                break;
            case 'right':
                /** @var Category $sibling */
                if($sibling = $category->getLeftSibling()){
                    $result['success'] = true;
                    $result['movable'] = true;
                    $result['message'] = trans('admin.category.move_right_msg',
                        ['cat1' => $category->cat_title, 'cat2' => $sibling->cat_title]);
                }else{
                    $result['message'] = trans('admin.category.cant_move');
                }
                break;
            case 'down':
                /** @var Category $sibling */
                if($sibling = $category->getRightSibling()){
                    $result['success'] = true;
                    $result['movable'] = true;
                    $result['message'] = trans('admin.category.move_down_msg',
                        ['cat1' => $category->cat_title, 'cat2' => $sibling->cat_title]);
                }else{
                    $result['message'] = trans('admin.category.cant_move');
                }
                break;
            case 'left':
                /** @var Category $parent */
                if($parent = $category->parent){
                    $result['success'] = true;
                    $result['movable'] = true;
                    $result['message'] = trans('admin.category.move_left_msg',
                        ['cat1' => $category->cat_title, 'cat2' => $parent->cat_title]);
                }else{
                    $result['message'] = trans('admin.category.cant_move');
                }
                break;
            default:
                abort(404);
        }
        if($result['movable'] == true){
            $result['move_link'] = route('backend.category.moving', ['id' => $id, 'direction' => $direction]);
        }
        echo json_encode($result);
    }

    public function moving($id, $direction){
        $result = ['move_title' => trans('common.move'),
            'success' => false,
            'moved' => false,
            'message' => 'Unknown error!!!',
            'remove' => [],
            'append' => [],
            'after' => 0];
        /** @var Category $category */
        $category = Category::find($id);
        if(!$category){
            abort(404);
        }
        $children = $category->descendantsAndSelf()->get(['id']);
        $remove = [];
        foreach($children as $v){
            $remove[] = $v->id;
        }
        switch($direction){
            case 'up':
                /** @var Category $sibling */
                if($sibling = $category->getLeftSibling()){
                    $category->moveLeft();
                    $result['success'] = true;
                    $result['moved'] = true;
                }else{
                    $result['message'] = trans('admin.category.cant_move');
                }
                break;
            case 'right':
                /** @var Category $sibling */
                if($sibling = $category->getLeftSibling()){
                    $category->makeLastChildOf($sibling);
                    $result['success'] = true;
                    $result['moved'] = true;
                }else{
                    $result['message'] = trans('admin.category.cant_move');
                }
                break;
            case 'down':
                /** @var Category $sibling */
                if($sibling = $category->getRightSibling()){
                    $category->moveRight();
                    $result['success'] = true;
                    $result['moved'] = true;
                }else{
                    $result['message'] = trans('admin.category.cant_move');
                }
                break;
            case 'left':
                /** @var Category $parent */
                if($parent = $category->parent){
                    $category->makeSiblingOf($parent);
                    $result['success'] = true;
                    $result['moved'] = true;
                }else{
                    $result['message'] = trans('admin.category.cant_move');
                }
                break;
            default:
                abort(404);
        }
        if($result['moved']){
            $result['message'] = trans('common.saved');
            $result['remove'] = $remove;
            $children = $category->descendantsAndSelf()->orderBy('lft', 'desc')->get();
            $result['append'] = view('backend.category._cat_row',['categories'=>$children])->render();
            $previous = Category::where('lft', '<', $category->lft)->orderBy('lft', 'desc')->first(['id']);
            if($previous){
                $result['after'] = $previous->id;
            }
        }
        echo json_encode($result);
    }

    /**
     *
     */
    public function toggleStatus($id){
        /** @var Category $category */
        $category = Category::find($id);
        if(!$category){
            abort(404);
        }
        $category->cat_active = $category->cat_active == 0 ? 1 : 0;
        $category->save();
        echo json_encode([
            'success' => true,
            'status' => $category->cat_active,
            'id' => $id
        ]);
    }

    public function rebuildTree(){
//        $cats = Category::all()->toHierarchy();
//        dd($cats);die();
        //Category::rebuild(true);die("DONE");
    }

    private function getSearchFilter(Request $request){
        $where = [];

        $kw = $request->input('kw', '');

        if($kw != ''){
            $where[] = ['cat_title', 'like', '%' . $kw . '%'];
        }

        $is_active = $request->input('is_active', -1);

        if($is_active >= 0){
            $where[] = ['cat_active', '=', intval($is_active)];
        }

        return $where;
    }
}