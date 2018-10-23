<?php

/**
 * Class Input
 */
class Input
{

    /**
     * @param string $type
     * @return bool
     */
    public static function exists($type = 'post')
    {
        switch ($type) {
            case 'post':
                return (!empty($_POST));
                break;
            case 'get':
                return (!empty($_GET));
                break;
            default:
                return false;
                break;
        }
    }

    /**
     * @param $item
     * @return string|array
     */
    public static function post($item)
    {
        if (is_array($item)) {
            foreach ($item as $k => $v) {
                $item[$k] = static::post($v);
            }
            return $item;
        }

        if (isset($_POST[$item])) {
            return trim($_POST[$item]);
        }

        return '';
    }


    /**
     * @param $item
     * @return string|array
     */
    public static function get($item)
    {
        if (is_array($item)) {
            foreach ($item as $k => $v) {
                $item[$k] = static::get($v);
            }
            return $item;
        }

        if (isset($_GET[$item])) {
            return $_GET[$item];
        }
        return '';
    }


    /**
     * @param $item
     * @return array|string
     */
    public static function getInput($item)
    {
        if (self::exists()) {
            return self::post($item);
        }
        if (self::exists('get')) {
            return self::get($item);
        }
    }

}