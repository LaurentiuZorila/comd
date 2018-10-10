<?php

/**
 * Class CustomerUser
 */
class CustomerUser
{
    /**
     * @var BackendDB|null
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
        $data   = $this->_customerDB->get(self::CUSTOMER_TBL, [$field, '=', $user]);

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
        if (!$this->_customerDB->update($table, $fields, $conditions)) {
            $this->_error = true;
            throw new Exception('There was a problem, please try again!');
        }
    }


    /**
     * @param $table
     * @param $fields
     * @throws Exception
     */
    public function insert($table, $fields)
    {
        $this->_error = false;
        if (!$this->_customerDB->insert($table, $fields)) {
            $this->_error = true;
            throw new Exception('There was a problem, please try again!');
        } else {
            $this->_success = true;
        }
    }


    /**
     * @param array $fields
     * @throws Exception
     */
    public function create($table, $fields = array())
    {
        $this->_error = false;
        if (!$this->_customerDB->insert($table, $fields)) {
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
            Session::put($this->_sessionCustomerId, $this->data()->id);
            Session::put($this->_sessionDepartmentId, $this->data()->departments_id);
            Session::put($this->_sessionOfficeId, $this->data()->offices_id);
            Session::put($this->_sessionSupervisorId, $this->data()->supervisors_id);
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

    public function success()
    {
        return $this->_success;
    }
}