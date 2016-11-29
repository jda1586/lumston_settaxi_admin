<?php

class BaseController extends Controller
{

    protected $layout = "layout.default";
    protected $user;

    /**
     * Setup the layout used by the controller.
     *
     * @return void
     */
    protected function authorize()
    {
        if (!Session::has("user"))
        {
            return false;
        }
        
        $user = unserialize(Session::get("user"));
        if ($user == null || gettype($user) != "object")
        {
            return false;
        }
        
        if(!in_array($user->rol->nombre, Config::get("app.allowedRoles")))
        {
            return false;
        }
        
        
        $this->user = $user;
        return true;
    }

    protected function setupLayout()
    {
        $navmenu = array(
            'Home' => '/',
            'Catálogo' => '/catalogo',
            'Proyectos' => '/proyectos',
            'Hablemos' => '/hablemos',
        );

        $requestUri = str_replace('/', '-', $_SERVER['REQUEST_URI']);
        $requestUri = $requestUri != '-' ? $requestUri : '-home';

        if (!is_null($this->layout))
        {
            $this->layout = View::make($this->layout, array(
                    'navmenu' => $navmenu,
                    'requestUri' => $requestUri,
                    'user' => $this->user
            ));
        }
    }

}
