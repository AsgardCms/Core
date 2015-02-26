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
        'bootstrap.css' => Theme::url('css/vendor/bootstrap.min.css'),
        'font-awesome.css' => Theme::url('css/vendor/font-awesome.min.css'),
        'ionicons.css' => Theme::url('css/vendor/ionicons.min.css'),
        'alertify.core.css' => Theme::url('css/vendor/alertify/alertify.core.css'),
        'alertify.default.css' => Theme::url('css/vendor/alertify/alertify.default.css'),
        'dataTables.bootstrap.css' => Theme::url('css/vendor/datatables/dataTables.bootstrap.css'),
        'icheck.blue.css' => Theme::url('css/vendor/iCheck/flat/blue.css'),
        'AdminLTE.css' => Theme::url('css/AdminLTE.css'),
        // Javascript
        'jquery.js' => Theme::url('js/vendor/jquery.min.js'),
        'bootstrap.js' => Theme::url('js/vendor/bootstrap.min.js'),
        'mousetrap.js' => Theme::url('js/vendor/mousetrap.min.js'),
        'alertify.js' => Theme::url('js/vendor/alertify/alertify.js'),
        'icheck.js' => Theme::url('js/vendor/iCheck/icheck.min.js'),
        'jquery.dataTables.js' => Theme::url('js/vendor/datatables/jquery.dataTables.js'),
        'dataTables.bootstrap.js' => Theme::url('js/vendor/datatables/dataTables.bootstrap.js'),
        'jquery.slug.js' => Theme::url('js/vendor/jquery.slug.js'),
        'app.js' => Theme::url('js/app.js'),
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
           'ionicons.css',
           'alertify.core.css',
           'alertify.default.css',
           'dataTables.bootstrap.css',
           'AdminLTE.css',
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
