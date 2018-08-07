<?php
/**
 * Created by PhpStorm.
 * User: hocvt
 * Date: 9/22/15
 * Time: 10:51
 */

return array(

    /*
    |--------------------------------------------------------------------------
    | Name of route
    |--------------------------------------------------------------------------
    |
    | Enter the routes name to enable dynamic imagecache manipulation.
    | This handle will define the first part of the URI:
    |
    | {route}/{template}/{filename}
    |
    | Examples: "images", "img/cache"
    |
    */

    'route' => 'images',

    /*
    |--------------------------------------------------------------------------
    | Storage paths
    |--------------------------------------------------------------------------
    |
    | The following paths will be searched for the image filename, submited
    | by URI.
    |
    | Define as many directories as you like.
    |
    */

    'paths' => array(
        public_path('images'),
        storage_path('images'),
        public_path('blogs'),
        storage_path('blogs')
    ),

    /*
    |--------------------------------------------------------------------------
    | Manipulation templates
    |--------------------------------------------------------------------------
    |
    | Here you may specify your own manipulation filter templates.
    | The keys of this array will define which templates
    | are available in the URI:
    |
    | {route}/{template}/{filename}
    |
    | The values of this array will define which filter class
    | will be applied, by its fully qualified name.
    |
    */

    'templates' => array(
        'small' => 'App\Core\ImageTemplate\Small',
        'medium' => 'App\Core\ImageTemplate\Medium',
        'large' => 'App\Core\ImageTemplate\Large',
        'cc_small' => 'App\Core\ImageTemplate\CourseCoverSmall',
        'cc_medium' => 'App\Core\ImageTemplate\CourseCoverMedium',
        'cc_large' => 'App\Core\ImageTemplate\CourseCoverLarge',
        'cc_video_cover' => 'App\Core\ImageTemplate\CourseCoverVideoCover',
        'ua_small'  => 'App\Core\ImageTemplate\UserAvatarSmall',
        'ua_medium'  => 'App\Core\ImageTemplate\UserAvatarMedium',
        'uc_medium' => 'App\Core\ImageTemplate\UserCoverMedium',
        'blog_large' => 'App\Core\ImageTemplate\BlogCoverLarge',
        'blog_medium' => 'App\Core\ImageTemplate\BlogCoverMedium',
        'blog_small' => 'App\Core\ImageTemplate\BlogCoverSmall',
        'cate_medium' => 'App\Core\ImageTemplate\CategoryAvatarMedium'
    ),

    /*
    |--------------------------------------------------------------------------
    | Image Cache Lifetime
    |--------------------------------------------------------------------------
    |
    | Lifetime in minutes of the images handled by the imagecache route.
    |
    */

    'lifetime' => 43200,

);
