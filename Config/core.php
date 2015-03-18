<?php

return [
   /*
   |--------------------------------------------------------------------------
   | The prefix that'll be used for the administration
   |--------------------------------------------------------------------------
   */
    'admin-prefix' => 'backend',

    /*
    |--------------------------------------------------------------------------
    | Location where your themes are located
    |--------------------------------------------------------------------------
    */
    'themes_path' => base_path() . '/Themes',

    /*
    |--------------------------------------------------------------------------
    | Which administration theme to use for the back end interface
    |--------------------------------------------------------------------------
    */
    'admin-theme' => 'AdminLTE',

   /*
   |--------------------------------------------------------------------------
   | Define which assets will be available through the asset manager
   |--------------------------------------------------------------------------
   | These assets are registered on the asset manager
   */
    'admin-assets' => [
        // Css
        'bootstrap.css' => Theme::url('vendor/bootstrap/dist/css/bootstrap.min.css'),
        'font-awesome.css' => Theme::url('vendor/font-awesome/css/font-awesome.min.css'),
        //'ionicons.css' => Theme::url('css/vendor/ionicons.min.css'),
        'alertify.core.css' => Theme::url('css/vendor/alertify/alertify.core.css'),
        'alertify.default.css' => Theme::url('css/vendor/alertify/alertify.default.css'),
        'dataTables.bootstrap.css' => Theme::url('css/vendor/datatables/dataTables.bootstrap.css'),
        'icheck.blue.css' => Theme::url('vendor/iCheck/skins/flat/blue.css'),
        'AdminLTE.css' => Theme::url('vendor/admin-lte/dist/css/AdminLTE.css'),
        'AdminLTE.all.skins.css' => Theme::url('vendor/admin-lte/dist/css/skins/_all-skins.min.css'),
        'asgard.css' => Theme::url('css/asgard.css'),
        // Javascript
        'jquery.js' => Theme::url('vendor/jquery/dist/jquery.min.js'),
        'bootstrap.js' => Theme::url('vendor/bootstrap/dist/js/bootstrap.min.js'),
        'mousetrap.js' => Theme::url('js/vendor/mousetrap.min.js'),
        'alertify.js' => Theme::url('js/vendor/alertify/alertify.js'),
        'icheck.js' => Theme::url('vendor/iCheck/icheck.min.js'),
        'jquery.dataTables.js' => Theme::url('js/vendor/datatables/jquery.dataTables.js'),
        'dataTables.bootstrap.js' => Theme::url('js/vendor/datatables/dataTables.bootstrap.js'),
        'jquery.slug.js' => Theme::url('js/vendor/jquery.slug.js'),
        'app.js' => Theme::url('vendor/admin-lte/dist/js/app.js'),
        'keypressAction.js' => Module::asset('core:js/keypressAction.js'),
        'ckeditor.js' => Theme::url('js/vendor/ckeditor/ckeditor.js'),
    ],

   /*
   |--------------------------------------------------------------------------
   | Define which default assets will always be included in your pages
   | through the asset pipeline
   |--------------------------------------------------------------------------
   */
   'admin-required-assets' => [
       'css' => [
           'bootstrap.css',
           'font-awesome.css',
           'alertify.core.css',
           'alertify.default.css',
           'dataTables.bootstrap.css',
           'AdminLTE.css',
           'AdminLTE.all.skins.css',
           'asgard.css',
       ],
       'js' => [
           'jquery.js',
           'bootstrap.js',
           'mousetrap.js',
           'alertify.js',
           'icheck.js',
           'jquery.dataTables.js',
           'dataTables.bootstrap.js',
           'jquery.slug.js',
           'keypressAction.js',
           'app.js',
       ],
   ],
];
