<?php

return [
    'site-name' => [
        'description' => trans('core::settings.site-name'),
        'view' => 'text',
        'translatable' => true
    ],
    'site-description' => [
        'description' => trans('core::settings.site-description'),
        'view' => 'textarea',
        'translatable' => true
    ],
    'template' => [
        'description' => trans('core::settings.template'),
        'view' => 'core::fields.select-theme'
    ],
];
