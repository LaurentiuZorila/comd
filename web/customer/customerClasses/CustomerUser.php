<?php

/**
 * Class CustomerUser
 */
class CustomerUser
{
    /**
     * @var CustomerDB|null
     */
    private $_customerDB;

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
    private $_sessionCustomerId;

    /**
     * @var
     */
    private $_sessionSupervisorId;

    /**
     * @var
     */
    private $_sessionOfficeId;

    /**
     * @var mixed|null
     */
    private $_sessionDepartmentId;


    /**
     * @var mixed|null
     */
    private $_fname;


    /**
     * @var mixed|null
     */
    private $_lname;

    private $_sessionCityId;

    /**
     * @var bool
     */
    private $_isLoggedIn;

    /**
     * @var bool
     */
    private $_error = false;


    private $_success = false;

    /**
     * Customer table
     */
    const CUSTOMER_TBL  = 'cmd_users';


    /**
     * CustomerUser constructor.
     * @param null $user
     */
    public function __construct($user = null)
    {
        $this->_customerDB          = CustomerDB::getInstance();
        $this->_sessionName         = Config::get('session/session_name');
        $this->_sessionCustomerId   = Config::get('session/session_id');
        $this->_sessionDepartmentId = Config::get('session/session_department_id');
        $this->_sessionOfficeId     = Config::get('session/session_office_id');
        $this->_sessionSupervisorId = Config::get('session/session_supervisor_id');
        $this->_fname               = Config::get('session/session_fname');
        $this->_lname               = Config::get('session/session_lname');
        $this->_sessionCityId       = Config::get('session/session_cityId');


        if (!$user) {
            if (Session::exists($this->_sessionCustomerId)) {
                $user = Session::get($this->_sessionCustomerId);

                if ($this->find($user)) {
                    $this->_isLoggedIn = true;
                } else {
                    $this->logout();
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
        $field  = (is_numeric($user)) ? 'id' : 'username';
        $data   = $this->_customerDB->get(self::CUSTOMER_TBL, AC::where([$field, $user]));

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
        if (!$this->_customerDB->update($table, $fields, $conditions)) {
            throw new Exception('There was a problem, please try again!');
        }
        $this->_success = true;
    }


    /**
     * @param $table
     * @param $fields
     * @throws Exception
     */
    public function insert($table, $fields)
    {
        if (!$this->_customerDB->insert($table, $fields)) {
            throw new Exception('There was a problem, please try again!');
        } else {
            $this->_success = true;
        }
    }


    /**
     * @param $table
     * @param array $fields
     * @throws Exception
     */
    public function create($table, $fields = array())
    {
        if (!$this->_customerDB->insert($table, $fields)) {
            throw new Exception('There was a problem creating an account!!');
        }
        $this->_success = true;
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
            Session::put($this->_sessionCustomerId, $this->data()->id);
            Session::put($this->_sessionDepartmentId, $this->data()->departments_id);
            Session::put($this->_sessionOfficeId, $this->data()->offices_id);
            Session::put($this->_sessionSupervisorId, $this->data()->supervisors_id);
            Session::put($this->_fname, $this->data()->fname);
            Session::put($this->_lname, $this->data()->lname);
            Session::put($this->_sessionCityId, $this->data()->city_id);
            return true;
        }

        return false;
    }

    /**
     * Delete all sessions
     */
    public function logout()
    {
        Session::delete($this->_sessionName);
        Session::delete($this->_sessionCustomerId);
        Session::delete($this->_sessionDepartmentId);
        Session::delete($this->_sessionOfficeId);
        Session::delete($this->_sessionSupervisorId);
        Session::delete($this->_lname);
        Session::delete($this->_fname);
        Redirect::to('/index.php');
        exit;
    }


    /**
     * @param $string
     * @param $hash
     * @return bool
     */
    public static function passCheck($string, $hash)
    {
        return password_verify($string, $hash);
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
     * @return mixed
     */
    public function customerId()
    {
        return Session::get($this->_sessionCustomerId);
    }


    /**
     * @return mixed
     */
    public function officesId()
    {
        return Session::get($this->_sessionOfficeId);
    }


    /**
     * @return mixed
     */
    public function supervisorId()
    {
        return Session::get($this->_sessionSupervisorId);
    }


    /**
     * @return mixed
     */
    public function departmentId()
    {
        return Session::get($this->_sessionDepartmentId);
    }

    /**
     * @return mixed
     */
    public function cityId()
    {
        return Session::get($this->_sessionCityId);
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

    /**
     * @return bool
     */
    public function success()
    {
        return $this->_success;
    }
}