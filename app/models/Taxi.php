<?php

use Illuminate\Database\Eloquent\SoftDeletingTrait;

class Taxi extends Eloquent
{

    use SoftDeletingTrait;

    protected $table = 'taxi';
    
    public function sitio()
    {
        return $this->hasOne('Sitio', 'id', 'id_sitio');
    }

}
