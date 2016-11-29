<?php

use Illuminate\Database\Eloquent\SoftDeletingTrait;

class Vtaxi extends Eloquent
{

    use SoftDeletingTrait;

    protected $table = 'vtaxi';

}
