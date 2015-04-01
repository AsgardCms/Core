<?php

if (! function_exists('on_route')) {
    function on_route($route)
    {
        return Route::current()->getName() == $route;
    }
}
