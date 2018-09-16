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
     * @var
     */
    private $_sessionUserName;

    /**
     * @var mixed|null
     */
    private $_sessionUserId;

    /**
     * @var bool
     */
    private $_isLoggedIn;

    /**
     * @var mixed|null
     */
    private $_sessionDepartmentId;

    /**
     * @var mixed|null
     */
    private $_sessionOfficeId;

    /**
     * @var mixed|null
     */
    private $_sessionSupervisorId;

    /**
     * @var mixed|null
     */
    private $_sessionTeamLeadId;

    /**
     * User constructor.
     * @param null $user
     */
    public function __construct($user = null)
    {
        $this->_db = DB::getInstance();
        $this->_sessionName         = Config::get('session/session_name');
        $this->_sessionUserName     = Config::get('session/session_username');
        $this->_sessionUserId       = Config::get('session/session_id');
        $this->_sessionDepartmentId = Config::get('session/session_department');
        $this->_sessionOfficeId     = Config::get('session/session_office');
        $this->_sessionSupervisorId = Config::get('session/session_supervisor');
        $this->_sessionTeamLeadId   = Config::get('session/session_user');

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
        $data = $this->_db->get('cmd_employees', array($field, '=', $user));

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
            Session::put($this->_sessionName, $this->data()->name);
            Session::put($this->_sessionUserName, $this->data()->username);
            Session::put($this->_sessionUserId, $this->data()->id);
            Session::put($this->_sessionDepartmentId, $this->data()->departments_id);
            Session::put($this->_sessionOfficeId, $this->data()->offices_id);
            Session::put($this->_sessionSupervisorId, $this->data()->supervisors_id);
            Session::put($this->_sessionTeamLeadId, $this->data()->user_id);
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
     * @param $string
     * @return string
     */
    public function makeAvatar()
    {
        $name = $this->_sessionName;
        $name = explode(' ', $name);
        $name = array_reverse($name);
        $value = '';
        foreach ($name as $item) {
            $value .= substr($item, 0, 1);
        }
        return $value;
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
    public function userName()
    {
        return Session::get($this->_sessionUserName);
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
    public function departmentId()
    {
        return Session::get($this->_sessionDepartmentId);
    }


    /**
     * @return mixed
     */
    public function officeId()
    {
        return Session::get($this->_sessionOfficeId);
    }


    /**
     * @return mixed
     */
    public function teamLeadId()
    {
        return Session::get($this->_sessionTeamLeadId);
    }

    /**
     * @return mixed
     */
    public function supervisorId()
    {
        return Session::get($this->_sessionSupervisorId);
    }

    /**
     * @return bool
     */
    public function isLoggedIn()
    {
        return $this->_isLoggedIn;
    }
}