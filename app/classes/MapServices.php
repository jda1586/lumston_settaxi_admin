<?php

class MapServices
{

    private static $apiKey = "AIzaSyCwOsCKAPOdasEGd8Dp-zTgW_e3jUq4XnM";
    private static $mapBaseUrl = "https://maps.googleapis.com/maps/api/directions/json?";
    private static $maxWaypoints = 8;

    public static function getRouteDetails($origin, $destination, $positions = false)
    {
        $wayPoints = "";
        if ($positions)
        {
            $numPositions = sizeof($positions);
            if ($numPositions <= self::$maxWaypoints)
            {
                foreach ($positions as $position)
                {
                    $wayPoints .= $position->lat . "," . $position->lng . "|";
                }
            }
            else
            {
                $wpStep = floor(sizeof($positions) / self::$maxWaypoints);
                $wpAdded = 0;

                for ($i = 0; $i < sizeof($positions); $i += $wpStep)
                {
                    if ($wpAdded < self::$maxWaypoints)
                    {
                        $wayPoints .= $positions[$i]->lat . "," . $positions[$i]->lng . "|";
                        $wpAdded++;
                    }
                }
            }
        }

        $requestURL = self::$mapBaseUrl . "key=" . self::$apiKey
            . "&origin=" . $origin->lat() . "," . $origin->lng()
            . "&destination=" . $destination->lat() . "," . $destination->lng()
            . "&waypoints=" . $wayPoints;

        $json = json_decode(file_get_contents($requestURL));

        if ($routeDetails = new RouteDetails($json))
        {
            return $routeDetails;
        }
        return false;
    }

    public static function getDistanceBetween($lat1, $lon1, $lat2, $lon2)
    {
        $theta = $lon1 - $lon2;
        $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
        $dist = acos($dist);
        $dist = rad2deg($dist);
        $miles = $dist * 60 * 1.1515;
        return ($miles * 1609.344);
    }

}
