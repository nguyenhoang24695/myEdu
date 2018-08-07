<?php
/**
 * Created by PhpStorm.
 * User: hocvt
 * Date: 12/10/15
 * Time: 09:42
 */

namespace App\Core\Contracts;


interface SearchEngine
{
    /**
     * Kiểm tra xem index hiện tại đã có trong search engine chưa
     * @param $index
     * @return mixed
     */
    public function checkIndexExist($index);

    /**
     * Kiểm tra index hiện tại đã có chưa, nếu chưa có thì tạo ra. Cuối cùng có index trả về true, ko tạo được là false
     * @param $index
     * @param bool $re_create
     * @return mixed
     */
    public function forceExist($index, $re_create = false);

    /**
     * Tạo mới index, chú ý config của các engine khác nhau
     * @param $index
     * @param bool $checkExists
     * @return mixed
     */
    public function createIndex($index, $checkExists = false);

    /**
     * Tìm kiếm theo keyword, yêu cầu match bất kỳ từ nào trong keyword
     * @param $index
     * @param array $query
     * @return mixed
     */
    public function search($index, Array $query);

    /**
     * Tìm kiếm theo keyword, yêu cầu match tất cả từ trong keyword
     * @param $index
     * @param $keyword
     * @return mixed
     */
    public function searchAll($index, $keyword);

    /**
     * Thêm 1 record vào index
     * @param $index
     * @param $body
     * @param null $id truyền vào custom id hoặc search engine tự động thêm, chú ý đến khái niệm id của các record
     * trong các search engine sử dụng trong dự án
     * @return mixed
     */
    public function add($index, Array $body, $id = null);

    /**
     * Thêm nhiều record vào index
     * @param $index
     * @param array $body
     * @return mixed
     */
    public function addBulk($index, Array $body);

    /**
     * Thay thế 1 record bằng thông tin mới
     * @param $index
     * @param $id
     * @param $body gồm toàn bộ thông tin của record như khi thêm mới
     * @return mixed
     */
    public function replace($index, $id, $body);

    /**
     * Sửa thông tin của 1 hoặc nhiều record
     * @param $index
     * @param int|array $id
     * @param $body gồm các thông tin cần thay đổi
     * @return mixed
     */
    public function update($index, $id, $body);

    /**
     * Xóa 1 hoặc nhiều record khỏi index
     * @param $index
     * @param int|array $id
     * @return mixed
     */
    public function delete($index, $id);


    public function deleteIndex($index);

    public function getIndexInfo($index);

    public function createIndexInfo($index);
}