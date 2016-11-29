<?php

use Illuminate\Database\Eloquent\SoftDeletingTrait;

class Taxista extends Eloquent
{

    use SoftDeletingTrait;

    protected $table = 'taxista';

    public function taxis()
    {
        //$taxiTaxistas = $this->hasMany('TaxiTaxista', 'id_taxista', 'id')->where('status', 'activo');
        $taxis = $this->belongsToMany('Taxi', 'taxi_taxista', 'id_taxista', 'id_taxi');
        return $taxis;
    }

    public function usuario()
    {
        return $this->hasOne('Usuario', 'id', 'id_usuario')->where('status', 'activo');
    }

}
