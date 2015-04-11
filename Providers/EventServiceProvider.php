<?php namespace Modules\Core\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        'Modules\Setting\Events\SettingWasUpdated' => [
            'Modules\Core\Events\Handlers\UpdateGlobalLocales',
        ],
    ];
}
