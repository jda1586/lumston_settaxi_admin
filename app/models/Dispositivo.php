<?php

use Illuminate\Database\Eloquent\SoftDeletingTrait;

class Dispositivo extends Eloquent
{

    use SoftDeletingTrait;
    protected $table = 'dispositivo';
    
    public static function registrarDispositivo($id_usuario, $token, $os)
    {
        $validToken = false;
        
        $dispUser = Dispositivo::where("token", $token)->where("id_usuario", $id_usuario)->first();
        if($dispUser != null)
        {
            if($dispUser->status == "inactivo")
            {
                // Activar dispositivo
                $dispUser->status = "activo";
                $dispUser->save();
            }
            $validToken = true;
        }
        else
        {
            $newDevice = new Dispositivo();
            $newDevice->id_usuario = $id_usuario;
            $newDevice->token = $token;
            $newDevice->os = $os;
            $newDevice->status = "activo";
            $newDevice->save();
            
            if($newDevice->id)
            {
                $validToken = true;
                
                // descativar el token para otros usuarios
                $anotherTokens = Dispositivo::where("token", $token)->where("status", "activo")->where("id_usuario", "<>", $id_usuario)->get();
                foreach($anotherTokens as $anotherToken)
                {
                    $anotherToken->status = "inactivo";
                    $anotherToken->save();
                }
            }
        }
        
        return $validToken;
    }

}
