<?php

use Illuminate\Database\Eloquent\SoftDeletingTrait;

class Ciudad extends Eloquent
{

    use SoftDeletingTrait;

    protected $table = 'ciudad';

}
