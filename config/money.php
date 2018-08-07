<?php
/**
 * Created by PhpStorm.
 * User: hocvt
 * Date: 1/4/16
 * Time: 09:19
 *
 */

return [

    /*
    |--------------------------------------------------------------------------
    | Cấu hình công thanh toán baokim
    |--------------------------------------------------------------------------
    |
    */

    'gates' => [

        'bao_kim'  => [

            /*
            |-----------------
            | Tích hop card
            |-----------------
            */

            //Tài khoản đăng ký truy cập API.
            'http_user'       => env('BK_HTTP_USER',''),

            //Mật khẩu tài khoản đăng ký truy cập API
            'http_password'   => env('BK_HTTP_PASSWORD',''),

            //Mã website đăng ký
            'merchant_id'     => env('BK_MERCHANT_ID',''),
            

            //Tài khoản đăng ký sử dụng API.
            'api_username'    => env('BK_API_USERNAME',''),

            //Mật khẩu tài khoản đăng ký truy cập API
            'api_password'    => env('BK_API_PASSWORD',''),

            //Mật khẩu website
            'secure_code'     => env('BK_SECURE_CODE',''),

            /*
            |-----------------
            | Tích hop baokim pro
            |-----------------
            */

            'pro_private_key' => file_get_contents(base_path('server.key')),
            'pro_email'       => env('BK_PRO_EMAIL'),
            'pro_api_user'    => env('BK_PRO_API_USER'),
            'pro_api_pwd'     => env('BK_PRO_API_PWD'),
            'pro_merchant_id' => env("BK_PRO_MERCHANT_ID"),
            'pro_secure_pas'  => env('BK_PRO_SECURE_PAS'),
            'order_prefix'    => env('BK_ORDER_PREFIX', 'OID'),

            //List link api
            'card_url'        => 'https://www.baokim.vn/the-cao/restFul/send',
            'pro_payment_url' => 'https://www.baokim.vn',//
            'bpn_link'        => 'https://www.baokim.vn/bpn/verify',
            'pro_api_seller_info_endpoint' => '/payment/rest/payment_pro_api/get_seller_info',
            'pro_api_pay_by_card_endpoint' => '/payment/rest/payment_pro_api/pay_by_card',
            'pro_api_payment_endpoint'     => '/payment/order/version11',

        ],
        '1_pay'  => [

            /*
            |-----------------
            | Tích hop card
            |-----------------
            */

            //Mật khẩu truy cập.
            'access_key'       => env('1P_ACCESS_KEY',''),

            //Mật khẩu bí mật
            'secret'           => env('1P_SECRET',''),

            //Link api
            'card_url'        => 'https://api.1pay.vn/card-charging/v5/topup',
            'bank_url'        => 'http://api.1pay.vn/bank-charging/service'
        ]
    ],

    /*
    |--------------------------------------------------------------------------
    | Cấu hình riêng cho dự án ubclass
    |--------------------------------------------------------------------------
    |
    */

    'myedu' => [

        //Đối với tài khoản doanh thu cần chính xác cả id và email
        'revenue_account' => [
            'id'    => env("REVENUE_ACC_ID", 7),
            'email' => env("REVENUE_ACC_EMAIL", 'revenue@myedu.io')
        ],

        // mặc định dùng tk default, có thể chia ra theo nhà cung cấp : VIETTEL, VINA, MOBI, VTC, GATE để chia cổng thanh
        // toán hoặc các mức triết khấu khác nhau
        'card_account' => [
            'default' => [
                'email'    => 'nap_ngay@myedu.io',
                'gate'     => '1_pay',
                'discount' => 23
            ]
        ],

        /*
        =====================
        */

        'bank_card' => [
            'default' => [
                'email' => 'bao_kim_bank@myedu.io',
                'gate'  => '1_pay'
            ]
        ],

        /*
        =====================
        */

        'bank_exchange' => [

            //// tương ứng với danh sách bank card để nhận thanh toán, nếu không có thì ném lỗi
            'TCB' => [ 
                'email' => 'tcb_bank_exchange@myedu.com.vn',
                'gate'  => 'manual'
            ],
            'VCB' => [ // tương ứng với danh sách bank card để nhận thanh toán, nếu không có thì ném lỗi
                'email' => 'vcb_bank_exchange@myedu.com.vn',
                'gate'  => 'manual'
            ],
            'VPB' => [ // tương ứng với danh sách bank card để nhận thanh toán, nếu không có thì ném lỗi
                'email' => 'vpb_bank_exchange@myedu.com.vn',
                'gate'  => 'manual'
            ],
            'TPB' => [ // bên UB
                'email' => 'tpb_bank_exchange@myedu.com.vn',
                'gate'  => 'manual'
            ],
            'LVB' => [ // bên UB
                'email' => 'lvb_bank_exchange@myedu.com.vn',
                'gate'  => 'manual'
            ]
        ],

        /*
        =====================
        */

        'bank_cards' => [
            'TCB' => [
                'name'      => 'CÔNG TY CỔ PHẦN GIẢI PHÁP HỆ THỐNG THÔNG TIN ISS VIỆT NAM',
                'account'   => '115 219 321 71017',
                'logo'      => '/frontend/img/banks/tcb.png',
                'bank_name' => 'Ngân hàng TMCP Kỹ Thương Việt Nam',
                'agent'     => 'PGD Ngọc Khánh'
            ],
            'VCB' => [
                'name'      => '',
                'account'   => '',
                'logo'      => '/frontend/img/banks/vcb.png',
                'bank_name' => 'Ngân hàng TMCP Ngoại Thương Việt Nam',
                'agent'     => 'Thăng Long'
            ],
            'VPB' => [
                'name'      => '',
                'account'   => '',
                'logo'      => '/frontend/img/banks/vpb.png',
                'bank_name' => 'Ngân hàng TMCP Việt Nam Thịnh Vượng',
                'agent'     => ''
            ],
            'TPB' => [
                'name'      => '',
                'account'   => '',
                'logo'      => '/frontend/img/banks/tpb.png',
                'bank_name' => 'Ngân hàng TMCP Tiên Phong',
                'agent'     => 'Thăng Long'
            ],
            'LVB' => [
                'name'      => '',
                'account'   => '',
                'logo'      => '/frontend/img/banks/lvb.png',
                'bank_name' => 'Ngân hàng TMCP Liên Việt',
                'agent'     => 'Thủ Đô'
            ]
        ],

        /*
        =====================
        */

        // các nhà mạng hỗ trợ trên cả hệ thống, có thể các cổng khác nhau sẽ hỗ trợ thêm bớt khác nhau
        'validated_card_provider'        => ['mobifone', 'vinaphone', 'viettel'],
        // phan tram thu duoc cua giao vien khi ban 1 khoa hoc
        'course_price_percent_for_owner' => 40,// default 40
    ],

];