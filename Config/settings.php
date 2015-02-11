<?php

return [
    'site-name'        => [
        'description'  => 'asgard.core.settings.site-name',
        'view'         => 'text',
        'translatable' => true,
    ],
    'site-description' => [
        'description'  => 'asgard.core.settings.site-description',
        'view'         => 'textarea',
        'translatable' => true,
    ],
    'template'         => [
        'description' => 'asgard.core.settings.template',
        'view'        => 'asgard.core.fields.select-theme',
    ],
];
