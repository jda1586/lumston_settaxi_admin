<?php

class TaxistasController extends BaseController
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
        $this->layout->section = "Taxistas";
        $taxistas = Taxista::where("status", "<>", "eliminado")->orderBy("id", "desc")->get();

        $this->layout->content = View::make('taxistas.taxistas', array(
                'taxistas' => $taxistas
        ));
    }

    public function details($id, $nombre)
    {
        $taxista = Taxista::find($id);
        if ($taxista != null)
        {
            if (Input::hasFile('file') && Input::file('file')->isValid() && Input::has("id"))
            {
                $ext = pathinfo(Input::file('file')->getClientOriginalName(), PATHINFO_EXTENSION);
                $newImage = AppFiles::saveFile(file_get_contents(Input::file('file')->getRealPath()), $ext, "user");
                $newImage = "user-" . $newImage;
                $response = array();
                $response['result'] = "error";
                $response['file'] = uploads_url($newImage);
                
                $taxista = Taxista::find(Input::Get("id"));
                if($taxista != null)
                {
                    $taxista->foto = $newImage;
                    $taxista->usuario->foto = $newImage;
                    $taxista->usuario->save();
                    $taxista->save();
                    $response['result'] = "ok";
                }
                
                return $response;
            }
            else if (Input::has("nombre"))
            {
                $taxista->nombre = Input::Get("nombre");
                $taxista->apellidos = Input::Get("apellidos");
                $taxista->telefono = Input::Get("telefono");
                $taxista->licencia = Input::Get("licencia");
                $taxista->status = (Input::Get("status") == "activo") ? "activo" : "inactivo";
                $taxista->save();
            }
            else if(Input::has("username"))
            {
                $usuario = Usuario::find(Input::Get("id"));
                if($usuario != null)
                {
                    $usuario->username = Input::get("username");
                    $usuario->email = Input::get("email");
                    $usuario->save();
                    $newPassword = trim(Input::get("password"));
                    if(strlen($newPassword) > 2 && $newPassword != "********")
                    {
                        $usuario->password = DB::raw("PASSWORD('{$newPassword}')");
                        $usuario->save();
                    }
                }
            }

            $usuario = Usuario::where("status", "<>", "eliminado")->where("id", $taxista->id_usuario)->first();

            $this->layout->section = "Taxistas";
            $this->layout->content = View::make('taxistas.details', array(
                    'taxista' => $taxista,
                    'usuario' => $usuario
            ));
            return;
        }
        return Redirect::to("/sitios");
    }
    
    public function delete($id)
    {
        $taxista = Taxista::find($id);
        if ($taxista != null)
        {
            $taxista->usuario->status = "eliminado";
            $taxista->usuario->save();
            $taxista->usuario->delete();
            
            $taxista->status = "eliminado";
            $taxista->save();
            $taxista->delete();
        }
        return Redirect::to("/taxistas");
    }

    public function anyNuevo()
    {
        $taxista = new Taxista();
        if (Input::has("nombre"))
        {
            $taxista->nombre = Input::Get("nombre");
            $taxista->apellidos = Input::Get("apellidos");
            $taxista->save();

            if ($taxista->id > 0)
            {
                $usuario = new Usuario();
                $usuario->id_rol = 5;
                $usuario->tipo = "taxista";
                $usuario->username = "taxista" . $taxista->id;
                $usuario->email = "taxista" . $taxista->id . "@setapp.com";
                $usuario->save();

                if ($usuario->id > 0)
                {
                    $taxista->id_usuario = $usuario->id;
                    $taxista->save();
                }
            }
        }
        return Redirect::to("/taxistas");
    }

}
