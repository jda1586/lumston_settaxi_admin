<?php

class ApiV2UserViajeController extends ApiBaseController
{
    
    public function calificarViaje()
    {
        $response = array(
            'codigo' => 2000
        );
        
        if (!Input::has('idSesion') || !Input::has('idViaje') || !Input::has('calificacion'))
        {
            return $this->resultDetails($response, 2000); // ERROR: Parametros invalidos
        }
        
        $sesion = Sesion::getBySessionId(Input::get('idSesion'));
        if ($sesion == null)
        {
            return $this->resultDetails($response, 2006); // Sesion invalida o expirada
        }
        
        $usuario = Usuario::find($sesion->id_usuario);
        if ($usuario == null) // ERROR: 2007 no existe el usuario
        {
            return $this->resultDetails($response, 2007);
        }

        if ($usuario->rol->nombre != "Usuario" && $usuario->rol->nombre != "UsuarioFacebook") // ERROR: 2063 no es un usuario cliente
        {
            return $this->resultDetails($response, 2063);
        }
        
        $idViaje = Input::get('idViaje');

        // Validar id viaje
        if (!is_numeric($idViaje) || !$idViaje)
        {
            return $this->resultDetails($response, 2014); 
        }
        
        $viaje = Viaje::where('id', $idViaje)->where('id_cliente', $sesion->id_usuario)->first();
        if ($viaje == null)
        {
            return $this->resultDetails($response, 2015); // Viaje no encontrado
        }
        
        $calificacion = Input::get('calificacion');
        
        if ($viaje->status == "iniciado" || $viaje->status == "finalizado")
        {
            if($calificacion == 1 || $calificacion == 2 || $calificacion == 3 || $calificacion == 4 || $calificacion == 5)
            {
                $viaje->calificacion = $calificacion;
                $viaje->save();

                $response['codigo'] = 1000; // OK
            }
            else
            {
                $response['codigo'] = 2070; // 1-5
            }
        }
        else
        {
            $response['codigo'] = 2069; // El viaje debe estar en estatus solicitado para cancelarlo
        }

        return $this->resultDetails($response);
        
    }

    public function verViajeTaxiPosicion()
    {
        $response = array(
            'codigo' => 2000
        );
        
        if (!Input::has('idSesion') || !Input::has('idViaje'))
        {
            return $this->resultDetails($response, 2000); // ERROR: Parametros invalidos
        }
        
        $sesion = Sesion::getBySessionId(Input::get('idSesion'));
        if ($sesion == null)
        {
            return $this->resultDetails($response, 2006); // Sesion invalida o expirada
        }
        
        $usuario = Usuario::find($sesion->id_usuario);
        if ($usuario == null) // ERROR: 2007 no existe el usuario
        {
            return $this->resultDetails($response, 2007);
        }

        if ($usuario->rol->nombre != "Usuario" && $usuario->rol->nombre != "UsuarioFacebook") // ERROR: 2063 no es un usuario cliente
        {
            return $this->resultDetails($response, 2063);
        }
        
        $idViaje = Input::get('idViaje');

        // Validar id viaje
        if (!is_numeric($idViaje) || !$idViaje)
        {
            return $this->resultDetails($response, 2014); 
        }
        
        $viaje = Viaje::where('id', $idViaje)->where('id_cliente', $sesion->id_usuario)->first();
        if ($viaje == null)
        {
            return $this->resultDetails($response, 2015); // Viaje no encontrado
        }
        
        if ($viaje->status == "iniciado" || $viaje->status == "aceptado")
        {
            $response['viaje'] = array(
                "id" => $viaje->id,
                "id_taxi" => $viaje->id_taxi,
                "posicion_lat" => $viaje->posicion_lat,
                "posicion_lng" => $viaje->posicion_lng,
            );

            $response['codigo'] = 1000; // OK
        }
        else
        {
            $response['codigo'] = 2068; // El viaje debe estar en estatus solicitado para cancelarlo
        }

        return $this->resultDetails($response);
        
    }
    

    public function cancelarViajeUsuario()
    {
        $response = array(
            'codigo' => 2000
        );
        
        if (!Input::has('idSesion') || !Input::has('idViaje'))
        {
            return $this->resultDetails($response, 2000); // ERROR: Parametros invalidos
        }
        
        $sesion = Sesion::getBySessionId(Input::get('idSesion'));
        if ($sesion == null)
        {
            return $this->resultDetails($response, 2006); // Sesion invalida o expirada
        }
        
        $usuario = Usuario::find($sesion->id_usuario);
        if ($usuario == null) // ERROR: 2007 no existe el usuario
        {
            return $this->resultDetails($response, 2007);
        }

        if ($usuario->rol->nombre != "Usuario" && $usuario->rol->nombre != "UsuarioFacebook") // ERROR: 2063 no es un usuario cliente
        {
            return $this->resultDetails($response, 2063);
        }
        
        $idViaje = Input::get('idViaje');

        // Validar id viaje
        if (!is_numeric($idViaje) || !$idViaje)
        {
            return $this->resultDetails($response, 2014); 
        }
        
        $viaje = Viaje::where('id', $idViaje)->where('id_cliente', $sesion->id_usuario)->first();
        if ($viaje == null)
        {
            return $this->resultDetails($response, 2015); // Viaje no encontrado
        }
        
        if ($viaje->status == "solicitado" || $viaje->status == "aceptado")
        {
            $viaje->status = "cancelado";
            $viaje->id_usuario_cancelacion = $usuario->id;
            $viaje->save();

            $response['codigo'] = 1000; // OK
        }
        else
        {
            $response['codigo'] = 2067; // El viaje debe estar en estatus solicitado para cancelarlo
        }

        return $this->resultDetails($response);
        
    }

