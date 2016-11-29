<?php

//namespace App\Controllers\

class ApiBaseController extends Controller
{

    protected function resultDetails($response, $resultCode = null)
    {
        if ($resultCode)
        {
            $response['codigo'] = $resultCode;
        }
        $response['mensaje'] = ResponsesV2::$codes[$response['codigo']];
        return $response;
    }

}
