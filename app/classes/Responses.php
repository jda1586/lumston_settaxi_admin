<?php

class Responses
{

    public static $codes = array(
        // Codigos exitosos

        1000 => 'Operacion finalizada correctamente',
        // Codigos de error
        2000 => 'Parametros vacios o incompletos',
        2001 => 'Usuario y o password invalidos',
        2002 => 'El usuario no es de tipo taxista',
        2003 => 'No hay un taxista asociado a este usuario',
        2004 => 'No hay taxis asociados al taxista',
        2005 => 'Hubo un error al iniciar sesion, intente de nuevo',
        2006 => 'La sesion es invalida o ha expirado',
        2007 => 'El usuario no existe',
        2008 => 'El taxi no existe o no esta asignado al taxista actual',
        2009 => 'No hay un taxi seleccionado para esta sesion',
        2010 => 'El formato de geolocalizacion es incorrecto',
        2011 => 'Los puntos de origen y destino tienen que ser diferentes',
        2012 => 'Hubo un error al comunicarse con el servidor de geolocalizacion',
        2013 => 'Hubo un error al crear el viaje, intente de nuevo',
        2014 => 'El id viaje tiene un formato incorrecto',
        2015 => 'Viaje no encontrado',
        2016 => 'El viaje no coincide con el taxi seleccionado para esta sesion',
        2017 => 'El viaje debe estar en estatus cotizado para marcarlo como iniciado',
        2018 => 'Hubo un error al iniciar el viaje, intente de nuevo',
        2019 => 'El viaje ya se encuentra marcado como taxi libre',
        2020 => 'Hubo un error al cambiar el viaje a taxi libre',
        2021 => 'No se pudo guardar la posicion actual de taxi libre',
        2022 => 'El viaje debe estar en estatus iniciado para marcarlo como taxi libre',
        2023 => 'El viaje debe estar marcado como taxi libre para actualizar la ubicacion',
        2024 => 'El viaje debe estar en estatus iniciado para marcarlo como finalizado',
        2025 => 'Hubo un error al cambiar el viaje a finalizado',
        2026 => 'El viaje debe estar finalizado para enviar email',
        2027 => 'Hubo un error al enviar email',
        2028 => 'Falta parametro incremental',
        2029 => 'La operacion ha sido guardada anteriormente y no puede duplicarse'
    );

}
