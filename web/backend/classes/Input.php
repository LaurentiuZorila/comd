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
                return (!empty($_POST)) ? true : false;
                break;
            case 'get':
                return (!empty($_GET)) ? true : false;
                break;
            default:
                return false;
                break;
        }
    }

    /**
     * @param $item
     * @return string
     */
    public static function post($item)
    {
        if (isset($_POST[$item])) {
            return trim($_POST[$item]);
        }
        return '';
    }

    /**
     * @param $item
     * @return string
     */
    public static function get($item)
    {
        if (isset($_GET[$item])) {
            return $_GET[$item];
        }
        return '';
    }

}