<?php

/**
 * Class User
 */
class User
{
    /**
     * @var DB|null
     */
    private $_db;

    /**
     * @var
     */
    private $_data;

    /**
     * @var mixed|null
     */
    private $_sessionName;

    /**
     * @var mixed|null
     */
    private $_sessionUserId;

    /**
     * @var bool
     */
    private $_isLoggedIn;

    /**
     * User constructor.
     * @param null $user
     */
    public function __construct($user = null)
    {
        $this->_db = DB::getInstance();
        $this->_sessionName = Config::get('session/session_name');
        $this->_sessionUserId = Config::get('session/session_user');

        if (!$user) {
            if (Session::exists($this->_sessionName)) {
                $user = Session::get($this->_sessionName);

                if ($this->find($user)) {
                    $this->_isLoggedIn = true;
                } else {
                    return $this->logout();
                    Redirect::to('login.php');
                }
            }
        } else {
            $this->find($user);
        }
    }


    /**
     * @param $table
     * @param null $customer_id
     * @param array $fields
     * @throws Exception
     */
    public function update($table, $fields = array(), $conditions = array())
    {
        if (!$this->_db->update($table, $fields, $conditions)) {
            throw new Exception('There was a problem, please try again!');
        }
    }


    /**
     * @param array $fields
     * @throws Exception
     */
    public function create($table, $fields = array())
    {
        if (!$this->_db->insert($table, $fields)) {
            throw new Exception('There was a problem creating an account!!');
        }
    }

    /**
     * @param $user
     * @return bool
     */
    public function find($user)
    {
        $field = (is_numeric($user)) ? 'id' : 'username';
        $data = $this->_db->get('cmd_users', array($field, '=', $user));

        if ($data->count()) {
            $this->_data = $data->first();
            return true;
        }

        return false;
    }

    /**
     * @param null $username
     * @param null $password
     * @return bool
     */
    public function login($username = null, $password = null)
    {
        if (!$this->find($username)) {
            return false;
        }

        if (password_verify($password, $this->data()->password)) {
            Session::put($this->_sessionName, $this->data()->id);
            Session::put($this->_sessionUserId, $this->data()->id);
            return true;
        }

        return false;
    }

    /**
     *
     */
    public function logout()
    {
        Session::delete($this->_sessionName);
        Session::delete($this->_sessionUserId);
    }

    /**
     * @return mixed
     */
    public function data()
    {
        return $this->_data;
    }

    /**
     * @return mixed|null
     */
    public function userId()
    {
        return Session::get($this->_sessionUserId);
    }

    /**
     * @return bool
     */
    public function isLoggedIn()
    {
        return $this->_isLoggedIn;
    }
}