<?php
/**
 * Created by PhpStorm.
 * User: hocvt
 * Date: 9/4/15
 * Time: 09:10
 */

namespace App\Repositories\Backend\Category;

interface CategoryContract{

    /**
     * @param $id
     * @return mixed
     */
    public function findOrThrowException($id);

    /**
     * @param $per_page
     * @param int $status
     * @param string $order_by
     * @param string $sort
     * @return mixed
     */
    public function getCategoriesPaginated($per_page, $status = 1, $order_by = 'id', $sort = 'asc');

    /**
     * @param int $status
     * @return mixed
     */
    public function getTree($status = 1);

}