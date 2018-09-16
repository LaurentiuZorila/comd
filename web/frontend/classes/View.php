<?php

class View
{
    public static function render()
    {
        $path = self::name();
        include '../view/'.$path;
    }

    public static function name()
    {
        return basename($_SERVER["SCRIPT_FILENAME"]);
    }
}