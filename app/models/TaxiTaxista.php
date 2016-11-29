<?php

use Illuminate\Database\Eloquent\SoftDeletingTrait;

class TaxiTaxista extends Eloquent
{

    use SoftDeletingTrait;

    protected $table = 'taxi_taxista';

    public function taxi()
    {
        $taxi = $this->belongsTo('Taxi', 'id_taxi', 'id')->where('status', 'activo');
        
        return $taxi;
    }
}
