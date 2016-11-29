<?php

use Illuminate\Database\Eloquent\SoftDeletingTrait;

class Sesion extends Eloquent
{

    use SoftDeletingTrait;

    protected $table = 'sesion';

    public static function getBySessionId($sessionId)
    {
        $session = static::where('session_identifier', $sessionId)->where('status', 'active')->first();
        return $session;
    }

    public static function createSession($usuario, $idTaxi = null, $tipo = "App")
    {
        $newSession = new Sesion;

        $newSession->id_usuario = $usuario->id;
        $newSession->tipo = $usuario->rol->nombre;
        $newSession->username = $usuario->username;
        $newSession->foto = $usuario->foto;
        $newSession->email = $usuario->email;
        $newSession->nombre = $usuario->nombre;
        $newSession->apellidos = $usuario->apellidos;
        $newSession->id_taxi = $idTaxi;
        $newSession->login_tipo = 'App';
        $newSession->status = 'active';
        $newSession->save();

        if ($newSession->id)
        {
            return Sesion::find($newSession->id);
        }

        return false;
    }

}
