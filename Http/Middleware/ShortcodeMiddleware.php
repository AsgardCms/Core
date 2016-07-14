<?php

namespace Modules\Boats\Http\Middleware;

use Closure;

class ShortcodeMiddleware {

    /**
     * Intercept a response to replace shortcodes
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        $shotcodes = config('asgard.core.shortcodes');

        foreach ($shotcodes as $shortcode => $options) {
            $response->setContent(
                str_replace("[$shortcode]", view($options['view'], $options['data'])->render(), $response->getContent())
            );
        }

    	return $response;
    }
    
}
