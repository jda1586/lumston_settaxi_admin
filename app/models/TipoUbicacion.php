<?php

use Illuminate\Database\Eloquent\SoftDeletingTrait;

class TipoUbicacion extends Eloquent
{

    use SoftDeletingTrait;

    protected $table = 'tipo_ubicacion';

}
