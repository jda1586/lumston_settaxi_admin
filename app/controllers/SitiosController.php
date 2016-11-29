<?php

class SitiosController extends BaseController
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
        $this->layout->section = "Sitios";
        $sitios = Sitio::where("status", "<>", "eliminado")->orderBy("id", "desc")->get();

        $this->layout->content = View::make('sitios.sitios', array(
                'sitios' => $sitios
        ));
    }

    public function details($id, $nombre)
    {
        $sitio = Sitio::find($id);
        if ($sitio != null)
        {
            if (Input::has("nombre"))
            {
                $sitio->numero = Input::Get("numero");
                $sitio->nombre = Input::Get("nombre");
                $sitio->direccion = Input::Get("direccion");
                $sitio->telefono = Input::Get("telefono");
                $sitio->lat = Input::Get("lat");
                $sitio->lng = Input::Get("lng");
                $sitio->id_encargado = Input::Get("id_encargado");
                $newStatus = Input::Get("status");
                if ($newStatus == "activo" || $newStatus == "inactivo")
                {
                    $sitio->status = $newStatus;
                }
                $sitio->save();
            }
            else if (Input::has("id_taxi"))
            {
                $taxi = Taxi::find(Input::get("id_taxi"));
                if ($taxi != null)
                {
                    $taxi->id_sitio = $sitio->id;
                    $taxi->save();
                }
            }


            $encargados = Usuario::where("id_rol", 3)->where("status", "<>", "eliminado")->orderBy("nombre", "asc")->get();


            $taxis = Vtaxi::where("id_sitio", $sitio->id)->where("status", "<>", "eliminado")->orderBy("id", "desc")->get();
            $taxisTodos = Vtaxi::where("id_sitio", "<>", $sitio->id)->where("status", "<>", "eliminado")->orderBy("id", "desc")->get();

            $this->layout->section = "Sitios";
            $this->layout->content = View::make('sitios.details', array(
                    'sitio' => $sitio,
                    'taxis' => $taxis,
                    'taxistodos' => $taxisTodos,
                    'encargados' => $encargados
            ));
            return;
        }
        return Redirect::to("/sitios");
    }

    public function delete($id)
    {
        $sitio = Sitio::find($id);
        if ($sitio != null)
        {
            $sitio->status = "eliminado";
            $sitio->save();
            $sitio->delete();
        }
        return Redirect::to("/sitios");
    }

    public function anyNuevo()
    {
        $sitio = new Sitio();
        if (Input::has("nombre"))
        {
            $sitio->numero = Input::Get("numero");
            $sitio->nombre = Input::Get("nombre");
            $sitio->save();
        }
        return Redirect::to("/sitios");
    }

}
