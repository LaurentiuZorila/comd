<?php

/**
 * Class Config
 */
class Config
{
    public static function get($path = null, $tokken = false)
    {
        if (!$path) {
            return null;
        }

        $config = static::all();
        $path   = explode('/', $path);

        foreach ($path as $bit) {
            if (isset($config[$bit])) {
                $config = $config[$bit];
            }
        }
        return $tokken ? $config . '?r=' . Tokens::getRoute() : $config;
    }

    public static function all()
    {
        static $config;
        if ($config === null) {
            $config = require __DIR__ . '/../core/config.php';
        }
        return $config;
    }
}


