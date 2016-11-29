<?php

use Illuminate\Database\Eloquent\SoftDeletingTrait;

class Usuario extends Eloquent
{

    use SoftDeletingTrait;

    protected $table = 'usuario';

    public static function login($username, $password)
    {
        $user = static::where('username', $username)
                ->where('password', DB::raw("PASSWORD('{$password}')"))
                ->where('status', 'activo')->first();

        return $user;
    }
    

    public static function loginFacebook($username, $password)
    {
        $user = static::where('username', $username)->where("tipo_registro", "fb")->where("id_rol", 7)
                ->where('password', DB::raw("PASSWORD('{$password}')"))
                ->where('status', 'activo')->first();

        return $user;
    }

    public function rol()
    {
        return $this->hasOne('Rol', 'id', 'id_rol');
    }

    public function taxista()
    {
        return $this->hasOne('Taxista', 'id_usuario', 'id');
    }

}
