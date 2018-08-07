<?php
/**
 * Lớp này chức các giá trị cố định mà ko thể thay đổi bằng config, tránh ảnh hưởng đến các dữ liệu đã sử dụng nó.
 * Có thể khai báo thêm khi hỗ trợ thêm tính năng nhưng các giá trị cũ cần đảm bảo không thay đổi trong quá trình nó
 * còn đang được sử dụng
 * User: hocvt
 * Date: 1/4/16
 * Time: 10:49
 */

namespace App\Core\Money\Utils;


class Constant
{

    // WALLET TYPES
    const ADMIN_WALLET = "admin";// tk dành cho các tính năng riêng
    const USER_WALLET = "user";//
    const SELLER_WALLET = "seller";
    const REVENUE_WALLET = "revenue";// tài khoản doanh thu toàn hệ thống

    // order status
    const PENDING_ORDER = 0;
    const APPROVED_ORDER = 1;
    const REVERTED_ORDER = 2;
    const REJECTED_ORDER = -1;
    public static $order_status = [
        'Hủy',
        'Chờ xử lý',
        'Hoàn thành',
        'Hoàn trả'
    ];

    // order types
    const RECHARGE_ORDER = 1;
    const EXCHANGE_ORDER = 2;
    const BUY_ORDER = 3;
    const OTHER_ORDER = 0;
    public static $order_types = [
        'Loại khác',
        'Nạp tiền',
        'Chuyển tiền',
        'Mua'
    ];

    // card status
    const ERROR_CARD = -1;
    const INIT_CARD = 0;
    const SUCCESS_CARD = 1;

    public static $mobile_card_status = [
        'Thẻ lỗi',
        'Thẻ chờ xử lý',
        'Thẻ nạp thành công'
    ];

    // Item type supported
    public static $item_types = [
        'App\Models\MobileCard' => 'Thẻ cào',
        'App\Models\Course' => 'Khóa học',
        'App\Models\BankPayment' => 'Ngân hàng'
    ] ;

    public static function orderStatus($status){
        return self::$order_status[$status + 1];
    }

    public static function cardStatus($status){
        return self::$mobile_card_status[$status + 1];
    }

    public static function sellableString($item_type){
        return array_get(self::$item_types, $item_type, $item_type);
    }

    public static function orderType($type){
        return self::$order_types[$type];
    }

}