<?php
/**
 * Class Tokens
 */

class Tokens
{

    /**
     * @return mixed|null
     */
    private static function sessionName()
    {
        return CommonConfig::get('submit_session/token_hash');
    }

    /**
     * @return mixed|null
     */
    private static function routeSession()
    {
        return CommonConfig::get('route_session/r_token_hash');
    }

    /**
     * @return array
     */
    private static function algo()
    {
        return [0 => 'md5', 1 => 'sha1', 2 => 'sha224', 3 => 'sha256'];
    }

    /**
     * @return mixed
     */
    private static function setAlgo()
    {
        $rand = array_rand(self::algo());
        return self::algo()[$rand];
    }

    /**
     * @return mixed
     * @throws Exception
     */
    private static function setSubmitToken()
    {
        if (Session::exists(self::sessionName())) {
            return Session::get(self::sessionName());
        }
        return Session::put(self::sessionName(), hash(self::setAlgo(), bin2hex(random_bytes(32))));
    }

    /**
     * @return mixed
     * @throws Exception
     */
    private static function setRoute()
    {
        if (Session::exists(self::routeSession())) {
            return Session::get(self::routeSession());
        }
        return Session::put(self::routeSession(), hash('md5', bin2hex(random_bytes(16))));
    }


    /**
     * @return mixed
     * @throws Exception
     */
    public static function getRoute()
    {
        return self::setRoute();
    }

    /**
     * @return mixed
     * @throws Exception
     */
    public static function getSubmitToken()
    {
       return self::setSubmitToken();
    }

    /**
     * @param $token
     * @return bool
     */
    public static function tokenVerify($token)
    {
        $submitToken = self::sessionName();
        if (Session::exists($submitToken) && hash_equals($token, Session::get($submitToken))) {
            Session::delete($submitToken);
            return true;
        }
        Redirect::to('./../error/notFoundToken.php');
    }


    /**
     * @param $token
     * @return bool
     */
    public static function routeTokenVerify($token)
    {
        $routeToken = self::routeSession();
        if (Session::exists($routeToken) && hash_equals($token, Session::get($routeToken))) {
            Session::delete($routeToken);
            return true;
        }
        Redirect::to('./../error/notFoundToken.php');
    }
}