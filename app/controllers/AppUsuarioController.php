<?php

class AppUsuarioController extends \Controller
{

    public function getMapDetails()
    {
        $response = array(
            'codigo' => 2000
        );
        if (!Input::has('idViaje'))
        {
            return $this->resultDetails($response, 2000);
        }

        $idViaje = Input::get('idViaje');
        $viaje = Viaje::find($idViaje);

        if ($viaje == null)
        {
            return $this->resultDetails($response, 2015);
        }

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
            "tarifa" => Rates::getRates($viaje->created_at),
            "posiciones" => array()
        );
        $positions = Posicion::where("id_viaje", $viaje->id)->get();
        foreach ($positions as $p)
        {
            $response['viaje']['posiciones'][] = array(
                "id" => $p->id,
                "lat" => $p->lat,
                "lng" => $p->lng,
                "distancia" => $p->distancia,
                "created" => $p->created_at . ""
            );
        }

        return $this->resultDetails($response, 1000);
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

        $taxi = Taxi::find($sesion->id_taxi);
        $sitio = Sitio::find($taxi->id_sitio);

        $idViaje = Input::get('idViaje');

        // Validar id viaje
        if (!is_numeric($idViaje) || !$idViaje)
        {
            return $this->resultDetails($response, 2014);
        }

        $viaje = Viaje::where('id', $idViaje)->where('id_sesion', $sesion->id)->first();
        if ($viaje == null)
        {
            return $this->resultDetails($response, 2015); //Viaje no encontrado
        }

        if ($viaje->status == "finalizado")
        {
            $viaje->email_cliente = Input::get('email');
            $viaje->save();

            // Send Email

            $mail = new PHPMailer();
            $mailFrom = 'viajes@setapp.testingweb.mx';

            //$mail->SMTPDebug = 3;                               // Enable verbose debug output

            $mail->isSMTP();                                      // Set mailer to use SMTP
            $mail->Host = 'mail.tejuino.mx';  // Specify main and backup SMTP servers
            $mail->SMTPAuth = true;                               // Enable SMTP authentication
            $mail->Username = $mailFrom;                 // SMTP username
            $mail->Password = 'stpP_2425';                           // SMTP password
            $mail->SMTPSecure = 'starttls';                            // Enable TLS encryption, `ssl` also accepted
            $mail->Port = 587;                                    // TCP port to connect to

            $mail->SMTPOptions = array(
                'ssl' => array(
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true));

            $mail->From = $mailFrom;
            $mail->FromName = 'SetApp.mx';
            $mail->addAddress($viaje->email_cliente);     // Add a recipient
            $mail->addCC("facturas@setapp.mx", "Facturas SET");     // Add a recipient
            $mail->addCC("rafael@tejuino.mx", "Alfaro");     // Add a recipient

            $mail->isHTML(true);                                  // Set email format to HTML

            $viajeHoraIni = Functions::getDatetimeToAmPm($viaje->hora_inicio);
            $viajeHoraEnd = Functions::getDatetimeToAmPm($viaje->hora_final);
            $viajeKm = Functions::getMetersToKm($viaje->distancia_real);
            $viajeTiempo = Functions::getSecondsToMinH($viaje->tiempo_real);
            $viajeTaxiLibre = ($viaje->taxi_libre == 1) ? "Si" : "No";
            $curRate = Rates::getRates($viaje->created_at);
            $tarifa = ($curRate["tarifa"] == "Día") ? "D&iacute;a" : "Noche";
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

            $body = "<html>
                        <head>
                            <title>SET Recibo de Viaje</title>
                        </head>
                        <body style='width: 100%; height: auto; margin: 0; padding: 20px 100px; background: #ffe789;font-family:Arial,sans-serif;color:#002f8a;'>
                            <div style='width:600px;height:auto;background:#fff;overflow:hidden;'>
                                <div style='display:block;clear:both;width:100%;height:110px;background:#efefef url(http://setapp.testingweb.mx/ticket.png) 40px 32px no-repeat;line-height:110px;text-indent:100px;font-size:38px;'>
                                    SET Recibo de Viaje
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

    public function __construct()
    {
        
    }

    public function finalizarViaje()
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

        // Validar id viaje
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
            return $this->resultDetails($response, 2024);
        }

        $posicionLatLng = new LatLng(Input::get('ubicacionActual'));
        if (!$posicionLatLng->isValid())
        {
            return $this->resultDetails($response, 2010);
        }

        // Get new estimations
        $origen = new LatLng($viaje->origen_lat . "," . $viaje->origen_lng);
        $positions = Posicion::getByViajeId($viaje->id);
        $distanceFromLastPoint = 0;

