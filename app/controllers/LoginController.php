<?php

class LoginController extends Controller
{

    protected $layout = "layout.login";
    
    protected function setupLayout()
    {
        if (!is_null($this->layout))
        {
            $this->layout = View::make($this->layout);
        }
    }

    public function anyIndex()
    {
        $error = "";
        $username = "";
        $password = "";
        if (Input::has("logout"))
        {
            Session::put('username', "");
            Session::regenerate();
            return Redirect::to("/login");
        }
        if (Input::has("username") && Input::has("password"))
        {
            $username = Input::get("username");
            $password = Input::get("password");

            $user = Usuario::login($username, $password);
            if ($user != null)
            {
                if (in_array($user->rol->nombre, Config::get("app.allowedRoles")))
                {
                    Session::regenerate();
                    Session::put('user', serialize($user));
                    if($user->rol->id == 3)
                    {
                        return Redirect::to("/viajes");
                    }
                    return Redirect::to("/taxis");
                }
                else
                {
                    $error = "Usuario sin permiso en este sistema";
                }
            }
            else
            {
                $error = "Usuario o password inv&aacute;lidos";
            }
        }

        $this->layout->error = $error;
        $this->layout->content = View::make('login', array());
    }

}
