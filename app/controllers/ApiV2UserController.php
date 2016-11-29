<?php

//namespace Controllers\Api\V2;

class ApiV2UserController extends ApiBaseController
{

    public function recuperarPassword()
    {
        $response = array('codigo' => 2000);
        if (!Input::has('email'))
        {
            return $this->resultDetails($response, 2000); // ERROR: parameters ---------------------
        }

        $email = Input::Get('email');
        // Validar formato
        if (!Validate::email($email))
        {
            return $this->resultDetails($response, 2051); // ERROR: email invalido
        }

        $user = Usuario::where("email", "=", $email)->where("id_rol", 6)->where("status", "activo")->first();
        if ($user == null)
        {
            return $this->resultDetails($response, 2077); // No hay email
        }

        // Generate recover key

        $user->recuperacion_codigo = DB::raw("UUID()");
        $user->recuperacion_status = "pendiente";
        $user->save();

        $userRestore = Usuario::find($user->id);
        if($userRestore == null || $userRestore->recuperacion_status != "pendiente" || !$userRestore->recuperacion_codigo)
        {
            return $this->resultDetails($response, 2077); // Error
        }

        // Send Email

        $config = Config::get("app.smtp");
        $mail = new PHPMailer();
        $mailFrom = $config['mailFrom'];

        $mail->isSMTP();
        $mail->Host = $config['host'];
        $mail->SMTPAuth = $config['SMTPAuth'];
        $mail->Username = $mailFrom;
        $mail->Password = $config['password'];
        $mail->SMTPSecure = $config['SMTPSecure'];
        $mail->Port = $config['port'];

        $mail->SMTPOptions = $config['SMTPOptions'];

        $mail->From = $mailFrom;
        $mail->FromName = 'SetApp.mx';
        $mail->addAddress($userRestore->email);
        foreach ($config['recipients'] as $recipientName => $recipientEmail)
        {
            $mail->addBCC($recipientEmail, $recipientName);
        }

        $mail->isHTML(true);

        $recoveryLink = $config['url'] . "recuperar_password.php?email=" . $userRestore->email .
            "&code=" . $userRestore->recuperacion_codigo;
        $body = "<html>
                        <head>
                            <title>SET Recuperar Cuenta</title>
                        </head>
                        <body style='width: 100%; height: auto; margin: 0; padding: 20px 100px; background: #cbcbcb;font-family:Arial,sans-serif;color:#002f8a;'>
                            <div style='width:600px;height:auto;background:#fff;overflow:hidden;'>
                                <div style='display:block;clear:both;width:100%;height:110px;background:#fcda45 url(" . $config['image'] . ") 20px 22px no-repeat;line-height:110px;text-indent:195px;font-size:30px;color:#fff;'>
                                    Recuperaci&oacute;n de Cuenta
                                </div>
                                <div style='display:block;clear:both;width:90%;height:auto;margin: 25px 5% 50px;overflow:hidden;'>
                                    <p style='font-size:16px;line-height:24px;color:#3369D1;font-weight:500;padding-bottom:10px;'>
                                        Estimado(a) <strong>" . $userRestore->nombre . "</strong>, hemos recibido una petici&oacute;n de cambio de contrase&ntilde;a de tu
                                            cuenta en SET App. Si has realizado esta solicitud, haz clic en el siguiente enlace: 
                                    </p>
                                    <p>
                                        <a href=\"" . $recoveryLink . "\" style='display:inline-block;width:auto;padding:0 20px;height:40px;line-height:40px;;background:#3369D1;color:#fff;font-size:15px;font-weight:600;text-transform:uppercase;text-align:center;text-decoration:none;border-radius:5px;'>
                                            Restablecer Contrase&ntilde;a</a>
                                    <p>
                                    <div style='display:inline-block;clear:both;width:95%;height:auto;padding: 32px 0 0;overflow:hidden;font-size: 14px;color:#979797;'>
                                        Este enlace ser&aacute; v&aacute;lido durante 24 horas o hasta que crees una contrase&ntilde;a nueva.<br /><br/>
                                        Si no solicitaste cambiar tu contrase&ntilde;a, por favor ignora este mensaje. Alg&uacute;n usuario
                                        puede haber remitido la solicitud por error. Ning&uacute;n cambio ser&aacute; realizado en tu cuenta. <br><br>
                                        <strong>Atentamente: El equipo de SET</strong>
                                    </div>
                                    <div style='display:inline-block;clear:both;width:95%;height:auto;padding: 30px 0 20px;overflow:hidden;font-size: 12px;color:#979797;'>
                                        Este email se ha generado autom&aacute;ticamente. Por favor, no conteste a esta direcci&oacute;n.
                                        Si tiene preguntas o necesita ayuda, por favor haga click en \"Contacte con nosotros\". 
                                    </div>
                                </div>
                            </div>
                        </body>
                    </html>";

        $mail->Subject = 'Recuperar Cuenta en SET App';
        $mail->Body = $body;

        if ($mail->send())
        {
            $response['codigo'] = 1000;
        }
        else
        {
            $response['codigo'] = 2078;
        }
        return $this->resultDetails($response);
    }

