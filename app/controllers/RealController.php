<?php

class RealController extends BaseController
{

    public function __construct()
    {
        $this->beforeFilter(function()
        {
            if (!$this->authorize())
            {
                return Redirect::to("/login");
            }

            if ($this->user->rol->id != 1 && $this->user->rol->id != 3)
            {
                //return Redirect::to("/taxis");
            }
        });
    }

    public function getIndex()
    {
        $this->layout->section = "Realtime";

        $this->layout->content = View::make('realtime.realtime', array(
        ));
    }
    
    public function postIndex()
    {
        $activeCabs = Taxi::where("status", "activo")->get();
        $response = array("result" => "ok", "cabs" => $activeCabs);
        return $response;
    }

}
