<?php

if (! function_exists('on_route')) {
    function on_route($route)
    {
        return Route::current() ? Route::current()->getName() == $route : false;
    }
}

if (! function_exists('locale')) {
    function locale($locale = null)
    {
        if (is_null($locale)) {
            return app()->getLocale();
        }

        app()->setLocale($locale);

        return app()->getLocale();
    }
}
