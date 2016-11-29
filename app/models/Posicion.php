<?php

use Illuminate\Database\Eloquent\SoftDeletingTrait;

class Posicion extends Eloquent
{

    use SoftDeletingTrait;

    protected $table = 'posicion';
    
    public static function getByViajeId($idViaje)
    {
        $positions = static::where('id_viaje', $idViaje)->get();
        return $positions;
    }
    public static function getLastByViajeId($idViaje)
    {
        $positions = static::where('id_viaje', $idViaje)->orderBy('id', 'desc')->first();
        return $positions;
    }

}
