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
        $this->_tokenHash       = Session::get('tokenHash');
        $this->_token           = Session::get('token');
        $this->_setupTokenHash  = Session::get('setupToken');

        if (!isset($_POST)) {
            $this->_randomId        = uniqid();
            $hash                   = password_hash($this->_randomId, PASSWORD_DEFAULT);
            $this->_token           = Session::put('token', $this->_randomId);
            $this->_tokenHash       = Session::put('tokenHash', $hash);
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