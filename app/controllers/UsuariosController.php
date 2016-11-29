<?php

class UsuariosController extends BaseController
{

    public function __construct()
    {
        $this->beforeFilter(function()
        {
            if (!$this->authorize())
            {
                return Redirect::to("/login");
            }

            if ($this->user->rol->nombre != "Administrador")
            {
                return Redirect::to("/taxis");
            }
        });
    }

    public function getIndex()
    {

        $this->layout->section = "Usuarios";
        $usuarios = VUsuario::where("status", "<>", "eliminado")->orderBy("id", "desc")->get();
        $roles = Rol::where("status", "<>", "eliminado")->orderBy("nombre", "asc")->get();

        $this->layout->content = View::make('usuarios.usuarios', array(
                'usuarios' => $usuarios,
                'roles' => $roles
        ));
    }

    public function details($id, $nombre)
    {

        $usuario = Usuario::find($id);
        if ($usuario != null)
        {
            $roles = Rol::where("status", "<>", "eliminado")->orderBy("nombre", "asc")->get();
            if (Input::hasFile('file') && Input::file('file')->isValid())
            {
                $ext = pathinfo(Input::file('file')->getClientOriginalName(), PATHINFO_EXTENSION);
                $newImage = AppFiles::saveFile(file_get_contents(Input::file('file')->getRealPath()), $ext, "user");
                $newImage = "user-" . $newImage;
                $response = array();
                $response['result'] = "error";
                $response['file'] = uploads_url($newImage);

                $usuario->foto = $newImage;
                $usuario->save();
                $response['result'] = "ok";

                return $response;
            }
            else if (Input::has("username"))
            {
                $usuario->nombre = Input::Get("nombre");
                $usuario->username = Input::Get("username");
                $usuario->id_rol = Input::Get("id_rol");
                $usuario->email = Input::Get("email");
                $usuario->status = (Input::Get("status") == "activo") ? "activo" : "inactivo";
                $usuario->save();

                $newPassword = trim(Input::get("password"));
                if (strlen($newPassword) > 2 && $newPassword != "********")
                {
                    $usuario->password = DB::raw("PASSWORD('{$newPassword}')");
                    $usuario->save();
                }
            }

            $this->layout->section = "Usuarios";
            $this->layout->content = View::make('usuarios.details', array(
                    'usuario' => $usuario,
                    'roles' => $roles
            ));
            return;
        }
        return Redirect::to("/sitios");
    }

    public function delete($id)
    {
        $usuario = Usuario::find($id);
        if ($usuario != null)
        {
            if ($usuario->id_rol == 5 || $usuario->id_rol == 4)
            {
                return Redirect::to("/usuarios");
            }
            else
            {
                $usuario->status = "eliminado";
                $usuario->save();
                $usuario->delete();
            }
        }
        return Redirect::to("/usuarios");
    }

    public function anyNuevo()
    {
        $usuario = new Usuario();
        if (Input::has("username"))
        {
            $usuario->username = Input::Get("username");
            $usuario->id_rol = Input::Get("id_rol");
            $usuario->nombre = $usuario->username;
            $usuario->email = $usuario->username . "setapp.mx";
            $newPassword = trim(Input::get("password"));
            $usuario->password = DB::raw("PASSWORD('{$newPassword}')");

            $usuario->save();
        }
        return Redirect::to("/usuarios");
    }

}
