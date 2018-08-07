<?php

return [
    'meta'      => [
        /*
         * The default configurations to be used by the meta generator.
         */
        'defaults'  => [
          'title'       => env('APP_TITLE', 'Trang chủ - quochoc.vn - Website giáo dục trực tuyến chất lượng nhất Việt Nam'), // set false to total remove
          'description' => env('APP_DESCRIPTION','Website giáo dục trực tuyến myedu.com.vn'), // set false to total remove
          'separator'   => ' - ',
          'keywords'    => ['giáo dục','trực tuyến', env('APP_NAME')],
        ],

        /*
         * Webmaster tags are always added.
         */
        'webmaster_tags' => [
            'google'    => env('WEBMASTER_GOOGLE', null),
            'bing'      => null,
            'alexa'     => null,
            'pinterest' => null,
            'yandex'    => null
        ]
    ],
    
    'opengraph' => [
        /*
         * The default configurations to be used by the opengraph generator.
         */
        'defaults' => [
            'title'       => env('APP_NAME', 'myedu.com.vn'), // set false to total remove
            'description' => env('APP_DESCRIPTION','Website giáo dục trực tuyến myedu.com.vn'), // set false to total remove
            'url'         => env('APP_URL','http://myedu.com.vn'),
            'type'        => false,
            'site_name'   => false,
            'images'      => [],
        ]
    ],
    'twitter' => [
        /*
         * The default values to be used by the twitter cards generator.
         */
        'defaults' => [
          //'card'        => 'summary',
          //'site'        => '@LuizVinicius73',
        ]
    ],
    'socialize' => [
        'facebook' => [
            'app_id' => env('FACEBOOK_APP_ID'),
        ]
    ]
];
