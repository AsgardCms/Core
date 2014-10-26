<?php namespace Modules\Core\Http\Filters;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Session;
use Modules\Core\Contracts\Authentication;

class AdminFilter
{
    /**
     * @var Authentication
     */
    private $auth;

    public function __construct(Authentication $auth)
    {
        $this->auth = $auth;
    }

    public function filter()
    {
        // Check if the user is logged in
        if (!$this->auth->check()) {
            // Store the current uri in the session
            Session::put('url.intended', Request::url());

            // Redirect to the login page
            return Redirect::route('login');
        }

        // Check if the user has access to the dashboard page
        if ( ! $this->auth->hasAccess('dashboard.index'))
        {
            // Show the insufficient permissions page
            return App::abort(403);
        }
    }
}
