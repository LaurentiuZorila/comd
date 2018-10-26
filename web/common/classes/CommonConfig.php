<?php
/**
 * Class CommonConfig
 */

class CommonConfig
{
    public static function get($path = null)
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
        return $config;
    }

    public static function all()
    {
        static $config;
        if ($config === null) {
            $config = require __DIR__ . '/../core/common_config.php';
        }
        return $config;
    }
}