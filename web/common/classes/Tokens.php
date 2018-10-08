<?php
/***
 * Class Tokens
 */
class Tokens {

    /***
     * Hash
     */
    const HASH  = PASSWORD_DEFAULT;


    /***
     * @return string
     */
    public static function unqId()
    {
        return uniqid();
    }


    /***
     * @return mixed
     */
    public static function getInputToken()
    {
        return Session::put('inputToken', self::unqId());
    }


    /***
     * @return bool|string
     */
    public static function getInputTokenHash()
    {
        return password_hash(Session::get('inputToken'), self::HASH);
    }


    /***
     * @param int $len
     * @return mixed
     */
    public static function getRouteToken($len = 10)
    {
        return Session::put('routeToken', self::randomString($len));
    }

    /***
     * @return bool|string
     */
    public static function getRouteTokenHash()
    {
        return password_hash(Session::get('routeToken'), self::HASH);
    }


    /***
     * @param int $length
     * @return string
     */
    public static function randomString($length = 10)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }


    /**
     * @param $token
     * @return bool
     */
    public static function checkInput($token)
    {
        $tokenHash = self::getInputTokenHash();
        if (Session::exists('inputToken') && password_verify($token, $tokenHash)) {
            Session::delete('inputToken');
            return true;
        }
        return false;
    }


    /***
     * @param $routeToken
     * @return bool
     */
    public static function checkRoute($routeToken)
    {
        if (Session::exists('routeToken')) {
            return password_verify($routeToken, self::getRouteTokenHash());
        }
    }
}