    public function registroUsuario()
    {
        $response = array('codigo' => 2000);

        if (!Input::has('nombre') || !Input::has('apellidos') || !Input::has('email') || !Input::has('password'))
        {
            return $this->resultDetails($response, 2000); // ERROR: parameters ---------------------
        }

        // Validar formato
        if (!Validate::email(Input::Get('email')))
        {
            return $this->resultDetails($response, 2051); // ERROR: email invalido
        }
        if (!Validate::name(Input::Get('nombre')))
        {
            return $this->resultDetails($response, 2052);
        }
        if (!Validate::password(Input::Get('password')))
        {
            return $this->resultDetails($response, 2053);
        }

        $newUser = new Usuario();
        $newUser->email = Input::Get('email');
        $newUser->username = Input::Get('email');
        $newPassword = Input::Get('password');
        $newUser->password = DB::raw("PASSWORD('{$newPassword}')");
        $newUser->nombre = Input::Get('nombre');
        $newUser->apellidos = Input::Get('apellidos');

        // Verificar si existe un usuario con el mismo email
        $existingUserEmail = Usuario::where("email", "like", $newUser->email)->first();
        if ($existingUserEmail != null)
        {
            return $this->resultDetails($response, 2054); // ERROR: existe un usuario con este email
        }

        // Crear usuario
        $newUser->id_rol = 6; // Rol usuario registro email
        $newUser->tipo_registro = "email";
        $newUser->foto = "default_user.png";
        $newUser->status = "activo";
        $newUser->save();

        if ($newUser->id > 0)
        {
            $response['codigo'] = 1000; // OK ---------------------
        }
        else
        {
            $response['codigo'] = 2055; // ERROR: Hubo un error al registrar usuario ---------------------
        }

        return $this->resultDetails($response);
    }

    public function actualizarUsuario()
    {
        $response = array('codigo' => 2000);

        if (!Input::has('idSesion') || !Input::has('nombre') || !Input::has('apellidos') || !Input::has('email'))
        {
            return $this->resultDetails($response, 2000); // ERROR: parameters ---------------------
        }

        // Validar formato email
        if (!Validate::email(Input::Get('email')))
        {
            return $this->resultDetails($response, 2051); // ERROR: email invalido
        }

        // Validar formato email
        if (!Validate::name(Input::Get('nombre')))
        {
            return $this->resultDetails($response, 2052); // ERROR: nombre invalido
        }

        $sesion = Sesion::getBySessionId(Input::get('idSesion'));
        if ($sesion == null)
        {
            return $this->resultDetails($response, 2006); // ERROR: Sesion invalida o expirada
        }

        $usuario = Usuario::find($sesion->id_usuario);
        if ($usuario == null || $usuario->status != "activo")
        {
            return $this->resultDetails($response, 2007); // ERROR: usuario no existe
        }

        // Validar si otro usuario tiene el mismo email
        $usuario->email = Input::Get('email');
        $existingUserEmail = Usuario::where("email", "like", $usuario->email)->where("id", "<>", $usuario->id)->first();
        if ($existingUserEmail != null)
        {
            return $this->resultDetails($response, 2054); // ERROR: existe un usuario con este email
        }

        if (Input::has('passwordActual') && Input::has('passwordNuevo'))
        {
            // Validar formato nuevo password
            if (!Validate::password(Input::Get("passwordNuevo")))
            {
                return $this->resultDetails($response, 2053); // ERROR: password con formato invalido
            }

            // Obtener usuario / con validacion de password
            $passwordActual = Input::Get('passwordActual');
            $user = Usuario::where("id", $sesion->id_usuario)
                    ->where('password', DB::raw("PASSWORD('{$passwordActual}')"))
                    ->where('status', 'activo')->first();

            if ($user == null)
            {
                return $this->resultDetails($response, 2061); // ERROR: el password actual es incorrecto
            }

            // Guardar nueva contrasena
            $passwordNuevo = Input::Get('passwordNuevo');
            $user->password = DB::raw("PASSWORD('{$passwordNuevo}')");

            $user->save();
        }

        // Guardar usuario
        $usuario->nombre = Input::Get('nombre');
        $usuario->apellidos = Input::Get('apellidos');
        $usuario->username = $usuario->email;
        $usuario->save();

        // Actualizar foto
        if (Input::hasFile('foto') && Input::file('foto')->isValid())
        {
            $ext = pathinfo(Input::file('foto')->getClientOriginalName(), PATHINFO_EXTENSION);
            $newImage = AppFiles::saveFile(file_get_contents(Input::file('foto')->getRealPath()), $ext, "user");
            $newImage = "user-" . $newImage;
            $response['foto'] = $newImage;

            $usuario->foto = $newImage;
            $usuario->save();
        }

        $response['codigo'] = 1000; // OK ---------------------
        return $this->resultDetails($response);
    }

    public function actualizarPassword()
    {
        $response = array('codigo' => 2000);

        if (!Input::has('idSesion') || !Input::has('passwordActual') || !Input::has('passwordNuevo'))
        {
            return $this->resultDetails($response, 2000); // ERROR: parameters ---------------------
        }

        $sesion = Sesion::getBySessionId(Input::get('idSesion'));
        if ($sesion == null)
        {
            return $this->resultDetails($response, 2006); // ERROR: Sesion invalida o expirada
        }

        // Validar formato nuevo password
        if (!Validate::password(Input::Get("passwordNuevo")))
        {
            return $this->resultDetails($response, 2053); // ERROR: password con formato invalido
        }

        // Obtener usuario / con validacion de password
        $passwordActual = Input::Get('passwordActual');
        $user = Usuario::where("id", $sesion->id_usuario)
                ->where('password', DB::raw("PASSWORD('{$passwordActual}')"))
                ->where('status', 'activo')->first();

        if ($user == null)
        {
            return $this->resultDetails($response, 2061); // ERROR: el password actual es incorrecto
        }

        // Guardar nueva contrasena
        $passwordNuevo = Input::Get('passwordNuevo');
        $user->password = DB::raw("PASSWORD('{$passwordNuevo}')");

        $user->save();

        $response['codigo'] = 1000; // OK ---------------------
        return $this->resultDetails($response);
    }

}
