<?php
/**
 * Class CommonConfig
 */
class CommonConfig
{
    /**
     * @param null $path
     * @return mixed|null
     */
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


    /**
     * @return mixed
     */
    public static function all()
    {
        static $config;
        if ($config === null) {
            $config = require __DIR__ . '/../core/common_config.php';
        }
        return $config;
    }
}