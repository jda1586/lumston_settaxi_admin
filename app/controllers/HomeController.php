<?php

class HomeController extends BaseController
{

    public function __construct()
    {
        $this->beforeFilter(function()
        {
            if (!$this->authorize())
            {
                return Redirect::to("/login");
            }
        });
    }

    public function anyIndex()
    {
        $this->layout->section = "Home";

        $viajes = Vviaje::where("status", "<>", "eliminado")->orderBy("id", "desc")->limit(1000)->get();
        $sitios = Sitio::where("status", "<>", "eliminado")->orderBy("numero", "asc")->limit(1000)->get();
        $taxis = Taxi::where("id", 1)->first();
        $idSitio = 0;
        $idTaxi = 0;
        $view = "home";
        $type = "";
        $id_propietario = 0;
        $id_taxista = 0;

        if ($this->user->rol->id == 3)
        {
            $viajes = null;
            $sitios = null;

            $sitio = Sitio::where("status", "<>", "eliminado")->where("id_encargado", $this->user->id)->first();
            if ($sitio != null)
            {
                $sitios = Sitio::where("id", $sitio->id)->get();
                $viajes = Vviaje::where("sitio_id", $sitio->id)->orderBy("id", "desc")->limit(1000)->get();
                $idSitio = $sitio->id;
            }
        }
        else if ($this->user->rol->id == 4)
        {
            $propietario = Propietario::where("id_usuario", $this->user->id)->first();
            if ($propietario != null)
            {
                $id_propietario = $propietario->id;
                $taxis = Taxi::where("id_propietario", $id_propietario)->get();
            }
            $view = "homeviajes";
            $viajes = Vviaje::where("status", "<>", "eliminado")->where("id_propietario", $id_propietario)->orderBy("id", "desc")->limit(1000)->get();
            $type = "propietario";
        }
        else if ($this->user->rol->id == 5)
        {
            $taxista = Taxista::where("id_usuario", $this->user->id)->first();
            if ($taxista != null)
            {
                $id_taxista = $taxista->id;
                $taxis = TaxiTaxista::where("id_taxista", $id_taxista)->get();
            }
            $view = "homeviajes";
            $viajes = Vviaje::where("status", "<>", "eliminado")->where("id_taxista", $id_taxista)->orderBy("id", "desc")->limit(1000)->get();
            $type = "taxista";
        }

        $start = date("Y-m-d");
        $end = date("Y-m-d");

        if (Input::has("start") && Input::has("end"))
        {
            $start = Input::get("start");
            $end = Input::get("end");
            $viajes = Vviaje::where("created_at", ">=", $start)->where("created_at", "<=", $end)->orderBy("id", "desc")->limit(1000)->get();
            if ($this->user->rol->id == 1)
            {
                $idSitio = Input::get("id_sitio");
            }
            if ($idSitio > 0)
            {
                $viajes = Vviaje::where("created_at", ">=", $start)->where("created_at", "<=", $end)->where("sitio_id", $idSitio)->orderBy("id", "desc")->limit(1000)->get();
            }
            else if ($this->user->rol->id == 4 || $this->user->rol->id == 5)
            {
                $idTaxi = Input::get("id_taxi");
                if ($idTaxi == 0)
                {
                    if ($this->user->rol->id == 4)
                    {
                        $viajes = Vviaje::where("created_at", ">=", $start)->where("created_at", "<=", $end)->where("id_propietario", $id_propietario)->orderBy("id", "desc")->limit(1000)->get();
                    }
                    else
                    {
                        $viajes = Vviaje::where("created_at", ">=", $start)->where("created_at", "<=", $end)->where("id_taxista", $id_taxista)->orderBy("id", "desc")->limit(1000)->get();
                    }
                }
                else
                {
                    $viajes = Vviaje::where("created_at", ">=", $start)->where("created_at", "<=", $end)->where("id_taxi", $idTaxi)->orderBy("id", "desc")->limit(1000)->get();
                }
            }
        }

        $this->layout->content = View::make($view, array(
                'viajes' => $viajes,
                'start' => $start,
                'end' => $end,
                'sitios' => $sitios,
                'id_sitio' => $idSitio,
                'taxis' => $taxis,
                'id_taxi' => $idTaxi,
                'type' => $type
        ));
    }
    

}
