<?php

class Rates
{

    private static $dayHours = array(
        "ini_hour" => 6,
        "ini_minute" => 0,
        "end_hour" => 21,
        "end_minute" => 59
    );
    private static $dayRates = array(
        "tarifa" => "Día",
        "minuto" => 1.00,
        "km" => 6.45,
        "base" => 8.50,
        "min" => 30
    );
    private static $nightRates = array(
        "tarifa" => "Noche",
        "minuto" => 1.50,
        "km" => 7.00,
        "base" => 9.00,
        "min" => 30
    );

    public static function getRates($datetime = false)
    {
        $hourMin = date('H:i');
        if($datetime)
        {
            $hourMin = substr($datetime, 11, 5);
        }

        $hourMinArray = explode(":", $hourMin);
        if (!is_array($hourMinArray) || sizeof($hourMinArray) != 2)
        {
            return false;
        }

        $hour = $hourMinArray[0];
        $min = $hourMinArray[1];
        if(!is_numeric($hour) || !is_numeric($min))
        {
            return false;
        }

        if(($hour < self::$dayHours["ini_hour"]) || ($hour > self::$dayHours["end_hour"]))
        {
            return self::$nightRates;
        }
        return self::$dayRates;
    }


    public static function getRateEstimation($duration, $distance, $day = true, $minRate = false)
    {
        $minutes = $duration / 60;
        $kms = $distance / 1000;

        $dayRate = true;
        if(!$day || ($day === "Noche"))
        {
            $dayRate = false;
        }

        $rate = ($dayRate == true) ? self::$dayRates : self::$nightRates;
        $cost = $rate["base"] + (($minutes * $rate["minuto"]) + ($kms * $rate["km"]));
        if($minRate)
        {
            if($cost < $rate["min"])
            {
                $cost = $rate["min"];
            }
        }
        return $cost;
    }

    public static function getMinRate($day)
    {
        $dayRate = true;
        if(!$day || ($day === "Noche"))
        {
            $dayRate = false;
        }

        $rate = ($dayRate) ? self::$dayRates : self::$nightRates;
        $minRate = $rate["min"];
        return $minRate;
    }

}
