<?php

namespace Modules\Core\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ShortcodeMiddleware
{
    /**
     * Intercept a response to replace shortcodes
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        // Get defined shortcodes from the config file
        $shotcodesDefinitions = config('asgard.core.shortcodes', []);

        foreach ($shotcodesDefinitions as $shortcodeName => $options) {

            // Search for shortcodes in the response's content
            if (preg_match("/\[$shortcodeName.*\]/", $response->getContent(), $shortcodes)) {
                foreach ($shortcodes as $shortcode) {

                    // Search for parameters
                    preg_match('/\w+=&quot;.*&quot;/', $shortcode, $rawParameters);

                    if (! empty($rawParameters)) {
                        $parameters = new Request();

                        // Parse every parameters and put them in a Request object
                        foreach ($rawParameters as $rawParameter) {
                            list($name, $value) = explode('=', $rawParameter);

                            $parameters->query->set($name, trim($value, '&quot;'));
                        }

                        if (array_key_exists('callback', $options)) {
                            // Call a function to interpret parameters and get the results as an array
                            $results = call_user_func($options['callback'], $parameters);
                        }
                    } else {
                        if (array_key_exists('callback', $options)) {
                            // Call a function to interpret parameters and get the results as an array
                            $results = call_user_func($options['callback']);
                        }
                    }

                    if (isset($options['view'])) {
                        $view = view($options['view'], compact('results'))->render();
                    } else {
                        // If there is no view configured, it assumes that the callback himself is rendering the view
                        $view = $results;
                    }

                    // Replace the shortcode by the corresponfing view and datas
                    $response->setContent(preg_replace("/\[$shortcodeName.*\]/", $view, $response->getContent()));
                }
            }
        }

        return $response;
    }

}
