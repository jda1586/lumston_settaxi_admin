<?php

use Illuminate\Database\Eloquent\SoftDeletingTrait;

class Propietario extends Eloquent
{

    use SoftDeletingTrait;

    protected $table = 'propietario';

    public function usuario()
    {
        return $this->hasOne('Usuario', 'id', 'id_usuario')->where('status', 'activo');
    }

}
