<?php
/***
 * Class Token
 */
class Token {

    private static $_tokenName       = 'submit_token';

    private static $_filterTokenName = 'filer_submit_token';

    public static $inputName         = 'token';

    public static $filterToken       = 'filter';


    /**
     * @return mixed
     */
    public static function generate() {
        if (Session::exists(self::$_tokenName)) {
            Session::delete(self::$_tokenName);
        }
        return Session::put(self::$_tokenName, md5(bin2hex(random_bytes(16))));
    }


    /**
     * @return mixed
     */
    public static function filterValue()
    {
        return Session::put(self::$_filterTokenName, md5(bin2hex(random_bytes(8))));
    }


    /**
     * @param $token
     * @return bool
     */
    public static function checkSubmit($token)
    {
        $hashToken = Session::get(Config::get(self::$_filterTokenName));
        if (Session::exists(self::$_filterTokenName) && hash_equals($hashToken, $token)) {
            Session::delete(self::$_filterTokenName);
            return true;
        }
        Redirect::to('../error/notFoundToken.php');
    }


    /**
     * @param $token
     * @return bool
     * @throws Exception
     */
    public static function check($token)
    {
        if (Session::exists(self::$_tokenName) && hash_equals(Session::get(self::$_tokenName), $token)) {
            Session::delete(self::$_tokenName);
            return true;
        }
//        Redirect::to('../error/notFoundToken.php');
        return false;
    }
}