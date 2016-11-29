<?php

//namespace Controllers\Api\V2;
use Facebook\FacebookSession;

class ApiV2SessionController extends ApiBaseController
{

    public function loginTaxista()
    {
        $response = array('codigo' => 2000);

        if (!Input::has('username') || !Input::has('password'))
        {
            return $this->resultDetails($response, 2000); // ERROR: parameters ---------------------
        }

        $usuario = Usuario::login(Input::get('username'), Input::get('password'));
        if ($usuario == null)
        {
            return $this->resultDetails($response, 2001); // No user ---------------------
        }
        if ($usuario->tipo != "taxista")
        {
            return $this->resultDetails($response, 2002); // No es taxista ---------------------
        }

        $taxista = $usuario->taxista;
        if ($taxista == null)
        {
            return $this->resultDetails($response, 2003);  // ERROR: No hay taxista asociado ---------------------
        }

        // Validar taxista y taxis
        if ($taxista->taxis->count() == 0)
        {
            return $this->resultDetails($response, 2004);  // ERROR: No hay taxis asignados ---------------------
        }

        if (!Input::has('token') || !Input::has('os'))
        {
            return $this->resultDetails($response, 2050); // ERROR: Parametros token invalidos ---------------------
        }

        // Registrar dispositivo / si no existe o si el dispositivo esta registrado con otro usuario
        Dispositivo::registrarDispositivo($usuario->id, Input::Get('token'), Input::Get('os'));

        // Crear sesion
        $newSession = Sesion::createSession($usuario, $taxista->taxis[0]->id);

        if ($newSession)
        {
            $response['sesion'] = $this->getDispatchableSession($newSession, $taxista);
            $response['codigo'] = 1000; // OK ---------------------
        }
        else
        {
            $response['codigo'] = 2005; // ERROR: Hubo un error al iniciar sesion ---------------------
        }

        return $this->resultDetails($response);
    }

    public function loginUsuario()
    {
        $response = array('codigo' => 2000);

        if (!Input::has('username') || !Input::has('password'))
        {
            return $this->resultDetails($response, 2000); // ERROR: parameters ---------------------
        }

        $usuario = Usuario::login(Input::get('username'), Input::get('password'));
        if ($usuario == null)
        {
            return $this->resultDetails($response, 2001); // No user ---------------------
        }
        if ($usuario->rol->nombre != "Usuario")
        {
            return $this->resultDetails($response, 2060); // No es usuario cliente ---------------------
        }

        if (!Input::has('token') || !Input::has('os'))
        {
            return $this->resultDetails($response, 2050); // ERROR: Parametros token invalidos ---------------------
        }

        // Registrar dispositivo / si no existe o si el dispositivo esta registrado con otro usuario
        Dispositivo::registrarDispositivo($usuario->id, Input::Get('token'), Input::Get('os'));

        // Crear sesion
        $newSession = Sesion::createSession($usuario, null);

        if ($newSession)
        {
            $response['sesion'] = $this->getDispatchableSession($newSession, null);
            $response['codigo'] = 1000; // OK ---------------------
        }
        else
        {
            $response['codigo'] = 2005; // ERROR: Hubo un error al iniciar sesion ---------------------
        }

        return $this->resultDetails($response);
    }

