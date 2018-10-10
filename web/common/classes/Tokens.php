<?php
/***
 * Class Token
 */
class Tokens {
    /***
     * @var mixed
     */
    private static $_token;


    /**
     * @var array
     */
    private $_tokens = ['token', 'tokenHash', 'setupToken', 'routeToken', 'routeTokenHash'];


    /***
     * Token constructor.
     */
    public function __construct()
    {
        if (!Input::exists()) {
            in_array('token', $this->_tokens) ? Session::put('token', $this->getUniqueId()) : '';
        }
    }


    /**
     * @return string
     */
    public function getUniqueId()
    {
        return uniqid();
    }


    /**
     * @return int
     */
    public function now()
    {
        return time();
    }


    /**
     * @return mixed
     * @ Generate token
     */
    public static function getToken()
    {
        return Session::get('token');
    }

    /**
     * @return string
     */
    public static function inputHidden()
    {
        echo '<input type="hidden" name="token" value="'. self::getToken() . '" />';
    }


    /**
     * @return mixed
     * @ Generate token hash
     */
    public static function getTokenHash()
    {
        return password_hash(self::getToken(), PASSWORD_DEFAULT);
    }


    /**
     * @param $token
     * @return bool
     */
    public static function checkToken($token)
    {
        if (password_verify($token, self::getTokenHash())) {
            Session::delete('token');
            return true;
        }
        return false;
    }
}
