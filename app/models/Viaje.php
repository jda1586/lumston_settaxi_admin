<?php

use Illuminate\Database\Eloquent\SoftDeletingTrait;

class Viaje extends Eloquent
{

    use SoftDeletingTrait;

    protected $table = 'viaje';

    public static function getById($idViaje)
    {
        $viaje = static::where('id', $idViaje)->first();
        return $viaje;
    }

}
