<?php

/**
 * Class Hash
 */
class Hash
{

    /**
     * @param $string
     * @return bool|string
     */
    public static function hashMake($string)
    {
        return password_hash($string, PASSWORD_DEFAULT);
    }


    /**
     * @param $password
     * @param $passwrodHash
     * @return bool
     */
    public static function hashVerify($password, $passwrodHash)
    {
        if (password_verify($password, $passwrodHash)) {
            return true;
        }
    }


    /**
     * @param $string
     * @param string $salt
     * @return string
     */
    public static function make($string, $salt = '')
    {
        return hash('sha256', $string . $salt);
    }


    /**
     * @param $lenght
     * @return string
     * @throws Exception
     */
    public static function salt($lenght)
    {
        return random_bytes($lenght);
    }


    /**
     * @return string
     */
    public static function unique()
    {
        return self::make(uniqid());
    }
}
