<?php

class RouteDetails
{

    public $origin;
    public $destination;
    public $distance = 0; // Metres
    public $duration = 0; // Seconds
    public $polyline;

    public function __construct($jsonObj)
    {
        if (!isset($jsonObj->routes[0]->legs[0]))
        {
            return false;
        }

        foreach ($jsonObj->routes[0]->legs as $leg)
        {
            $this->distance += $leg->distance->value;
            $this->duration += $leg->duration->value;
        }

        $this->polyline = $jsonObj->routes[0]->overview_polyline->points;

        /* foreach($jsonObj->routes[0]->legs[0]->steps as $step)
          {
          $this->distance += $step->distance->value;
          $this->duration += $step->duration->value;
          } */

        if (is_numeric($this->distance) && is_numeric($this->duration))
        {
            return $this;
        }
        return false;
    }

}
