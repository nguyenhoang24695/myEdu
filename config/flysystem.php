<?php

/*
 * This file is part of Laravel Flysystem.
 *
 * (c) Graham Campbell <graham@alt-three.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

return [

    /*
    |--------------------------------------------------------------------------
    | Default Connection Name
    |--------------------------------------------------------------------------
    |
    | Here you may specify which of the connections below you wish to use as
    | your default connection for all work. Of course, you may use many
    | connections at once using the manager class.
    |
    */

    'default' => 'local',
    'default_video' => 'local',//vod_quochoc
    'default_document' => 'local',
    'default_audio' => 'local',
    /*
    |--------------------------------------------------------------------------
    | Flysystem Connections
    |--------------------------------------------------------------------------
    |
    | Here are each of the connections setup for your application. Examples of
    | configuring each supported driver is shown below. You can of course have
    | multiple connections per driver.
    |
    */

    'connections' => [

        'awss3' => [
            'driver'          => 'awss3',
            'key'             => 'your-key',
            'secret'          => 'your-secret',
            'bucket'          => 'your-bucket',
            'region'          => 'your-region',
            'version'         => 'latest',
            // 'bucket_endpoint' => false,
            // 'calculate_md5'   => true,
            // 'scheme'          => 'https',
            // 'endpoint'        => 'your-url',
            // 'prefix'          => 'your-prefix',
            // 'visibility'      => 'public',
            // 'eventable'       => true,
            // 'cache'           => 'foo'
        ],

        'azure' => [
            'driver'       => 'azure',
            'account-name' => 'your-account-name',
            'api-key'      => 'your-api-key',
            'container'    => 'your-container',
            // 'visibility'   => 'public',
            // 'eventable'    => true,
            // 'cache'        => 'foo'
        ],

        'copy' => [
            'driver'          => 'copy',
            'consumer-key'    => 'your-consumer-key',
            'consumer-secret' => 'your-consumer-secret',
            'access-token'    => 'your-access-token',
            'token-secret'    => 'your-token-secret',
            // 'prefix'          => 'your-prefix',
            // 'visibility'      => 'public',
            // 'eventable'       => true,
            // 'cache'           => 'foo'
        ],

        'dropbox' => [
            'driver'     => 'dropbox',
            'token'      => 'your-token',
            'app'        => 'your-app',
            // 'prefix'     => 'your-prefix',
            // 'visibility' => 'public',
            // 'eventable'  => true,
            // 'cache'      => 'foo'
        ],

        'ftp' => [
            'driver'     => 'ftp',
            'host'       => 'ftp.example.com',
            'port'       => 21,
            'username'   => 'your-username',
            'password'   => 'your-password',
            // 'root'       => '/path/to/root',
            // 'passive'    => true,
            // 'ssl'        => true,
            // 'timeout'    => 20,
            // 'visibility' => 'public',
            // 'eventable'  => true,
            // 'cache'      => 'foo'
        ],

        'gridfs' => [
            'driver'     => 'gridfs',
            'server'     => 'mongodb://localhost:27017',
            'database'   => 'your-database',
            // 'visibility' => 'public',
            // 'eventable'  => true,
            // 'cache'      => 'foo'
        ],

        'tmp' => [
            'driver'     => 'local',
            'path'       => storage_path('upload_tmp'),
            // 'visibility' => 'public',
            // 'eventable'  => true,
            // 'cache'      => 'foo'
        ],

        'local' => [
            'driver'     => 'local',
            'path'       => public_path(''),
            // 'visibility' => 'public',
            // 'eventable'  => true,
            // 'cache'      => 'foo'
            // Khai báo storage có hỗ trợ stream video/audio không
            'video_streaming_support' => true,
            // Khai báo hàm build link stream, đầu vào của hàm là path từ thư mục gốc, bản ghi(object) chứa video, user(object) hiện tại
            'get_video_stream_closure' => '\App\Core\MyStorage::local_stream',
            // Khai báo các kích thước có thể có của video,
            'video_resolutions' => ['_720p' => 'HD', '_360p' => 'SD'],
        ],

        'vod_myedu' => [
            'driver'     => 'local',
            'path'       =>  '/var/www/storage/vod_myeducomvn',
            // 'visibility' => 'public',
            // 'eventable'  => true,
            // 'cache'      => 'foo'
            // Khai báo storage có hỗ trợ stream video/audio không
            'video_streaming_support' => true,
            // Khai báo hàm build link stream, đầu vào của hàm là path từ thư mục gốc, bản ghi(object) chứa video, user(object) hiện tại
            'get_video_stream_closure' => '\App\Core\MyStorage::vod_ubclass_stream',
            // Khai báo các kích thước có thể có của video,
            'video_resolutions' => ['_720p' => 'HD', '_360p' => 'SD'],
        ],

        'public' => [
            'driver'     => 'local',
            'path'       => public_path('images'),
            // 'visibility' => 'public',
            // 'eventable'  => true,
            // 'cache'      => 'foo'
        ],

        'blog' => [
            'driver'     => 'local',
            'path'       => public_path('blogs'),
            // 'visibility' => 'public',
            // 'eventable'  => true,
            // 'cache'      => 'foo'
        ],

        'null' => [
            'driver'    => 'null',
            // 'eventable' => true,
            // 'cache'     => 'foo'
        ],

        'rackspace' => [
            'driver'     => 'rackspace',
            'endpoint'   => 'your-endpoint',
            'region'     => 'your-region',
            'username'   => 'your-username',
            'apiKey'     => 'your-api-key',
            'container'  => 'your-container',
            // 'internal'   => false,
            // 'visibility' => 'public',
            // 'eventable'  => true,
            // 'cache'      => 'foo'
        ],

        'replicate' => [
            'driver'     => 'replicate',
            'source'     => 'your-source-adapter',
            'replica'    => 'your-replica-adapter',
            // 'visibility' => 'public',
            // 'eventable'  => true,
            // 'cache'      => 'foo'
        ],

        'sftp' => [
            'driver'     => 'sftp',
            'host'       => 'sftp.example.com',
            'port'       => 22,
            'username'   => 'your-username',
            'password'   => 'your-password',
            // 'privateKey' => 'path/to/or/contents/of/privatekey',
            // 'root'       => '/path/to/root',
            // 'timeout'    => 20,
            // 'visibility' => 'public',
            // 'eventable'  => true,
            // 'cache'      => 'foo'
        ],

        'webdav' => [
            'driver'     => 'webdav',
            'baseUri'    => 'http://example.org/dav/',
            'userName'   => 'your-username',
            'password'   => 'your-password',
            // 'visibility' => 'public',
            // 'eventable'  => true,
            // 'cache'      => 'foo'
        ],

        'zip' => [
            'driver'     => 'zip',
            'path'       => storage_path('files.zip'),
            // 'visibility' => 'public',
            // 'eventable'  => true,
            // 'cache'      => 'foo'
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Flysystem Cache
    |--------------------------------------------------------------------------
    |
    | Here are each of the cache configurations setup for your application.
    | There are currently two drivers: illuminate and adapter. Examples of
    | configuration are included. You can of course have multiple connections
    | per driver as shown.
    |
    */

    'cache' => [

        'foo' => [
            'driver'    => 'illuminate',
            'connector' => null, // null means use default driver
            'key'       => 'foo',
            // 'ttl'       => 300
        ],

        'bar' => [
            'driver'    => 'illuminate',
            'connector' => 'redis', // config/cache.php
            'key'       => 'bar',
            'ttl'       => 600,
        ],

        'adapter' => [
            'driver'  => 'adapter',
            'adapter' => 'local', // as defined in connections
            'file'    => 'flysystem.json',
            'ttl'     => 600,
        ],

    ],

    /**
     * Other config
     */

    'max_upload_size' => 1024*1024*1024,
    'upload_exts_default' => ['mp4', 'mpeg4',
        'pdf', //'doc', 'docx', 'ppt', 'pptx', 'txt',
        'mp3',//'wav',
        'jpg', 'jpeg', 'bmp', 'gif', 'png',
    ],

    'max_size' => [
        'video' => 1024*1024*1024,
        'audio' => 50*1024*1024,
        'image' => 5*1024*1024,
        'document' => 50*1024*1024,
    ],

    'course_avatar_min_size' => [
        'width' => '800',
        'height' => '600',
    ],

    'exts' => [
        'video' => ['mp4', 'mpeg4'],
        'document' => ['pdf'],//, 'doc', 'docx', 'ppt', 'pptx', 'txt'],
        'audio' => ['mp3'],
        'image' => ['jpg', 'jpeg', 'bmp', 'gif', 'png']
    ],

];