    public function solicitarTaxistaViajeUsuario()
    {
        $response = array(
            'codigo' => 2000
        );
        
        if (!Input::has('idSesion') || !Input::has('idViaje'))
        {
            return $this->resultDetails($response, 2000); // ERROR: Parametros invalidos
        }
        
        $sesion = Sesion::getBySessionId(Input::get('idSesion'));
        if ($sesion == null)
        {
            return $this->resultDetails($response, 2006); // Sesion invalida o expirada
        }
        
        $usuario = Usuario::find($sesion->id_usuario);
        if ($usuario == null) // ERROR: 2007 no existe el usuario
        {
            return $this->resultDetails($response, 2007);
        }

        if ($usuario->rol->nombre != "Usuario" && $usuario->rol->nombre != "UsuarioFacebook") // ERROR: 2063 no es un usuario cliente
        {
            return $this->resultDetails($response, 2063);
        }
        
        $idViaje = Input::get('idViaje');

        // Validar id viaje
        if (!is_numeric($idViaje) || !$idViaje)
        {
            return $this->resultDetails($response, 2014); 
        }
        
        $viaje = Viaje::where('id', $idViaje)->where('id_cliente', $sesion->id_usuario)->first();
        if ($viaje == null)
        {
            return $this->resultDetails($response, 2015); // Viaje no encontrado
        }
        
        if ($viaje->status == "cotizado" || $viaje->status == "solicitado")
        {
            $viaje->status = "solicitado";
            $viaje->save();

            $response['codigo'] = 1000; // OK
        }
        else
        {
            $response['codigo'] = 2064; // El viaje debe estar en estatus cotizado para marcarlo como solicitado
        }

        return $this->resultDetails($response);
        
    }

    public function cotizarViajeUsuario()
    {
        $response = array(
            'codigo' => 2000
        );

        if (!Input::has('idSesion') || !Input::has('origen') || !Input::has('destino'))
        {
            return $this->resultDetails($response, 2000); // ERROR: Parametros invalidos
        }

        $sesion = Sesion::getBySessionId(Input::get('idSesion'));
        if ($sesion == null)
        {
            return $this->resultDetails($response, 2006); // Sesion invalida o expirada
        }

        $usuario = Usuario::find($sesion->id_usuario);
        if ($usuario == null) // ERROR: 2007 no existe el usuario
        {
            return $this->resultDetails($response, 2007);
        }

        if ($usuario->rol->nombre != "Usuario" && $usuario->rol->nombre != "UsuarioFacebook") // ERROR: 2063 no es un usuario cliente
        {
            return $this->resultDetails($response, 2063);
        }


        // Verificar formato Lat Lng
        $origen = new LatLng(Input::get('origen'));
        $destino = new LatLng(Input::get('destino'));
        if (!$origen->isValid() || !$destino->isValid())
        {
            return $this->resultDetails($response, 2010); // Formato de LatLng invalido
        }

        // Validar la distancia mínima para cotizar
        $distance = MapServices::getDistanceBetween($origen->lat(), $origen->lng(), $destino->lat(), $destino->lng());
        if ($distance < 100)
        {
            return $this->resultDetails($response, 2011); // Los puntos origen y destino deben ser diferentes
        }

        // Crear viaje ------------------------------------------------------------------------------------------

        $viaje = new Viaje();
        $viaje->id_sesion = $sesion->id;
        $viaje->id_usuario = $usuario->id;
        $viaje->id_cliente = $usuario->id; // Cliente que crea el viaje

        $viaje->origen_lat = $origen->lat();
        $viaje->origen_lng = $origen->lng();
        $viaje->destino_lat = $destino->lat();
        $viaje->destino_lng = $destino->lng();
        $viaje->status = "cotizado";

        $routeDetails = MapServices::getRouteDetails($origen, $destino);
        if ($routeDetails)
        {
            $curRate = Rates::getRates();
            $viaje->tarifa = $curRate["tarifa"];
            
            $viaje->tiempo_estimado = $routeDetails->duration;
            $viaje->distancia_estimada = $routeDetails->distance;
            
            $viaje->costo_estimado = Rates::getRateEstimation($routeDetails->duration, $routeDetails->distance, $viaje->tarifa, true);
            $viaje->save();

            if ($viaje->id)
            {
                $viaje = Viaje::find($viaje->id);
            }
            if ($viaje->id)
            {
                $response['viaje'] = array(
                    "id" => $viaje->id,
                    "origen_lat" => $viaje->origen_lat,
                    "origen_lng" => $viaje->origen_lng,
                    "destino_lat" => $viaje->destino_lat,
                    "destino_lng" => $viaje->destino_lng,
                    "status" => $viaje->status,
                    "tiempo_estimado" => $viaje->tiempo_estimado,
                    "costo_estimado" => $viaje->costo_estimado,
                    "distancia_estimada" => $viaje->distancia_estimada,
                    "tarifa" => Rates::getRates($viaje->created_at)
                );
                $response['codigo'] = 1000; // OK
            }
            else
            {
                $response['codigo'] = 2013; // Hubo un error al crear el viaje
            }
        }
        else
        {
            $response['codigo'] = 2012; // Hubo un error al comunicarse con el servidor de geolocalizacion
        }

        return $this->resultDetails($response);
    }

}
