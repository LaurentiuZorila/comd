<?php
/***
 * Class Token
 */
class Token {

    private $_token;

    private $_tokenHash;

    private $_randomId;

    private $_setupTokenHash;


    /**
     * @return mixed
     */
    public function __construct()
    {
        $this->_tokenHash   = Session::get(Config::get('token/token_hash'));
        $this->_token       = Session::get(Config::get('token/token'));

        if (!Input::exists()) {
            $this->_randomId        = uniqid();
            $hash                   = password_hash($this->_randomId, PASSWORD_DEFAULT);
            $this->_token           = Session::put(Config::get('token/token'), $this->_randomId);
            $this->_tokenHash       = Session::put(Config::get('token/token_hash'), $hash);
            $this->_setupTokenHash  = Session::put('setupToken', $hash);
        }
    }


    /**
     * @return mixed
     * @ Genereate token
     */
    public function getToken()
    {
        return $this->_token;
    }

    /**
     * @return mixed
     * @ Generate token hash
     */
    public function getTokenHash()
    {
        return $this->_tokenHash;
    }


    /**
     * @param $token
     * @return bool
     */
    public function checkToken($token)
    {
        return password_verify($token, $this->_tokenHash);
    }


    /**
     * @param $tokenHash
     * @return bool
     */
    public function checkHashToken($tokenHash)
    {
        return password_verify($this->_token, $tokenHash);
    }
}