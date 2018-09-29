<?php

/**
 * Class FrontendUser
 */
class FrontendUser
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
     * front table
     */
    const FRONT_TBL = 'cmd_employees';



    /**
     * User constructor.
     * @param null $user
     */
    public function __construct($user = null)
    {
        $this->_db                  = FrontendDB::getInstance();
        $this->_sessionName         = Config::get('frontSession/session_name');
        $this->_sessionUserName     = Config::get('frontSession/session_username');
        $this->_sessionUserId       = Config::get('frontSession/session_id');
        $this->_sessionDepartmentId = Config::get('frontSession/session_department');
        $this->_sessionOfficeId     = Config::get('frontSession/session_office');
        $this->_sessionSupervisorId = Config::get('frontSession/session_supervisor');


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
        $data = $this->_db->get(self::FRONT_TBL, [$field, '=', $user]);

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
        Session::delete($this->_sessionSupervisorId);
        Session::delete($this->_sessionOfficeId);
        Session::delete($this->_sessionDepartmentId);
        Session::delete($this->_sessionUserName);
        Session::delete($this->_sessionTeamLeadId);
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