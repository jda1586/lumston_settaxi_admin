<?php

use Illuminate\Database\Eloquent\SoftDeletingTrait;

class Ubicacion extends Eloquent
{

    use SoftDeletingTrait;

    protected $table = 'ubicacion';

}