        $secondsDiff = strtotime(Functions::datetimeNow()) - strtotime($viaje->hora_inicio);
        $viaje->tiempo_real = $secondsDiff;
        $curRate = Rates::getRates($viaje->created_at);
        $dayRate = ($curRate["tarifa"] == "Día") ? true : false;
        $viaje->hora_final = Functions::datetimeNow();

        if ($viaje->taxi_libre == 1)
        {
            if (Input::has('gps'))
            {
                // Get distance between 2 points
                $lastPosition = Posicion::getLastByViajeId($viaje->id);
                $distanceFromLastPoint = MapServices::getDistanceBetween($lastPosition->lat, $lastPosition->lng, $posicionLatLng->lat(), $posicionLatLng->lng());

                $totalPointDistance = 0;
                $positions = Posicion::where("id_viaje", $viaje->id)->get();
                foreach ($positions as $p)
                {
                    $totalPointDistance += $p->distancia;
                }
                $totalPointDistance += $distanceFromLastPoint;

                $viaje->distancia_real = $viaje->distancia_estimada + $totalPointDistance;
                $viaje->costo_real = Rates::getRateEstimation($viaje->tiempo_real, $viaje->distancia_real, $dayRate);
                $gpsDetails = true;
            }
            else
            {
                $routeDetails = MapServices::getRouteDetails($origen, $posicionLatLng, $positions);
                $viaje->destino_lat = $posicionLatLng->lat();
                $viaje->destino_lng = $posicionLatLng->lng();
                if ($routeDetails)
                {
                    // Tiempo estimado ya no es obtenido de gmaps si es taxi libre
                    $viaje->tiempo_estimado = $routeDetails->duration;

                    $viaje->distancia_estimada = $routeDetails->distance;
                    $viaje->costo_estimado = Rates::getRateEstimation($viaje->tiempo_estimado, $routeDetails->distance, $dayRate);
                    $viaje->costo_real = Rates::getRateEstimation($viaje->tiempo_real, $routeDetails->distance, $dayRate);
                }
            }
        }
        else
        {
            $viaje->costo_real = $viaje->costo_estimado;
            $viaje->distancia_real = $viaje->distancia_estimada;
        }
        
        // Round
        $viaje->costo_redondeado = ceil($viaje->costo_real);
        $costoRedondeado = $viaje->costo_redondeado;
        

        $viaje->destino_real_lat = $posicionLatLng->lat();
        $viaje->destino_real_lng = $posicionLatLng->lng();
        $viaje->status = "finalizado";

        $viaje->save();
        $viaje = Viaje::find($viaje->id);
        if ($viaje == null)
        {
            return $this->resultDetails($response, 2025);
        }

