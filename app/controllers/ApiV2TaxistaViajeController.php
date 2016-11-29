<?php

class ApiV2TaxistaViajeController extends ApiBaseController
{

    public function cotizarViajeTaxista()
    {
        $response = array(
            'codigo' => 2000
        );

        if (!Input::has('idSesion') || !Input::has('origen') || !Input::has('destino'))
        {
            return $this->resultDetails($response, 2000); // Parametros invalidos
        }

        $sesion = Sesion::getBySessionId(Input::get('idSesion')); // Get Session
        if ($sesion == null)
        {
            return $this->resultDetails($response, 2006); // Sesion invalida o expirada
        }

        $usuario = Usuario::find($sesion->id_usuario);
        if ($usuario == null || $usuario->tipo != "taxista") // Verificar si el taxi esta asignado al taxista
        {
            return $this->resultDetails($response, 2007);
        }

        $taxista = $usuario->taxista;
        if ($taxista == null)
        {
            return $this->resultDetails($response, 2003); // No hay taxista asociado al usuario
        }

        if ($sesion->id_taxi == 0)
        {
            return $this->resultDetails($response, 2009); // No hay taxi seleccionado para la sesion
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
        $viaje->id_usuario = $sesion->id_usuario;
        $viaje->id_taxi = $sesion->id_taxi;
        $viaje->id_taxista = $taxista->id;
        $viaje->origen_lat = $origen->lat();
        $viaje->origen_lng = $origen->lng();
        $viaje->destino_lat = $destino->lat();
        $viaje->destino_lng = $destino->lng();
        $viaje->status = "cotizado";

        $routeDetails = MapServices::getRouteDetails($origen, $destino);
        if ($routeDetails)
        {
            $curRate = Rates::getRates();
            $dayRate = ($curRate["tarifa"] == "Noche") ? false : true;
            $viaje->tiempo_estimado = $routeDetails->duration;
            $viaje->distancia_estimada = $routeDetails->distance;
            $viaje->costo_estimado = Rates::getRateEstimation($routeDetails->duration, $routeDetails->distance, $dayRate, true);
            $viaje->tarifa = $curRate["tarifa"];
            $viaje->save();

            if ($viaje->id)
            {
                $viaje = Viaje::find($viaje->id);
            }
            if ($viaje->id)
            {
                $response['viaje'] = array(
                    "id" => $viaje->id,
                    "id_taxi" => $viaje->id_taxi,
                    "origen_lat" => $viaje->origen_lat,
                    "origen_lng" => $viaje->origen_lng,
                    "destino_lat" => $viaje->destino_lat,
                    "destino_lng" => $viaje->destino_lng,
                    "status" => $viaje->status,
                    "tiempo_estimado" => $viaje->tiempo_estimado,
                    "costo_estimado" => ceil($viaje->costo_estimado),
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

    public function verViajesAbiertos()
    {
        $response = array(
            'codigo' => 2000
        );

        if (!Input::has('idSesion'))
        {
            return $this->resultDetails($response, 2000);
        }

        $sesion = Sesion::getBySessionId(Input::get('idSesion')); // Get session
        if ($sesion == null)
        {
            return $this->resultDetails($response, 2006); // Sesion invalida o expirada
        }

        $usuario = Usuario::find($sesion->id_usuario);
        if ($usuario == null || $usuario->tipo != "taxista")
        {
            return $this->resultDetails($response, 2007);
        }

        $taxista = $usuario->taxista;
        if ($taxista == null)
        {
            return $this->resultDetails($response, 2003);
        }

        if ($sesion->id_taxi == 0)
        {
            return $this->resultDetails($response, 2009);
        }

        $viajes = Viaje::where("id_taxista", $taxista->id)->where("status", "iniciado")->limit(5)->get();
        $response['viajes_abiertos'] = array();
        foreach ($viajes as $viaje)
        {
            $response['viajes_abiertos'][] = array(
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
        }

        $response['result'] = 1000;

        return $this->resultDetails($response);
    }

    public function actualizarPosicionTaxi()
    {
        $response = array(
            'codigo' => 2000
        );

        if (!Input::has('idSesion') || !Input::has('idViaje') || !Input::has('ubicacionActual'))
        {
            return $this->resultDetails($response, 2000);
        }

        $sesion = Sesion::getBySessionId(Input::get('idSesion')); // Get session
        if ($sesion == null)
        {
            return $this->resultDetails($response, 2006); // Sesion invalida o expirada
        }

        $usuario = Usuario::find($sesion->id_usuario);
        if ($usuario == null || $usuario->tipo != "taxista")
        {
            return $this->resultDetails($response, 2007);
        }

        $taxista = $usuario->taxista;
        if ($taxista == null)
        {
            return $this->resultDetails($response, 2003);
        }

        if ($sesion->id_taxi == 0)
        {
            return $this->resultDetails($response, 2009);
        }

        $idViaje = Input::get('idViaje');

        // Validar id viaje
        if (!is_numeric($idViaje) || !$idViaje)
        {
            return $this->resultDetails($response, 2014);
        }

        $viaje = Viaje::where('id', $idViaje)->where('id_taxista', $taxista->id)->first();
        if ($viaje == null)
        {
            return $this->resultDetails($response, 2015); //Viaje no encontrado
        }

        if ($viaje->id_taxi != $sesion->id_taxi)
        {
            return $this->resultDetails($response, 2016); // El taxi del viaje no coincide con el de la sesion
        }

        $posicionLatLng = new LatLng(Input::get('ubicacionActual'));
        if (!$posicionLatLng->isValid())
        {
            return $this->resultDetails($response, 2010);
        }

        if ($viaje->status == "iniciado" || $viaje->status == "aceptado")
        {



            $viaje->posicion_lat = $posicionLatLng->lat();
            $viaje->posicion_lng = $posicionLatLng->lng();
            $viaje->save();

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
            $response['codigo'] = 2066; // El viaje debe estar en estatus cotizado para marcarlo como iniciado
        }

        return $this->resultDetails($response);
    }

    public function cancelarViajeTaxista()
    {
        $response = array(
            'codigo' => 2000
        );

        if (!Input::has('idSesion') || !Input::has('idViaje') || !Input::has('motivosCancelacion'))
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

        if ($usuario->rol->nombre !== "Taxista") // ERROR: 2002 no es un usuario taxista
        {
            return $this->resultDetails($response, 2002);
        }

        $taxista = $usuario->taxista;
        if ($taxista == null)
        {
            return $this->resultDetails($response, 2003);
        }

        if ($sesion->id_taxi == 0)
        {
            return $this->resultDetails($response, 2009);
        }

        $idViaje = Input::get('idViaje');
        $motivos = Input::get('motivosCancelacion');

        // Validar id viaje
        if (!is_numeric($idViaje) || !$idViaje)
        {
            return $this->resultDetails($response, 2014);
        }

        $viaje = Viaje::where('id', $idViaje)->where('id_taxista', $taxista->id)->first();
        if ($viaje == null)
        {
            return $this->resultDetails($response, 2015); // Viaje no encontrado
        }

        if ($viaje->status == "aceptado")
        {
            $viaje->status = "cancelado";
            $viaje->motivo_cancelacion = $motivos;
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

    public function iniciarViaje()
    {
        $response = array(
            'codigo' => 2000
        );

        if (!Input::has('idSesion') || !Input::has('idViaje'))
        {
            return $this->resultDetails($response, 2000);
        }

        $sesion = Sesion::getBySessionId(Input::get('idSesion')); // Get session
        if ($sesion == null)
        {
            return $this->resultDetails($response, 2006); // Sesion invalida o expirada
        }

        $usuario = Usuario::find($sesion->id_usuario);
        if ($usuario == null || $usuario->tipo != "taxista")
        {
            return $this->resultDetails($response, 2007);
        }

        $taxista = $usuario->taxista;
        if ($taxista == null)
        {
            return $this->resultDetails($response, 2003);
        }

        if ($sesion->id_taxi == 0)
        {
            return $this->resultDetails($response, 2009);
        }

        $idViaje = Input::get('idViaje');

        // Validar id viaje
        if (!is_numeric($idViaje) || !$idViaje)
        {
            return $this->resultDetails($response, 2014);
        }

        $viaje = Viaje::where('id', $idViaje)->where('id_taxista', $taxista->id)->first();
        if ($viaje == null)
        {
            return $this->resultDetails($response, 2015); //Viaje no encontrado
        }

        if ($viaje->id_taxi != $sesion->id_taxi)
        {
            return $this->resultDetails($response, 2016); // El taxi del viaje no coincide con el de la sesion
        }

        if ($viaje->status == "cotizado" || $viaje->status == "aceptado")
        {
            $viaje->status = "iniciado";
            $viaje->hora_inicio = Functions::datetimeNow();
            $viaje->save();

            $viaje = Viaje::find($viaje->id);
            if ($viaje != null && $viaje->status == "iniciado")
            {
                $response['viaje'] = array(
                    "id" => $viaje->id,
                    "id_taxi" => $viaje->id_taxi,
                    "taxi_libre" => $viaje->taxi_libre,
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
                $response['codigo'] = 2018; // Hubo un error al iniciar el viaje, intente de nuevo
            }
        }
        else
        {
            $response['codigo'] = 2066; // El viaje debe estar en estatus cotizado para marcarlo como iniciado
        }

        return $this->resultDetails($response);
    }

    public function aceptarViajeSolicitado()
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
        if ($usuario == null || $usuario->tipo != "taxista")
        {
            return $this->resultDetails($response, 2007);
        }

        $taxista = $usuario->taxista;
        if ($taxista == null)
        {
            return $this->resultDetails($response, 2003);
        }

        $idViaje = Input::get('idViaje');

        // Validar id viaje
        if (!is_numeric($idViaje) || !$idViaje)
        {
            return $this->resultDetails($response, 2014);
        }

        $viaje = Viaje::where('id', $idViaje)->first();
        if ($viaje == null)
        {
            return $this->resultDetails($response, 2015); // Viaje no encontrado
        }

        if ($viaje->status == "solicitado")
        {
            $viaje->status = "aceptado";
            $viaje->id_taxista = $taxista->id;
            $viaje->id_taxi = $sesion->id_taxi;
            $viaje->save();

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
            $response['codigo'] = 2065;
        }

        return $this->resultDetails($response);
    }

    public function listaViajesSolicitados()
    {
        $response = array(
            'codigo' => 2000
        );

        if (!Input::has('idSesion'))
        {
            return $this->resultDetails($response, 2000); // ERROR: Parametros invalidos
        }

        $sesion = Sesion::getBySessionId(Input::get('idSesion'));
        if ($sesion == null)
        {
            return $this->resultDetails($response, 2006); // Sesion invalida o expirada
        }

        $usuario = Usuario::find($sesion->id_usuario);
        if ($usuario == null || $usuario->tipo != "taxista")
        {
            return $this->resultDetails($response, 2007);
        }

        $taxista = $usuario->taxista;
        if ($taxista == null)
        {
            return $this->resultDetails($response, 2003);
        }

        $viajesSolicitados = Viaje::where("status", "solicitado")->orderBy("id", "desc")->get();
        $response['codigo'] = 1000;
        $response['viajes_solicitados'] = array();

        foreach ($viajesSolicitados as $viaje)
        {
            $viajeItem = array(
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
            $viajeItem["cliente"] = array();
            $cliente = Usuario::find($viaje->id_cliente);
            if ($cliente != null)
            {
                $viajeItem["cliente"] = array(
                    "username" => $cliente->username,
                    "nombre" => $cliente->nombre,
                    "apellidos" => $cliente->apellidos,
                    "email" => $cliente->email,
                    'foto' => uploads_url($cliente->foto)
                );
            }

            $response['viajes_solicitados'][] = $viajeItem;
        }

        return $this->resultDetails($response);
    }

    public function iniciarTaxiLibre()
    {
        $response = array('codigo' => 2000);
        if (!Input::has('idSesion') || !Input::has('idViaje') || !Input::has('ubicacionActual'))
        {
            return $this->resultDetails($response, 2000);
        }

        $sesion = Sesion::getBySessionId(Input::get('idSesion')); // Get session
        if ($sesion == null)
        {
            return $this->resultDetails($response, 2006);
        }

        $usuario = Usuario::find($sesion->id_usuario);
        if ($usuario == null || $usuario->tipo != "taxista")
        {
            return $this->resultDetails($response, 2007);
        }

        $taxista = $usuario->taxista;
        if ($taxista == null)
        {
            return $this->resultDetails($response, 2003);
        }

        if ($sesion->id_taxi == 0 || $sesion->id_taxi == null)
        {
            return $this->resultDetails($response, 2009);
        }

        $idViaje = Input::get('idViaje');
        if (!is_numeric($idViaje) || $idViaje == 0)
        {
            return $this->resultDetails($response, 2014);
        }

        $viaje = Viaje::where('id', $idViaje)->where('id_sesion', $sesion->id)->first();
        if ($viaje == null)
        {
            return $this->resultDetails($response, 2015);
        }

        if ($viaje->id_taxi != $sesion->id_taxi)
        {
            return $this->resultDetails($response, 2016);
        }

        if ($viaje->status != "iniciado")
        {
            return $this->resultDetails($response, 2022);
        }

        if ($viaje->taxi_libre != 0)
        {
            return $this->resultDetails($response, 2019);
        }

        $posicionLatLng = new LatLng(Input::get('ubicacionActual'));
        if (!$posicionLatLng->isValid())
        {
            return $this->resultDetails($response, 2010);
        }

        $viaje->taxi_libre = 1;
        // Get new estimations
        $origen = new LatLng($viaje->origen_lat . "," . $viaje->origen_lng);
        $routeDetails = MapServices::getRouteDetails($origen, $posicionLatLng);
        $viaje->destino_lat = $posicionLatLng->lat();
        $viaje->destino_lng = $posicionLatLng->lng();
        if ($routeDetails)
        {
            $curRate = Rates::getRates($viaje->created_at);
            $dayRate = ($curRate["tarifa"] == "Día") ? true : false;

            // Tiempo estimado ya no es obtenido de gmaps si es taxi libre
            $viaje->tiempo_estimado = $routeDetails->duration;

            // Calcular tiempo real

            $secondsDiff = strtotime(Functions::datetimeNow()) - strtotime($viaje->hora_inicio);
            $viaje->tiempo_real = $secondsDiff;
            $viaje->hora_final = Functions::datetimeNow();

            $viaje->distancia_estimada = $routeDetails->distance;
            $viaje->distancia_real = $viaje->distancia_estimada;
            $viaje->costo_estimado = Rates::getRateEstimation($viaje->tiempo_estimado, $routeDetails->distance, $dayRate);
            $viaje->costo_real = Rates::getRateEstimation($viaje->tiempo_real, $routeDetails->distance, $dayRate);
        }

        $viaje->save();
        $viaje = Viaje::find($viaje->id);

        if ($viaje != null && $viaje->taxi_libre == 1)
        {
            $posicion = new Posicion();
            $posicion->id_viaje = $viaje->id;
            $posicion->lat = $posicionLatLng->lat();
            $posicion->lng = $posicionLatLng->lng();
            $posicion->save();

            if ($posicion->id)
            {
                $response['viaje'] = array(
                    "id" => $viaje->id,
                    "id_taxi" => $viaje->id_taxi,
                    "taxi_libre" => $viaje->taxi_libre,
                    "origen_lat" => $viaje->origen_lat,
                    "origen_lng" => $viaje->origen_lng,
                    "destino_lat" => $viaje->destino_lat,
                    "destino_lng" => $viaje->destino_lng,
                    "status" => $viaje->status,
                    "hora_inicio" => $viaje->hora_inicio,
                    "hora_final" => $viaje->hora_final,
                    "tiempo_estimado" => $viaje->tiempo_estimado,
                    "tiempo_real" => $viaje->tiempo_real,
                    "costo_estimado" => $viaje->costo_estimado,
                    "costo_real" => $viaje->costo_real,
                    "distancia_estimada" => $viaje->distancia_estimada,
                    "distancia_real" => $viaje->distancia_real,
                    "tarifa" => Rates::getRates($viaje->created_at),
                    "posicion_actual" => array(
                        "lat" => $posicion->lat,
                        "lng" => $posicion->lng
                    )
                );
                $response['codigo'] = 1000;
            }
            else
            {
                $viaje->taxi_libre = 0;
                $viaje->save();
                $response['codigo'] = 2021;
            }
        }
        else
        {
            $response['codigo'] = 2020;
        }

        return $this->resultDetails($response);
    }

    public function enviarEmail()
    {
        $response = array(
            'codigo' => 2000
        );

        if (!Input::has('idSesion') || !Input::has('idViaje'))
        {
            return $this->resultDetails($response, 2000);
        }

        $sesion = Sesion::getBySessionId(Input::get('idSesion')); // Get session
        if ($sesion == null)
        {
            return $this->resultDetails($response, 2006); // Sesion invalida o expirada
        }

        $usuario = Usuario::find($sesion->id_usuario);
        if ($usuario == null)
        {
            return $this->resultDetails($response, 2007);
        }

        // Validar id viaje
        $idViaje = Input::get('idViaje');
        if (!is_numeric($idViaje) || !$idViaje)
        {
            return $this->resultDetails($response, 2014);
        }

        $viaje = Viaje::where('id', $idViaje)->first();
        if ($viaje == null)
        {
            return $this->resultDetails($response, 2015); //Viaje no encontrado
        }

        if ($viaje->id_cliente != $usuario->id && $viaje->id_usuario != $usuario->id)
        {
            return $this->resultDetails($response, 2076); // Viaje no pertenece a usuario
        }

        $taxista = Taxista::find($viaje->id_taxista);
        if ($taxista == null)
        {
            return $this->resultDetails($response, 2003);
        }

        $taxi = Taxi::find($viaje->id_taxi);
        $sitio = Sitio::find($taxi->id_sitio);

        if ($viaje->status == "finalizado")
        {
            $viaje->email_cliente = Input::get('email');
            $viaje->save();

            // Send Email

            $config = Config::get("app.smtp");
            $mail = new PHPMailer();
            $mailFrom = $config['mailFrom'];

            $mail->isSMTP();
            $mail->Host = $config['host'];
            $mail->SMTPAuth = $config['SMTPAuth'];
            $mail->Username = $mailFrom;
            $mail->Password = $config['password'];
            $mail->SMTPSecure = $config['SMTPSecure'];
            $mail->Port = $config['port'];

            $mail->SMTPOptions = $config['SMTPOptions'];

            $mail->From = $mailFrom;
            $mail->FromName = 'SetApp.mx';
            $mail->addAddress($viaje->email_cliente);
            foreach ($config['recipients'] as $recipientName => $recipientEmail)
            {
                $mail->addBCC($recipientEmail, $recipientName);
            }

            $mail->isHTML(true);

            $viajeHoraIni = Functions::getDatetimeToAmPm($viaje->hora_inicio);
            $viajeHoraEnd = Functions::getDatetimeToAmPm($viaje->hora_final);
            $viajeKm = Functions::getMetersToKm($viaje->distancia_real);
            $viajeTiempo = Functions::getSecondsToMinH($viaje->tiempo_real);
            $viajeTaxiLibre = ($viaje->taxi_libre == 1) ? "Si" : "No";
            $costo = number_format($viaje->costo_redondeado, 2);

            // Sitio

            $sitioNombre = "Sin nombre";
            $taxi = Taxi::where("id", $viaje->id_taxi)->first();
            if ($taxi !== null)
            {
                $sitio = Sitio::where("id", $taxi->id_sitio)->first();
                if ($sitio != null)
                {
                    $sitioNombre = $sitio->nombre . " No. " . $sitio->numero;
                }
            }

            $tarifa = ($viaje->tarifa == "Noche") ? "Noche" : "D&iacute;a";

            $body = "<html>
                        <head>
                            <title>SET Recibo de Viaje</title>
                        </head>
                        <body style='width: 100%; height: auto; margin: 0; padding: 20px 100px; background: #cbcbcb;font-family:Arial,sans-serif;color:#002f8a;'>
                            <div style='width:600px;height:auto;background:#fff;overflow:hidden;'>
                                <div style='display:block;clear:both;width:100%;height:110px;background:#fcda45 url(" . $config['image'] . ") 20px 22px no-repeat;line-height:110px;text-indent:195px;font-size:42px;color:#fff;'>
                                    Recibo de Viaje
                                </div>
                                <div style='display:block;clear:both;width:90%;height:auto;margin: 25px 5% 50px;overflow:hidden;'>
                                    <div style='display:block;clear:both;width:100%;height:auto;padding: 10px 0;border-bottom:1px solid #dadada;overflow:hidden;'>
                                        <label style='display:inline-block;width:120px;height:auto;line-height:32px;font-size:22px;color:#3369d1;margin:0;padding:0;'>Sitio:</label>
                                        <p style='display:inline-block;width:390px;height:auto;line-height:36px;font-size:22px;color:#2c2c2c;margin:0;margin-left:20px;padding:0;'>" . $sitioNombre . "</p>
                                    </div>
                                    <div style='display:block;clear:both;width:100%;height:auto;padding: 10px 0;border-bottom:1px solid #dadada;overflow:hidden;'>
                                        <label style='display:inline-block;width:120px;height:auto;line-height:32px;font-size:22px;color:#3369d1;margin:0;padding:0;'>Unidad:</label>
                                        <p style='display:inline-block;width:390px;height:auto;line-height:36px;font-size:22px;color:#2c2c2c;margin:0;margin-left:20px;padding:0;'>" . $taxi->numero_economico . "</p>
                                    </div>
                                    <div style='display:block;clear:both;width:100%;height:auto;padding: 10px 0;overflow:hidden;border-bottom:1px solid #dadada;'>
                                        <label style='display:inline-block;width:120px;height:auto;line-height:32px;font-size:22px;color:#3369d1;margin:0;padding:0;'>Chofer:</label>
                                        <p style='display:inline-block;width:390px;height:auto;line-height:36px;font-size:22px;color:#2c2c2c;margin:0;margin-left:20px;padding:0;'>" . $taxista->nombre . " " . $taxista->apellidos . "</p>
                                    </div>
                                    <div style='display:block;clear:both;width:100%;height:auto;padding: 10px 0;overflow:hidden;margin-bottom: 30px;'>
                                        <label style='display:inline-block;width:120px;height:auto;line-height:32px;font-size:22px;color:#3369d1;margin:0;padding:0;'>Cliente:</label>
                                        <p style='display:inline-block;width:390px;height:auto;line-height:36px;font-size:22px;color:#2c2c2c;margin:0;margin-left:20px;padding:0;'>" . $viaje->email_cliente . "</p>
                                    </div>
                                    <div style='display:inline-block;clear:both;width:33%;height:auto;padding: 10px 0;overflow:hidden;font-size: 17px;color:#002f8a;'>
                                        <strong>Inicio:</strong> " . $viajeHoraIni . "
                                    </div>
                                    <div style='display:inline-block;clear:both;width:33%;height:auto;padding: 10px 0;overflow:hidden;font-size: 17px;color:#002f8a;'>
                                        <strong>Distancia:</strong> " . $viajeKm . " Km
                                    </div>
                                    <div style='display:inline-block;clear:both;width:32%;height:auto;padding: 10px 0;overflow:hidden;font-size: 17px;color:#002f8a;'>
                                        <strong>Tiempo:</strong> " . $viajeTiempo . "
                                    </div>
                                    <div style='display:inline-block;clear:both;width:33%;height:auto;padding: 10px 0;overflow:hidden;font-size: 17px;color:#002f8a;'>
                                        <strong>Fin:</strong> " . $viajeHoraEnd . "
                                    </div>
                                    <div style='display:inline-block;clear:both;width:33%;height:auto;padding: 10px 0;overflow:hidden;font-size: 17px;color:#002f8a;'>
                                        <strong>Taxi Libre:</strong> " . $viajeTaxiLibre . "
                                    </div>
                                    <div style='display:inline-block;clear:both;width:32%;height:auto;padding: 10px 0;overflow:hidden;font-size: 17px;color:#002f8a;'>
                                        <strong>Tarifa:</strong> " . $tarifa . "
                                    </div>
                                    <div style='display:inline-block;clear:both;width:100%;height:auto;padding: 40px 0 0;overflow:hidden;font-size: 28px;color:#333333;'>
                                        Costo de tu Viaje
                                    </div>
                                    <div style='display:inline-block;clear:both;width:100%;height:auto;padding: 10px 0 10px;overflow:hidden;font-size: 90px;color:#002f8a;'>
                                        <sup><small>$</small></sup>" . $costo . "<sup>MXN</sup>
                                    </div>
                                    <div style='display:inline-block;clear:both;width:85%;height:auto;padding: 32px 0 0;overflow:hidden;font-size: 13px;color:#979797;'>
                                        En caso de requerir factura, favor de enviar sus datos fiscales al siguiente correo: <a href='facturas@setapp.mx'>facturas@setapp.mx</a>. &nbsp;
                                        <strong>Servicio Eficiente para Taxis</strong> le enviar&aacute; su factura a la brevedad. <strong>Gracias por viajar con SET</strong>
                                    </div>
                                    <div style='display:inline-block;clear:both;width:85%;height:auto;padding: 30px 0 20px;overflow:hidden;font-size: 12px;color:#979797;'>
                                        La informaci&oacute;n contenida en este mensaje y en cualquier archivo o documento que se adjunte al mismo es confidencial. Est&aacute; dirigida
                                        exclusivamente al uso del destinatario y no debe ser utilizado por otra persona. Si usted recibe esta transmisi&oacute;n por error, por favor
                                        notifique inmediatamente al remitente por esta v&iacute;a.
                                    </div>
                                </div>
                            </div>
                        </body>
                    </html>";

            $mail->Subject = 'SET Recibo de Viaje';
            $mail->Body = $body;


            if ($mail->send())
            {
                $response['codigo'] = 1000;
            }
            else
            {
                $response['codigo'] = 2027;
            }
        }
        else
        {
            $response['codigo'] = 2026; // El viaje debe estar en estatus cotizado para marcarlo como iniciado
        }

        return $this->resultDetails($response);
    }

    public function obtenerRuta(){
        $request = "https://maps.googleapis.com/maps/api/directions/json?" .
                "origin=" . Input::get("origen") . "&" .
                "destination=" . Input::get("destino") . "&" .
                "sensor=false&" .
                // "alternatives=true&" .
                "key=" . Config::get('services.google-apikey');

        $response = json_decode(file_get_contents($request));

        if(($response->routes) == 0){
            return ["success" => false, "error" => "no_routes"];
        }

        return [
            "success" => true,
            "polyline" => $response->routes[0]->overview_polyline->points,
            "duration" => $response->routes[0]->legs[0]->duration
        ];
    }

}
