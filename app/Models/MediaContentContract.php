<?php
/**
 * Created by PhpStorm.
 * User: hocvt
 * Date: 9/30/15
 * Time: 14:36
 */

namespace App\Models;


interface MediaContentContract
{
    public function get_media_id();

    public function setTitle($title);
    public function getTitle();
    public function setSubTitle($sub_title);
    public function getSubTitle();
    public function setCreator($user_id);
    public function autoSave($bool = null);

    /**
     * @return string loại dữ liệu
     */
    public function get_media_type();

    public function get_media_class();

    /**
     * @return string ổ lưu trữ dữ liệu
     */
    public function get_media_disk();
    public function setMediaDisk($disk);

    /**
     * @return string đường dẫn lưu trữ từ thư mục gốc của ổ đĩa
     */
    public function get_media_path();
    public function setMediaPath($path);

    /**
     * @param $disk ổ đĩa đích
     * @param array $disk_only chỉ chuyển sang ổ đĩa đích nếu ổ đĩa trong nhóm này
     * @param array $disk_ignore không di chuyển nếu dữ liệu đang ở một trong những ổ đĩa này
     * @return bool true nếu di chuyển ok, false nếu di chuyển không thành công.
     */
    public function move_to_disk($disk, $new_path = '', $disk_only = [], $disk_ignore = []);

    /**
     * Trả về link đến thumbnail của media
     * @param $template
     * @return mixed
     */
    public function thumbnail_link($template = 'small');

    /**
     * Các media cần phải được gen thumbnail qua hàm này
     * @return mixed
     */
    public function gen_thumbnail();

    /**
     * Gen thumbnail, get file size, page number, ...
     * @return mixed
     */
    public function gen_info($regen = false, $save = true);

    /**
     * Thử xóa media khi cập nhật lecture, nhưng nếu media đó còn dùng ở 1 lecture khác thì sẽ không bị xóa
     * @param $id
     * @return mixed
     */
    public function removeWhenUpdateLecture($id);

    /**
     * Xóa cả các file liên quan
     * @return mixed
     */
    public function deletePlus();

    public function get_data_length();
}