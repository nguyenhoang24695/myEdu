<?php
/**
 * Created by PhpStorm.
 * User: hocvt
 * Date: 12/2/15
 * Time: 09:32
 */

return [
    'no_follow' => [
        'public_study_lecture_link' => true,
        'default_study_lecture_link' => true,
    ],
    'follow_accepted_domains' => [
//        'unibee.dev',
//        'unibee.com',
//        '123doc.org',
    ],
    'html_filters' => [// list profile with allowed tags
        'default'       => [
            'allowed' => '',// các thẻ được phép dùng, ví dụ ['a', 'b']
            'denied' => 'all',// các thẻ không được phép dùng, ví dụ ['a', 'h1']
            'nofollow' => true,// có gắn nofollow vào các link không, rule được quy định trong mục follow_accepted_domains
            'inline_css' => false,// có được sử dụng inline css không, cài đặt là false nếu muốn bỏ hết inline css
        ],
        'comment' => [
            'allowed' => ['b', 'i', 'u'],
            'denied' => '',
            'nofollow' => true,
            'inline_css' => false,
        ],
        'description'   => [
            'allowed' => '',
            'denied' => ['h1', 'h2'],
            'nofollow' => true,
            'inline_css' => true,
        ],
        'description_seo'   => [
            'allowed' => ['br', 'i', 'ul', 'ol', 'li'],
            'denied' => '',
            'nofollow' => true,
            'inline_css' => false,
        ],
        'br_only' => [
            'allowed' => 'br',
            'inline_css' => false,
            'denied' => ''
        ]

    ]
];