    public function facebookConnect()
    {
        $response = array('codigo' => 2000);

        if (!Input::has('fb_token'))
        {
            return $this->resultDetails($response, 2000); // ERROR: parameters ---------------------
        }

        if (!Input::has('token') || !Input::has('os'))
        {
            return $this->resultDetails($response, 2050); // ERROR: Parametros token invalidos ---------------------
        }


        // FB Connect
        //FacebookSession::setDefaultApplication(Config::get('app.fbAppId'), Config::get('app.fbAppSecret'));

        $fb = new Facebook\Facebook([
            'app_id' => Config::get('app.fbAppId'),
            'app_secret' => Config::get('app.fbAppSecret'),
            'default_graph_version' => 'v2.5',
        ]);
        $fb->setDefaultAccessToken(Input::get('fb_token'));

        $validToken = false;

        try
        {
            $responseFb = $fb->get('/me?locale=en_US&fields=name,email,first_name,last_name,id');
            $userNode = $responseFb->getGraphUser();
            
            $fbEmail = $userNode->getProperty("email");
            $emailInvalido = 0;
            if($fbEmail === FALSE || strlen($fbEmail) < 8)
            {
                $fbEmail = $userNode->getProperty("id") . "@facebook.com";
                $emailInvalido = 1;
            }

            // Get existing user
            $usuario = Usuario::loginFacebook($fbEmail, $userNode->getProperty("id"));
            if ($usuario == null)
            {
                // Create user
                // Crear usuario

                $usuario = new Usuario();
                $usuario->email = $fbEmail;
                $usuario->email_invalido = $emailInvalido;
                $usuario->username = $usuario->email;
                
                $newPassword = $userNode->getProperty("id");
                $usuario->password = DB::raw("PASSWORD('{$newPassword}')");
                $usuario->nombre = $userNode->getProperty("first_name");
                $usuario->apellidos = $userNode->getProperty("last_name");
                $usuario->id_rol = 7; // Rol usuario registro facebook
                $usuario->tipo_registro = "fb";
                //$usuario->foto = "http://graph.facebook.com/v2.5/" . $userNode->getProperty("id") . "/picture?width=200&height=200";
                $usuario->status = "activo";
                
                $newImage = AppFiles::saveFileFromFacebook($userNode->getProperty("id"));
                $usuario->foto = $newImage;
                
                $usuario->save();
            }

            if ($usuario != null && $usuario->id > 0)
            {
                $response['codigo'] = 1000; // OK ---------------------
                // Registrar dispositivo / si no existe o si el dispositivo esta registrado con otro usuario
                Dispositivo::registrarDispositivo($usuario->id, Input::Get('token'), Input::Get('os'));

                // Crear sesion
                $newSession = Sesion::createSession($usuario, null);

                if ($newSession)
                {
                    $response['sesion'] = $this->getDispatchableSession($newSession, null);
                    $response['codigo'] = 1000; // OK ---------------------
                }
                else
                {
                    $response['codigo'] = 2005; // ERROR: Hubo un error al iniciar sesion ---------------------
                }
            }

            ////echo $userNode->getProperty("email") . "<br><br>";
            //
            //http://graph.facebook.com/v2.5/938843812867946/picture?width=200&height=200
            //http://localhost:8006/api/v2/facebookConnect?os=android&token=32aas&fb_token=CAAHQSQz9QJABAIHRSknJfSDSC5uWGB3WGP05oU0h3D3UuFqe7KWj0yUk7hOrZBEhYZAhImjuzTpdPvZAytAGZCKDT37EvDp6hIKkYZAbo4K3UfUUGDpBdAS4l79gwxZCYDpUC4XZA4fZBT1nyEHSjdui29vcXdZARUFFmMBG1nu2ceu6Ni2yeVwviB58piYZBIN1ZAUAMln3IvItMpwieYQRq1q9M5ed8ncf98ZD
        }
        catch (Facebook\Exceptions\FacebookResponseException $e)
        {
            // Session not valid, Graph API returned an exception with the reason.
            return $this->resultDetails($response, 2056);
        }
        catch (Facebook\Exceptions\FacebookSDKException $e)
        {
            //echo 'Facebook SDK returned an error: ' . $e->getMessage();
            //exit;
            // Graph API returned info, but it may mismatch the current app or have expired.
            return $this->resultDetails($response, 2057);
        }

        return $this->resultDetails($response);
    }

    public function logout()
    {
        $response = array(
            'codigo' => 2000
        );
        if (Input::has('idSesion'))
        {
            $sesion = Sesion::getBySessionId(Input::get('idSesion'));
            if ($sesion != null)
            {
                $sesion->status = 'finalizada';
                $sesion->save();
                $response['codigo'] = 1000;
            }
            else
            {
                $response['codigo'] = 2006; // Sesion invalida o expirada
            }
        }
        else
        {
            $response['codigo'] = 2000; // Parametros invalidos
        }
        return $this->resultDetails($response);
    }

    private function getDispatchableSession($session, $taxista = null)
    {
        $sesion = array();
        $sesion['idSesion'] = $session->session_identifier;
        
        if(strpos($session->email, "@facebook.com"))
        {
            $session->email = "";
        }

        $sesion['usuario'] = array(
            'tipo' => $session->tipo,
            'username' => $session->username,
            'email' => $session->email,
            'foto' => $session->foto,
            'nombre' => $session->nombre,
            'apellidos' => $session->apellidos
        );

        if ($session->tipo == "Taxista")
        {
            $sitio = Sitio::find($taxista->taxis[0]->id_sitio);
            $sesion['id_taxi'] = $session->id_taxi;
            $sesion['taxi'] = $taxista->taxis[0];
            $sesion['taxista'] = array(
                'nombre' => $taxista->nombre,
                'apellidos' => $taxista->apellidos,
                'telefono' => $taxista->telefono,
                'licencia' => $taxista->licencia,
                'foto' => uploads_url($taxista->foto),
                'numero_economico' => $taxista->taxis[0]->numero_economico,
                'sitio_nombre' => $sitio->nombre,
                'sitio_numero' => $sitio->numero
            );
            $sesion['taxis'] = $taxista->taxis;
        }

        return $sesion;
    }

}