        $posicion = new Posicion();
        $posicion->id_viaje = $viaje->id;
        $posicion->lat = $posicionLatLng->lat();
        $posicion->lng = $posicionLatLng->lng();
        $posicion->distancia = $distanceFromLastPoint;
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
                "costo_estimado" => $costoRedondeado,
                "costo_real" => $costoRedondeado,
                "distancia_estimada" => $viaje->distancia_estimada,
                "tarifa" => Rates::getRates($viaje->created_at),
                "posicion_actual" => array(
                    "lat" => $posicion->lat,
                    "lng" => $posicion->lng
                )
            );
            if (isset($gpsDetails))
            {
                $response['viaje']['distancia_real'] = $viaje->distancia_real;
                $response['viaje']['posicion_actual']['distancia_recorrida'] = $posicion->distancia;
            }
            $response['codigo'] = 1000;
        }
        else
        {
            $viaje->taxi_libre = 0;
            $viaje->save();
            $response['codigo'] = 2021;
        }

        return $this->resultDetails($response);
    }

    public function actualizarPosicion()
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

        // Validar id viaje
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

        if ($viaje->taxi_libre != 1)
        {
            return $this->resultDetails($response, 2023);
        }

        $posicionLatLng = new LatLng(Input::get('ubicacionActual'));
        if (!$posicionLatLng->isValid())
        {
            return $this->resultDetails($response, 2010);
        }

        $incremental = 0; // Default incremental value
        // Get incremental parameter
        if (Input::has("incremental"))
        {
            //
            // Get if this operation is currently saved
            $incremental = Input::Get("incremental");

            $savedPosition = Posicion::where("id_viaje", $viaje->id)->where("incremental", $incremental)->first();

            if ($savedPosition != null)
            {
                return $this->resultDetails($response, 2029); // Error: error al guardar una operacion duplicada
            }
        }
        else
        {
            //return $this->resultDetails($response, 2028); // Error: falta parametro incremental
        }


        // Get new estimations
        $origen = new LatLng($viaje->origen_lat . "," . $viaje->origen_lng);
        $positions = Posicion::getByViajeId($viaje->id);
        $distanceFromLastPoint = 0;

        $omitirPuntoActual = false;



        // ------------------
        // 
        // Get distance between 2 points
        $lastPosition = Posicion::getLastByViajeId($viaje->id);

        if ($lastPosition->incremental > $incremental)
        {
            //return $this->resultDetails($response, 1000); // Don't save this point, but it's ok
            return $this->resultDetails($response, 2029);
            $omitirPuntoActual = true;
        }
        else
        {
            $distanceFromLastPoint = MapServices::getDistanceBetween($lastPosition->lat, $lastPosition->lng, $posicionLatLng->lat(), $posicionLatLng->lng());

            $viaje->destino_real_lat = $posicionLatLng->lat();
            $viaje->destino_real_lng = $posicionLatLng->lng();

            $curRate = Rates::getRates($viaje->created_at);
            $dayRate = ($curRate["tarifa"] == "Día") ? true : false;

            $totalPointDistance = 0;
            $positions = Posicion::where("id_viaje", $viaje->id)->get();
            foreach ($positions as $p)
            {
                $totalPointDistance += $p->distancia;
            }
            $totalPointDistance += $distanceFromLastPoint;

            $secondsDiff = strtotime(Functions::datetimeNow()) - strtotime($viaje->hora_inicio);
            $viaje->tiempo_real = $secondsDiff;
            $viaje->hora_final = Functions::datetimeNow();
            $viaje->distancia_real = $viaje->distancia_estimada + $totalPointDistance;
            $viaje->costo_real = Rates::getRateEstimation($viaje->tiempo_real, $viaje->distancia_real, $dayRate);
            $gpsDetails = true;
        }

        // ------------------

        $viaje->save();
        $viaje = Viaje::find($viaje->id);
        if ($viaje == null || $viaje->taxi_libre != 1)
        {
            return $this->resultDetails($response, 2020);
        }
        if (!$omitirPuntoActual)
        {
            $posicion = new Posicion();
            $posicion->id_viaje = $viaje->id;
            $posicion->lat = $posicionLatLng->lat();
            $posicion->lng = $posicionLatLng->lng();
            $posicion->distancia = $distanceFromLastPoint;
            $posicion->incremental = $incremental;

            $posicion->gps = 2;
            $posicion->timestamp = 0;

            if (Input::has('gps') && Input::has('timestamp'))
            {
                $gps = Input::Get("gps");
                $posicion->gpsstr = Input::Get("gps");
                if ($gps == 1 || $gps == "true" || $gps == "True")
                {
                    $posicion->gps = 1;
                }
                else
                {
                    $posicion->gps = 0;
                }
                $posicion->timestamp = Input::Get("timestamp");
            }

            $posicion->save();
        }
        else
        {
            $posicion = Posicion::getLastByViajeId($viaje->id);
        }

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
                "tarifa" => Rates::getRates($viaje->created_at),
                "posicion_actual" => array(
                    "lat" => $posicion->lat,
                    "lng" => $posicion->lng,
                    "gps" => $posicion->gps,
                    "timestamp" => $posicion->timestamp
                )
            );
            if (isset($gpsDetails))
            {
                $response['viaje']['distancia_real'] = $viaje->distancia_real;
                $response['viaje']['posicion_actual']['distancia_recorrida'] = $posicion->distancia;
            }
            $response['codigo'] = 1000;
        }
        else
        {
            $viaje->taxi_libre = 0;
            $viaje->save();
            $response['codigo'] = 2021;
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

        if ($viaje->status == "cotizado")
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
            $response['codigo'] = 2017; // El viaje debe estar en estatus cotizado para marcarlo como iniciado
        }

        return $this->resultDetails($response);
    }

    public function nuevoViaje()
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

        // Validar que sean diferentes
        if (($origen->lat() == $destino->lat()) || ($origen->lng() == $destino->lng()))
        {
            return $this->resultDetails($response, 2011); // Los puntos origen y destino deben ser diferentes
        }

        // Crear viaje ------------------------------------------------------------------------------------------

        $viaje = new Viaje();
        $viaje->id_sesion = $sesion->id;
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
            $dayRate = ($curRate["tarifa"] == "Día") ? true : false;
            $viaje->tiempo_estimado = $routeDetails->duration;
            $viaje->distancia_estimada = $routeDetails->distance;
            $viaje->costo_estimado = Rates::getRateEstimation($routeDetails->duration, $routeDetails->distance, $dayRate);
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

    public function logout()
    {
        $response = array(
            'codigo' => 2000
        );
        if (Input::has('idSesion'))
        {
            $sesion = Sesion::getBySessionId(Input::get('idSesion'));
            if ($sesion != null)
            {
                $sesion->status = 'finalizada';
                $sesion->save();
                $response['codigo'] = 1000;
            }
            else
            {
                $response['codigo'] = 2006; // Sesion invalida o expirada
            }
        }
        else
        {
            $response['codigo'] = 2000; // Parametros invalidos
        }
        return $this->resultDetails($response);
    }

    public function elegirTaxi()
    {
        $response = array(
            'codigo' => 2000
        );

        if (!Input::has('idSesion') || !Input::has('idTaxi'))
        {
            return $this->resultDetails($response, 2000); // Parametros invalidos
        }
        $sesion = Sesion::getBySessionId(Input::get('idSesion')); // Get session
        $taxiId = (int) Input::get('idTaxi');
        if ($sesion == null)
        {
            return $this->resultDetails($response, 2006); // Sesion invalida o expirada
        }

        $usuario = Usuario::find($sesion->id_usuario); // Buscar usuario
        if ($usuario == null || $usuario->tipo != "taxista") // Verificar sea de tipo taxista
        {
            return $this->resultDetails($response, 2007);
        }

        $taxiElegido = null;
        $taxista = $usuario->taxista;
        $taxisTaxistas = $taxista->taxis;
        foreach ($taxisTaxistas as $taxi)
        {
            if ($taxi->id == $taxiId)
            {
                $taxiElegido = $taxi;
            }
        }

        if ($taxiElegido != null)
        {
            $sesion->id_taxi = $taxiElegido->id;
            $sesion->save();
            $response['numero_economico'] = $taxiElegido->numero_economico;
            $response['placas'] = $taxiElegido->placas;
            $response['sitio_numero'] = $taxiElegido->sitio->numero;
            $response['sitio_nombre'] = $taxiElegido->sitio->nombre;
            $response['codigo'] = 1000;
        }
        else
        {
            $response['codigo'] = 2008;
        }

        return $this->resultDetails($response);
    }

    public function login()
    {
        $response = array(
            'codigo' => 2000
        );
        $sesion = array("idSesion" => "");
        if (!Input::has('username') || !Input::has('password'))
        {
            return $this->resultDetails($response, 2000); // ERROR: Parametros !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
        }

        $usuario = Usuario::login(Input::get('username'), Input::get('password'));
        if ($usuario == null || $usuario->tipo != "taxista")
        {
            if ($usuario == null)
            {
                return $this->resultDetails($response, 2001); // ERROR: Usuario !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
            }
            else
            {
                return $this->resultDetails($response, 2002);  // ERROR: No es taxista !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
            }
        }

        $taxista = $usuario->taxista;
        if ($taxista == null)
        {
            return $this->resultDetails($response, 2003);  // ERROR: No hay taxista asociado !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
        }

        // Validar taxista y taxis
        if ($taxista->taxis->count() == 0)
        {
            return $this->resultDetails($response, 2004);  // ERROR: No hay taxis asignados
        }

        // Crear sesion
        $newSession = new Sesion;
        $newSession->id_usuario = $usuario->id;
        $newSession->id_taxi = $taxista->taxis[0]->id;
        $newSession->login_tipo = 'App';
        $newSession->username = $usuario->username;
        $newSession->status = 'active';
        $newSession->save();

        if ($newSession->id)
        {
            $newSession = Sesion::find($newSession->id);
        }

        if ($newSession != null && $newSession->id)
        {
            $sesion['idSesion'] = $newSession->session_identifier;
            $sesion['id_taxi'] = $newSession->id_taxi;
            $sesion['sitio_numero'] = $taxista->taxis[0]->sitio->numero;
            $sesion['sitio_nombre'] = $taxista->taxis[0]->sitio->nombre;
            //$sesion['id_taxista'] = $taxista->id;

            $sesion['usuario'] = array(
                'tipo' => $usuario->tipo,
                'username' => $usuario->username,
                'email' => $usuario->email
            );
            $sesion['taxista'] = array(
                'nombre' => $taxista->nombre,
                'apellidos' => $taxista->apellidos,
                'telefono' => $taxista->telefono,
                'licencia' => $taxista->licencia,
                'foto' => uploads_url($taxista->foto)
            );
            $sesion['taxis'] = $taxista->taxis;

            $response['codigo'] = 1000;
        }
        else
        {
            $response['codigo'] = 2005;
        }

        $response['sesion'] = $sesion;
        return $this->resultDetails($response);
    }

    private function resultDetails($response, $resultCode = null)
    {
        if ($resultCode)
        {
            $response['codigo'] = $resultCode;
        }
        $response['mensaje'] = Responses::$codes[$response['codigo']];
        return $response;
    }

}
