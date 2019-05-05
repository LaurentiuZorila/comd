<?php

/**
 * Class FrontendUser
 */
class FrontendUser
{
    /**
     * @var FrontendDB|null
     */
    private $_db;

    /**
     * @var bool
     */
    private $_error = false;

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
    private $_sessionLname;

    /**
     * @var
     */
    private $_sessionFname;

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
     * @var mixed|string|null
     */
    private $_sessionCityId;

    /**
     * @var
     */
    private $_success = false;

    /**
     * front table
     */
    const FRONT_TBL = 'cmd_employees';


    /**
     * FrontendUser constructor.
     * @param null $user
     */
    public function __construct($user = null)
    {
        $this->_db                  = FrontendDB::getInstance();
        $this->_sessionName         = Config::get('frontSession/session_name');
        $this->_sessionFname        = Config::get('frontSession/session_fname');
        $this->_sessionLname        = Config::get('frontSession/session_lname');
        $this->_sessionUserName     = Config::get('frontSession/session_username');
        $this->_sessionUserId       = Config::get('frontSession/session_id');
        $this->_sessionDepartmentId = Config::get('frontSession/session_department');
        $this->_sessionOfficeId     = Config::get('frontSession/session_office');
        $this->_sessionSupervisorId = Config::get('frontSession/session_supervisor');
        $this->_sessionCityId       = Config::get('frontSession/session_cityId');


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
            Session::put($this->_sessionFname, $this->data()->fname);
            Session::put($this->_sessionLname, $this->data()->lname);
            Session::put($this->_sessionUserName, $this->data()->username);
            Session::put($this->_sessionUserId, $this->data()->id);
            Session::put($this->_sessionDepartmentId, $this->data()->departments_id);
            Session::put($this->_sessionOfficeId, $this->data()->offices_id);
            Session::put($this->_sessionSupervisorId, $this->data()->supervisors_id);
            Session::put($this->_sessionCityId, $this->data()->city_id);
            return true;
        }
        return false;
    }


    /**
     * @param bool $redirect
     */
    public function logout($redirect = true)
    {
        if ($redirect) {
            Session::delete($this->_sessionName);
            Session::delete($this->_sessionFname);
            Session::delete($this->_sessionLname);
            Session::delete($this->_sessionUserId);
            Session::delete($this->_sessionSupervisorId);
            Session::delete($this->_sessionOfficeId);
            Session::delete($this->_sessionDepartmentId);
            Session::delete($this->_sessionUserName);
            Session::delete($this->_sessionTeamLeadId);
            Redirect::to('../index.php');
        } else {
            Session::delete($this->_sessionName);
            Session::delete($this->_sessionFname);
            Session::delete($this->_sessionLname);
            Session::delete($this->_sessionUserId);
            Session::delete($this->_sessionSupervisorId);
            Session::delete($this->_sessionOfficeId);
            Session::delete($this->_sessionDepartmentId);
            Session::delete($this->_sessionUserName);
            Session::delete($this->_sessionTeamLeadId);
        }


    }


    /**
     * @param $table
     * @param array $fields
     * @param array $conditions
     * @throws Exception
     */
    public function update($table, $fields = array(), $conditions = array())
    {
        if (!$this->_db->update($table, $fields, $conditions)) {
            throw new Exception('There was a problem, please try again!');
        }
        $this->_success = true;
    }


    /**
     * @param $table
     * @param $where
     * @throws Exception
     */
    public function delete($table, $where)
    {
        if (!$this->_db->delete($table, $where)) {
            throw new Exception('There was a problem, please try again!');
        }
        $this->_success = true;
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
    public function fName()
    {
        return Session::get($this->_sessionFname);
    }

    /**
     * @return mixed
     */
    public function lName()
    {
        return Session::get($this->_sessionLname);
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
    public function cityId()
    {
        return Session::get($this->_sessionCityId);
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

    /**
     * @return bool
     */
    public function dbSuccess()
    {
        return $this->_success;
    }
}