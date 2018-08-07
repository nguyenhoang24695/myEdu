<?php
/**
 * Created by PhpStorm.
 * User: hocvt
 * Date: 1/5/16
 * Time: 11:38
 */
return [
    'bao_kim' => [
        'response' => [
            '200' => [
                'title' => 'Thành công',
                'description' => 'Giao dịch nạp thành công',
            ],
            '202' => [
                'title' => 'Thẻ trễ',
                'description' => 'Giao dịch chưa xác định được trạng thái thành công hay không! TimeOut',
            ],
            '450' => [
                'title' => 'Lỗi dữ liệu gửi lên',
                'description' => 'Nội dung mô tả lỗi dữ liệu gửi lên chưa đúng',
            ],
            '460' => [
                'title' => 'Lỗi kết quả trả về từ nhà mạng',
                'description' => 'Nội dung mô tả lỗi thẻ cào từ nhà mạng',
            ],
        ]
    ],
    'wallet_types' => [
        'primary' => 'Tài khoản doanh thu',
        'secondary' => 'Tài khoản mua khóa học',
    ],
    'wallet_types_short' => [
        'primary' => 'TK Doanh thu',
        'secondary' => 'TK Mua KH',
    ]
];