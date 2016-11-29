<?php

class ResponsesV2
{
    public static $defaultCode = 2999;
    public static $codes = array(
        // Codigos exitosos

        1000 => 'Operacion finalizada correctamente',
        1001 => 'Cliente WS Conectado',
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
        2011 => 'Elija primero un destino para poder cotizar',
        2012 => 'Hubo un error al comunicarse con el servidor de geolocalizacion',
        2013 => 'Hubo un error al crear el viaje, intente de nuevo',
        2014 => 'El id viaje tiene un formato incorrecto',
        2015 => 'Viaje no encontrado',
        2016 => 'El viaje no coincide con el taxi seleccionado para esta sesion',
        2017 => 'El viaje debe estar en estatus cotizado o aceptado para marcarlo como iniciado',
        2018 => 'Hubo un error al iniciar el viaje, intente de nuevo',
        2019 => 'El viaje ya se encuentra marcado como taxi libre',
        2020 => 'Hubo un error al cambiar el viaje a taxi libre',
        2021 => 'No se pudo guardar la posicion actual de taxi libre',
        2022 => 'El viaje debe estar en estatus aceptado, cotizado o iniciado para marcarlo como taxi libre',
        2023 => 'El viaje debe estar marcado como taxi libre para actualizar la ubicacion',
        2024 => 'El viaje debe estar en estatus iniciado para marcarlo como finalizado',
        2025 => 'Hubo un error al cambiar el viaje a finalizado',
        2026 => 'El viaje debe estar finalizado para enviar email',
        2027 => 'Hubo un error al enviar email',
        2050 => 'Tiene que proporcionar el token del dispositivo (token) y el sistema operativo (os)',
        2051 => 'El formato de email es incorrecto',
        2052 => 'El formato de nombre es incorrecto. Solo se aceptan letras, espacios y numeros. 3-50 caracteres',
        2053 => 'El formato de password es incorrecto. Debe contener 6-20 caracteres, al menos un numero y una letra mayuscula.',
        2054 => 'Ya existe un usuario con el email indicado.',
        2055 => 'Hubo un error al registrar el usuario.',
        2056 => 'Error de conexión con la API con Facebook',
        2057 => 'Error de conexión con Facebook',
        2060 => 'El usuario no es tipo cliente.',
        2061 => 'El password actual indicado es incorrecto.',
        2062 => 'No se pudo actualizado el password del usuario.',
        2063 => 'El usuario no es un cliente',
        2064 => 'El viaje debe estar en estatus cotizado (por el cliente) para marcarlo como solicitado',
        2065 => 'El viaje debe estar en estatus solicitado (por el cliente) para marcarlo como aceptado',
        2066 => 'El viaje debe estar en estatus aceptado o cotizado para iniciarlo',
        2067 => 'El viaje debe estar en estatus aceptado o solicitado para cancelarlo',
        2068 => 'El viaje debe estar en estatus aceptado o iniciado para ver ubicacion',
        2069 => 'El viaje debe estar en estatus finalizado o iniciado para calificar',
        2070 => 'La calificacion debe estar entre 1 y 5',
        2071 => 'El viaje no pudo marcarse como aceptado',
        2072 => 'El viaje no pudo marcarse como cancelado',
        2073 => 'No se pudo establecer el destino del viaje',
        2074 => 'El viaje debe estar en estatus Solicitado o Aceptado para cambiar de destino',
        2075 => 'No se pudo actualizar la ubicacion',
        2076 => 'El usuario actual no es taxista o cliente de este viaje',
        2077 => 'No existe un usuario con el email indicado. Verifique el email.',
        2078 => 'Hubo un error al reestablecer el usuario. Intente de nuevo',
        2100 => 'Error al conectar cliente Ws. Verificar sesion',
        
        2999 => 'Ha ocurrido un error inesperado'
    );

}
