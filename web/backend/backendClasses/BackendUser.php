<?php

/**
 * Class BackendUser
 */
class BackendUser
{
    /**
     * @var BackendDB|null
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
     * @var mixed|null
     */
    private $_sessionDepartmentId;

    /**
     * @var bool
     */
    private $_isLoggedIn;

    /**
     * @var bool
     */
    private $_error = false;

    /**
     * Backend table
     */
    const BACKEND_TBL = 'cmd_supervisors';


    /**
     * BackendUser constructor.
     * @param null $user
     */
    public function __construct($user = null)
    {
        $this->_db                  = BackendDB::getInstance();
        $this->_sessionName         = Config::get('session/session_name');
        $this->_sessionUserId       = Config::get('session/session_id');
        $this->_sessionDepartmentId = Config::get('session/session_department');

        if (!$user) {
            if (Session::exists($this->_sessionUserId)) {
                $user = Session::get($this->_sessionUserId);

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
     * @param $user
     * @return bool
     */
    public function find($user)
    {
        $field = (is_numeric($user)) ? 'id' : 'username';
        $data = $this->_db->get(self::BACKEND_TBL, [$field, '=', $user]);

        if ($data->count()) {
            $this->_data = $data->first();
            return true;
        }
        return false;
    }

    /**
     * @param $table
     * @param null $customer_id
     * @param array $fields
     * @throws Exception
     */
    public function update($table, $fields = array(), $conditions = array())
    {
        $this->_error = false;
        if (!$this->_db->update($table, $fields, $conditions)) {
            $this->_error = true;
            throw new Exception('There was a problem, please try again!');
        }
    }


    /**
     * @param array $fields
     * @throws Exception
     */
    public function create($table, $fields = array())
    {
        $this->_error = false;
        if (!$this->_db->insert($table, $fields)) {
            $this->_error = true;
            throw new Exception('There was a problem creating an account!!');
        }
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
            Session::put($this->_sessionName, $this->data()->name);
            Session::put($this->_sessionUserId, $this->data()->id);
            Session::put($this->_sessionDepartmentId, $this->data()->departments_id);
            return true;
        }

        return false;
    }


    /**
     * Process logout
     */
    public function logout()
    {
        Session::delete($this->_sessionName);
        Session::delete($this->_sessionUserId);
        Session::delete($this->_sessionDepartmentId);
        Redirect::to('../index.php');
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
     * @return mixed
     */
    public function departmentId()
    {
        return Session::get($this->_sessionDepartmentId);
    }


    /**
     * @return bool
     */
    public function isLoggedIn()
    {
        return $this->_isLoggedIn;
    }


    /**
     * @return bool
     */
    public function errors()
    {
        return $this->_error;
    }
}