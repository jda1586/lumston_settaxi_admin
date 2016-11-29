<?php

class Functions
{

    private static $days = array(
        "Domingo",
        "Lunes",
        "Martes",
        "Miercoles",
        "Jueves",
        "Viernes",
        "Sabado");
    private static $months = array(
        "Enero",
        "Febrero",
        "Marzo",
        "Abril",
        "Mayo",
        "Junio",
        "Julio",
        "Agosto",
        "Septiembre",
        "Octubre",
        "Noviembre",
        "Diciembre");
    
    public static function datetimeNow()
    {
        return date("Y-m-d H:i:s");
    }
    
    public static function getDatetimeToAmPm($datetime)
    {
        $newDateTime = date('h:i A', strtotime($datetime));
        return $newDateTime;
    }
    public static function getMetersToKm($meters)
    {
        $km = $meters / 1000;
        return number_format($km, 2);
    }
    public static function getSecondsToMinH($seconds)
    {
        $val = $seconds . " Seg.";
        if($seconds >= 3600)
        {
            $val = number_format(($seconds / 3600), 2);
            $val .= " Horas";
        }
        else if($seconds >= 60)
        {
            $val = number_format(($seconds / 60), 2);
            $val .= " Min.";
        }
        return $val;
    }

    public static function tableToArray($table)
    {
        $rows = array();
        foreach ($table as $tableRow)
        {
            $row = array();
            foreach ($tableRow as $key => $value)
            {
                if (is_numeric($key) || $key == "id_user_create" || $key == "id_user_update" || $key == "date_created")
                {
                    continue;
                }
                $row[$key] = $value;
            }
            $rows[] = $row;
        }
        return $rows;
    }

    public static function dayOfWeek($n)
    {
        return self::$days[$n - 1];
    }

    public static function get_filesize($fileSize)
    {
        if ($fileSize > 1000000)
        {
            $fsize = $fileSize / 1048576;
            $fsize = round($fsize, 2);
            $fsize = $fsize . " MB";
        }
        else
        {
            $fsize = $fileSize / 1024;
            $fsize = round($fsize, 2);
            $fsize = $fsize . " KB";
        }
        return $fsize;
    }

    public static function shortDatetime($datetime)
    {
        $dtm = new DateTime($datetime);
        $y = $dtm->format('Y');
        $m = self::$months[$dtm->format('m') - 1];
        $d = $dtm->format('d');

        if ($y == '-0001')
        {
            return "--";
        }

        return $d . " de " . $m . " de " . $y;
    }

}

?>