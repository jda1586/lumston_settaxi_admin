<?php

use Illuminate\Database\Eloquent\SoftDeletingTrait;

class Sitio extends Eloquent
{

    use SoftDeletingTrait;

    protected $table = 'sitio';

}
