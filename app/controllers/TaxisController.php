<?php

class TaxisController extends BaseController
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
                return Redirect::to("/");
            }
        });
    }

    public function getIndex()
    {
        $this->layout->section = "Taxis";
        $taxis = Vtaxi::where("status", "<>", "eliminado")->orderBy("id", "desc")->get();
        $sitios = Sitio::where("status", "<>", "eliminado")->orderBy("numero", "asc")->get();
        $propietarios = Propietario::where("status", "<>", "eliminado")->orderBy("nombre", "asc")->get();

        $this->layout->content = View::make('taxis.taxis', array(
                'taxis' => $taxis,
                'sitios' => $sitios,
                'propietarios' => $propietarios
        ));
    }

    public function details($id, $nombre)
    {
        $taxi = Taxi::find($id);

        $sitio = new Sitio();
        $propietario = new Propietario();

        if ($taxi != null)
        {
            if (Input::has("numero_economico"))
            {
                $taxi->numero_economico = Input::Get("numero_economico");
                $taxi->placas = Input::Get("placas");
                $taxi->marca = Input::Get("marca");
                $taxi->modelo = Input::Get("modelo");
                $taxi->anio = Input::Get("anio");
                $taxi->descripcion = Input::Get("descripcion");
                $taxi->status = (Input::Get("status") == "activo") ? "activo" : "inactivo";
                $taxi->save();
            }
            else if (Input::has("id_propietario"))
            {
                $taxi->id_propietario = Input::Get("id_propietario");
                $taxi->save();
            }
            else if (Input::has("id_sitio"))
            {
                $taxi->id_sitio = Input::Get("id_sitio");
                $taxi->save();
            }
            else if (Input::has("id_taxista"))
            {
                $idTaxista = Input::get("id_taxista");
                $taxiTaxista = TaxiTaxista::where("id_taxista", $idTaxista)->where("id_taxi", $taxi->id)->where("status", "activo")->first();
                if ($taxiTaxista == null)
                {
                    $taxiTaxista = new TaxiTaxista();
                    $taxiTaxista->id_taxi = $taxi->id;
                    $taxiTaxista->id_taxista = $idTaxista;
                    $taxiTaxista->status = "activo";
                    $taxiTaxista->save();
                }
            }

            $sitio = Sitio::find($taxi->id_sitio);
            $propietario = Propietario::find($taxi->id_propietario);

            $taxistas = Vtaxista::where("id_taxi", $taxi->id)->orderBy("id", "desc")->get();
            $taxistasTodos = Taxista::where("status", "<>", "eliminado")->orderBy("id", "desc")->get();

            $sitios = Sitio::where("status", "<>", "eliminado")->orderBy("numero", "asc")->get();
            $propietarios = Propietario::where("status", "<>", "eliminado")->orderBy("nombre", "asc")->get();


            $viajes = Vviaje::where("id_taxi", $taxi->id)->orderBy("id", "desc")->get();

            $this->layout->section = "Taxis";
            $this->layout->content = View::make('taxis.details', array(
                    'taxi' => $taxi,
                    'sitio' => $sitio,
                    'propietario' => $propietario,
                    'sitios' => $sitios,
                    'propietarios' => $propietarios,
                    'taxistas' => $taxistas,
                    'taxistastodos' => $taxistasTodos,
                    'viajes' => $viajes
            ));
            return;
        }
        return Redirect::to("/taxistas");
    }

    public function delete($id)
    {
        $taxi = Taxi::find($id);
        if ($taxi != null)
        {
            $taxi->status = "eliminado";
            $taxi->save();
            $taxi->delete();
        }
        return Redirect::to("/taxis");
    }

    public function anyNuevo()
    {
        $taxi = new Taxi();
        if (Input::has("numero_economico"))
        {
            $taxi->numero_economico = Input::Get("numero_economico");
            $taxi->placas = Input::Get("placas");
            $taxi->id_propietario = Input::Get("id_propietario");
            $taxi->id_sitio = Input::Get("id_sitio");
            $taxi->save();
        }
        return Redirect::to("/taxis");
    }

}
