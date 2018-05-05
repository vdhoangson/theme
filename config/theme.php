<?php
/**
 * Package: vdhoangson\theme
 * Author: vdhoangson
 * Github: https://github.com/vdhoangson/theme
 * Web: vdhoangson.com
 */

return [

    /*
    |--------------------------------------------------------------------------
    | Themes path
    |--------------------------------------------------------------------------
    |
    | This path used for save the generated theme. This path also will added
    | automatically to list of scanned folders.
    |
    */
    'path' => public_path('themes'),

    /*
    |--------------------------------------------------------------------------
    | Default folder name
    |--------------------------------------------------------------------------
    */
    'baseDir' => 'themes',

    /*
    |--------------------------------------------------------------------------
    | Default backend folder name
    |--------------------------------------------------------------------------
    */
    'backend' => [
        'active' => 'default',
        'folder' => 'backend',
    ],

    /*
    |--------------------------------------------------------------------------
    | Default fontend folder name
    |--------------------------------------------------------------------------
    */
    'frontend' => [
        'active' => 'default',
        'folder' => 'frontend',
    ],

    /*
    |--------------------------------------------------------------------------
    | Theme config name and change log file name
    |--------------------------------------------------------------------------
    |
    | Here is the config for theme.json file and changelog
    | for version control status
    |
    */
    'config'     => [
        'name' => 'theme.json',
        'log' => 'log.json',
    ],

    /*
    |--------------------------------------------------------------------------
    | Themes folder structure
    |--------------------------------------------------------------------------
    |
    | Here you may update theme folder structure.
    |
    */
    'folders'    => [
        'assets'  => 'assets',
        'views'   => 'views',

        'css' => 'assets/css',
        'js'  => 'assets/js',
        'img' => 'assets/img',

        'layouts' => 'views/layouts',
    ]

];
