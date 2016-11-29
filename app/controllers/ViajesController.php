<?php

class ViajesController extends BaseController
{

    public function __construct()
    {
        $this->beforeFilter(function()
        {
            if(!$this->authorize())
            {
                return Redirect::to("/login");
            }
            
            if($this->user->rol->id != 1 && $this->user->rol->id != 3)
            {
                //return Redirect::to("/taxis");
            }
        });
    }

    public function getIndex()
    {
        
    }

    public function details($id)
    {
        $viaje = Viaje::find($id);
        if ($viaje != null)
        {
            $tarifa = Rates::getRates($viaje->created_at);

            $taxi = Taxi::find($viaje->id_taxi);
            $taxista = Taxista::find($viaje->id_taxista);

            $this->layout->section = "Viajes";
            $this->layout->content = View::make('viajes.viaje', array(
                    'viaje' => $viaje,
                    'taxi' => $taxi,
                    'taxista' => $taxista,
                    'tarifa' => $tarifa
            ));
            return;
        }
        return Redirect::to("/viajes");
    }
    
    public function fixCost()
    {
        $response = array(
            'result' => "error"
        );
        
        if(Input::has("id"))
        {
            $viaje = Viaje::where("status", "finalizado")->where("id", Input::Get('id'))->first();
            if($viaje !== null)
            {
                $curRate = Rates::getRates($viaje->created_at);
                $dayRate = ($curRate["tarifa"] == "Día") ? true : false;
                $tiempoReal = ($viaje->taxi_libre) ? $viaje->tiempo_real : $viaje->tiempo_estimado;
                $costoReal = Rates::getRateEstimation($tiempoReal, $viaje->distancia_real, $dayRate);

                if (abs($viaje->costo_real - $costoReal) > 1)
                {
                    $viaje->costo_real = $costoReal;
                    $viaje->corregido = 1;
                    $viaje->save();
                    $response['result'] = "ok";
                    $response['costo_real'] = round($viaje->costo_real, 2);
                }
            }
        }
        
        return $response;
    }

}
