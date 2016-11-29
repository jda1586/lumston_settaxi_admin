<?php

use Illuminate\Database\Eloquent\SoftDeletingTrait;

class VUsuario extends Eloquent
{
    use SoftDeletingTrait;
    
    protected $table = 'v_usuario';
}
