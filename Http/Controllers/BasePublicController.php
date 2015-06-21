<?php namespace Modules\Core\Http\Controllers;

use Illuminate\Routing\Controller;

abstract class BasePublicController extends Controller
{
    /**
     * @var \Modules\Core\Contracts\Authentication
     */
    private $auth;

    public function __construct()
    {
        $this->auth = app('Modules\Core\Contracts\Authentication');
        view()->share('currentUser', $this->auth->check());
    }
}
