<?php

class UbicacionesController extends BaseController
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
        $this->index((Input::has("mapa")));
    }

    public function index($map)
    {
        $this->layout->section = ($map) ? "UbicacionesMapa" : "Ubicaciones";
        $locations = Vubicacion::where("status", "<>", "eliminado")->orderBy("id", "desc")->get();
        $cities = Ciudad::orderBy("titulo", "asc")->get();
        $types = TipoUbicacion::orderBy("titulo", "asc")->get();

        $this->layout->content = View::make('ubicaciones.list', array(
                'locations' => $locations,
                'cities' => $cities,
                'types' => $types,
                'showMap' => $map
        ));
    }

    public function locations()
    {
        $locations = Vubicacion::where("status", "<>", "eliminado")->orderBy("id", "desc")->get();

        $response = array(
            "result" => "ok",
            "locations" => $locations
        );
        return $response;
    }
    
    

    public function details($id, $nombre)
    {
        $ubicacion = Ubicacion::find($id);
        if ($ubicacion != null)
        {
            if (Input::has("titulo"))
            {
                $ubicacion->titulo = Input::Get("titulo");
                $ubicacion->direccion = Input::Get("direccion");
                $ubicacion->etiquetas = Input::Get("etiquetas");
                $ubicacion->lat = Input::Get("lat");
                $ubicacion->lng = Input::Get("lng");
                $ubicacion->id_ciudad = Input::Get("id_ciudad");
                $ubicacion->id_tipo_ubicacion = Input::Get("id_tipo_ubicacion");
                $newStatus = Input::Get("status");
                if ($newStatus == "activo" || $newStatus == "inactivo")
                {
                    $ubicacion->status = $newStatus;
                }
                $ubicacion->save();
            }

            $cities = Ciudad::orderBy("titulo", "asc")->get();
            $types = TipoUbicacion::orderBy("titulo", "asc")->get();

            $this->layout->section = "Ubicaciones";
            $this->layout->content = View::make('ubicaciones.details', array(
                    'location' => $ubicacion,
                    'cities' => $cities,
                    'types' => $types
            ));
            return;
        }
        return Redirect::to("/ubicaciones");
    }

    public function delete($id)
    {
        $ubicacion = Ubicacion::find($id);
        if ($ubicacion != null)
        {
            $ubicacion->status = "eliminado";
            $ubicacion->save();
            $ubicacion->delete();
        }
        return Redirect::to("/ubicaciones");
    }

    public function anyNuevo()
    {
        $ubicacion = new Ubicacion();
        if (Input::has("titulo"))
        {
            $ubicacion->titulo = Input::Get("titulo");
            $ubicacion->id_ciudad = Input::Get("id_ciudad");
            $ubicacion->id_tipo_ubicacion = Input::Get("id_tipo_ubicacion");
            $ubicacion->save();
        }
        return Redirect::to("/ubicaciones");
    }

}
