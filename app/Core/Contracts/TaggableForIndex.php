<?php
/**
 * Interface này dùng để index cho các dữ liệu taggable, tức là có liên quan đến thẻ tag, có thể nói đây là những index
 * chính của hệ thống, hệ thống hiện tại dùng tag làm lõi kết nối các dữ liệu khác loại với nhau.
 * User: hocvt
 * Date: 12/23/15
 * Time: 09:25
 */

namespace App\Core\Contracts;


interface TaggableForIndex
{
    /**
     * Trả về type của dữ liệu khi index vào indexer, nếu dùng sphinx thì tên của index sẽ là taggable_index_prefix
     * kết hợp với dấu gạch dưới và sau đó là chuỗi trả về từ hàm này
     * @return string
     */
    public function indexType();

    /**
     * Các dữ liệu khác cần index
     * @return mixed
     */
    public function indexOther();

    /**
     * Trả về toàn bộ mảng cần dưa vào index
     * @return array
     */
    public function indexData($include_other = true);
}