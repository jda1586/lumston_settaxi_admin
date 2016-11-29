<?php

class PropietariosController extends BaseController
{

    public function __construct()
    {
        $this->beforeFilter(function()
        {
            if(!$this->authorize())
            {
                return Redirect::to("/login");
            }
            
            if($this->user->rol->nombre != "Administrador")
            {
                return Redirect::to("/taxis");
            }
        });
    }

    public function getIndex()
    {
        $this->layout->section = "Propietarios";
        $propietarios = Propietario::where("status", "<>", "eliminado")->orderBy("id", "desc")->get();

        $this->layout->content = View::make('propietarios.propietarios', array(
                'propietarios' => $propietarios
        ));
    }

    public function details($id, $nombre)
    {
        $propietario = Propietario::find($id);
        if ($propietario != null)
        {
            if (Input::hasFile('file') && Input::file('file')->isValid() && Input::has("id"))
            {
                $ext = pathinfo(Input::file('file')->getClientOriginalName(), PATHINFO_EXTENSION);
                $newImage = AppFiles::saveFile(file_get_contents(Input::file('file')->getRealPath()), $ext, "user");
                $newImage = "user-" . $newImage;
                $response = array();
                $response['result'] = "error";
                $response['file'] = uploads_url($newImage);

                $propietario->foto = $newImage;
                $propietario->usuario->foto = $newImage;
                $propietario->usuario->save();
                $propietario->save();
                $response['result'] = "ok";
                return $response;
            }
            else if (Input::has("nombre"))
            {
                $propietario->nombre = Input::Get("nombre");
                $propietario->apellidos = Input::Get("apellidos");
                $propietario->telefono = Input::Get("telefono");
                $propietario->status = (Input::Get("status") == "activo") ? "activo" : "inactivo";
                $propietario->save();
            }
            else if (Input::has("username"))
            {
                $usuario = Usuario::find(Input::Get("id"));
                if ($usuario != null)
                {
                    $usuario->username = Input::get("username");
                    $usuario->email = Input::get("email");
                    $usuario->save();
                    $newPassword = trim(Input::get("password"));
                    if (strlen($newPassword) > 2 && $newPassword != "********")
                    {
                        $usuario->password = DB::raw("PASSWORD('{$newPassword}')");
                        $usuario->save();
                    }
                }
            }
            else if (Input::has("id_taxi"))
            {
                $taxi = Taxi::find(Input::get("id_taxi"));
                if ($taxi != null)
                {
                    $taxi->id_propietario = $propietario->id;
                    $taxi->save();
                }
            }

            $usuario = Usuario::where("status", "<>", "eliminado")->where("id", $propietario->id_usuario)->first();

            $taxis = Vtaxi::where("id_propietario", $propietario->id)->where("status", "<>", "eliminado")->orderBy("id", "desc")->get();
            $taxisTodos = Vtaxi::where("id_propietario", "<>", $propietario->id)->where("status", "<>", "eliminado")->orderBy("id", "desc")->get();

            $this->layout->section = "Propietarios";
            $this->layout->content = View::make('propietarios.details', array(
                    'propietario' => $propietario,
                    'usuario' => $usuario,
                    'taxis' => $taxis,
                    'taxistodos' => $taxisTodos
            ));
            return;
        }
        return Redirect::to("/sitios");
    }

    public function delete($id)
    {
        $propietario = Propietario::find($id);
        if ($propietario != null)
        {
            $propietario->usuario->status = "eliminado";
            $propietario->usuario->save();
            $propietario->usuario->delete();
            
            $propietario->status = "eliminado";
            $propietario->save();
            $propietario->delete();
        }
        return Redirect::to("/propietarios");
    }

    public function anyNuevo()
    {
        $propietario = new Propietario();
        if (Input::has("nombre"))
        {
            $propietario->nombre = Input::Get("nombre");
            $propietario->apellidos = Input::Get("apellidos");
            $propietario->save();

            if ($propietario->id > 0)
            {
                $usuario = new Usuario();
                $usuario->tipo = "propietario";
                $usuario->id_rol = 4;
                $usuario->username = "propietario" . $propietario->id;
                $usuario->email = "propietario" . $propietario->id . "@setapp.com";
                $usuario->save();

                if ($usuario->id > 0)
                {
                    $propietario->id_usuario = $usuario->id;
                    $propietario->save();
                }
            }
        }
        return Redirect::to("/propietarios");
    }

}
