<?php

Class LatLng
{

    private $lat = null;
    private $lng = null;
    private $valid = false;

    public function __construct($latLngStr)
    {
        $latLngArr = explode(",", $latLngStr);
        if(is_array($latLngArr) && sizeof($latLngArr) == 2)
        {
            $pLat = $latLngArr[0];
            $pLng = $latLngArr[1];
            
            if(is_numeric($pLat) && $pLat != 0 && is_numeric($pLng) && $pLng != 0)
            {
                $this->lat = $pLat;
                $this->lng = $pLng;
                $this->valid = true;
            }
        }
    }
    
    public function isValid()
    {
        return $this->valid;
    }
    
    public function lat()
    {
        return $this->lat;
    }
    
    public function lng()
    {
        return $this->lng;
    }

}
