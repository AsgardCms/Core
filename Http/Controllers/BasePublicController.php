<?php namespace Modules\Core\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\App;

abstract class BasePublicController extends Controller
{
    /**
     * @var \Modules\Core\Contracts\Authentication
     */
    protected $auth;
    public $locale;

    public function __construct()
    {
        $this->locale = App::getLocale();
        $this->auth = app('Modules\Core\Contracts\Authentication');
        view()->share('currentUser', $this->auth->check());
    }

    protected function convertObjToArr($object)
    {
        if (!is_object($object) && !is_array($object)) {
            return $object;
        } elseif (is_object($object)) {
            echo "<pre>";
            die(var_dump($object->get()));

            $object = get_object_vars($object);
        }

        return array_map(array($this, 'convertObjToArr'), $object);
    }
}
