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
     * @var
     */
    private $_lang;


    /**
     * @var mixed|null
     */
    private $_fname;


    /**
     * @var mixed|null
     */
    private $_lname;


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

    private $_sessionCityId;


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
        $this->_fname               = Config::get('session/session_fname');
        $this->_lname               = Config::get('session/session_lname');
        $this->_lang                = Session::get('lang');
        $this->_sessionCityId       = Config::get('session/session_cityId');

        if (!$user) {
            if (Session::exists($this->_sessionUserId)) {
                $user = Session::get($this->_sessionUserId);

                if ($this->find($user)) {
                    $this->_isLoggedIn = true;
                } else {
                    return $this->logout();
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
        $data = $this->_db->get(self::BACKEND_TBL, AC::where([$field, $user]));

        if ($data->count()) {
            $this->_data = $data->first();
            return true;
        }
        return false;
    }


    /**
     * @param $table
     * @param array $fields
     * @param array $conditions
     * @return bool
     * @throws Exception
     */
    public function update($table, $fields = array(), $conditions = array())
    {
        if (!$this->_db->update($table, $fields, $conditions)) {
            throw new Exception('There was a problem, please try again!');
        }
        return true;
    }


    /**
     * @param $table
     * @param array $fields
     * @return bool
     * @throws Exception
     */
    public function create($table, $fields = array())
    {
        $this->_error = false;
        if (!$this->_db->insert($table, $fields)) {
            $this->_error = true;
            throw new Exception('There was a problem creating an account!!');
        }
        return true;
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
            Session::put($this->_fname, $this->data()->fname);
            Session::put($this->_lname, $this->data()->lname);
            Session::put($this->_sessionCityId, $this->data()->city_id);
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
        Session::delete($this->_lname);
        Session::delete($this->_fname);
        Redirect::to('../index.php');
        exit;
    }


    /**
     * @return mixed
     */
    public function data()
    {
        return $this->_data;
    }


    /**
     * @return mixed
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
     * @return mixed|null
     */
    public function fName()
    {
        return Session::get($this->_fname);
    }

    /**
     * @return mixed|null
     */
    public function lName()
    {
        return Session::get($this->_lname);
    }


    /**
     * @return mixed
     */
    public function name()
    {
        return Session::get($this->_sessionName);
    }


    /**
     * @return mixed
     */
    public function uName()
    {
        return $this->data()->username;
    }


    /**
     * @param bool $id
     * @return mixed if id = true return language id if false return language name
     */
    public function language($id = true)
    {
        if ($id) {
            return Params::LANG[$this->data()->lang];
        }
        return $this->data()->lang;
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