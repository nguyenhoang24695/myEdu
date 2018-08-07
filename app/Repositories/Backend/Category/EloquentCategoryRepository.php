<?php
/**
 * Created by PhpStorm.
 * User: hocvt
 * Date: 9/4/15
 * Time: 09:11
 */
namespace App\Repositories\Backend\Category;

use App\Exceptions\NotFoundRecordException;
use App\Models\Category;


class EloquentCategoryRepository implements CategoryContract{



    /**
     * EloquentCategory constructor.
     */
    public function __construct()
    {

    }

    /**
     * Lấy thông tin category theo ID
     * @param $id
     * @return mixed
     * @throws NotFoundRecordException
     */
    public function findOrThrowException($id)
    {
        $category = Category::find($id);
        if(!is_null($category)){
            return $category;
        }
        throw new NotFoundRecordException('Category');
    }

    /**
     * @param $per_page
     * @param int $status
     * @param string $order_by
     * @param string $sort
     * @return mixed
     */
    public function getCategoriesPaginated($per_page, $status = 1, $order_by = 'id', $sort = 'asc')
    {
        // TODO: Implement getCategoriesPaginated() method.
        return Category::where('cat_active','=',$status)->orderBy($order_by, $sort)->paginate($per_page);
    }

    public function getTree($status = 1)
    {
        // TODO: Implement getTree() method.
        if($status != null){
            return Category::where("cat_active", "=", $status)->get()->toHierarchy();
        }else{
            return Category::all()->toHierarchy();
        }


    }

    private function getNextRow(array $current_row, $status = 1){

    }


}