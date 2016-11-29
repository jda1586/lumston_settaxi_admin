<?php

class Validate
{

    private static $patterns = array(
        "name" => "/^([a-zA-Z0-9 .ñáéíóúÑÁÉÍÓÚ]{3,60})*$/",
        "password" => "/(?=^.{6,20}$)(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?!.*\s)[0-9a-zA-Z!@#$%^&*()]*$/"
    );

    public static function password($password, $level = "default")
    {
        if (!preg_match(self::$patterns['password'], $password))
        {
            return false;
        }
        return true;
    }

    public static function name($name, $level = "default")
    {
        if (!preg_match(self::$patterns['name'], $name))
        {
            return false;
        }
        return true;
    }

    public static function email($email, $level = "default")
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL))
        {
            return false;
        }
        return true;
    }

}
