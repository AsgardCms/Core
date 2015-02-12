<?php namespace Modules\Core\Http\Middleware;

use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Session\Store;
use Illuminate\Foundation\Application;
use Mcamara\LaravelLocalization\Middleware\LaravelLocalizationRedirectFilter;
use Modules\Core\Contracts\Authentication;

class LocalizationMiddleware extends LaravelLocalizationRedirectFilter {}