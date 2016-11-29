<?php

//namespace Controllers\Api\V2;

class ApiV2LocationsController extends ApiBaseController
{

    public function getLocations()
    {
        $response = array('codigo' => 2000);

        if (!Input::has('search'))
        {
            return $this->resultDetails($response, 2000); // ERROR: parameters ---------------------
        }
        
        $search = Input::get("search");
        
        $filter = "CONCAT(titulo, direccion, etiquetas, tipo_ubicacion_titulo) LIKE '%" . $search . "%'";
        
        
        $locations = Vubicacion::where("status", "activo")
            ->whereRaw($filter)
            ->orderBy("consultas", "desc")->limit(10)
            ->get();
        

        $response['codigo'] = 1000;
        $response['locations'] = $locations;

        return $this->resultDetails($response);
    }

}
