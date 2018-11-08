<?php
/**
 * Created by PhpStorm.
 * User: onetwist
 * Date: 10/24/18
 * Time: 4:10 PM
 */

class Errors
{

    private static $_errors = [];

    private static $_successErrors = [];

    private static $_dangerErrors = [];

    private static $_infoErrors = [];

    private static $_warningErrors = [];

    private static $_type;

    private static $_messageType = [
        self::SUCCESS => 'well_done',
        self::WARNING => 'Make_attention',
        self::DANGER  => 'something_wrong',
        self::INFO    => 'info'
        ];

    const SUCCESS = 'success';
    const INFO    = 'info';
    const WARNING = 'warning';
    const DANGER  = 'danger';


    /**
     * @param $type
     * @param $errors
     */
    public static function setErrorType($type, $errors)
    {
        if ($type === self::SUCCESS){
            self::$_type = self::SUCCESS;
            self::$_successErrors[] = $errors;
        } elseif ($type === self::DANGER) {
            self::$_type = self::DANGER;
            self::$_dangerErrors[] = $errors;
        } elseif ($type === self::INFO) {
            self::$_type = self::INFO;
            self::$_infoErrors[] = $errors;
        } elseif ($type === self::WARNING) {
            self::$_type = self::WARNING;
            self::$_warningErrors[] = $errors;
        }
    }

    /**
     * @return mixed
     */
    public static function errorMessage()
    {
        return self::$_messageType[self::$_type];
    }

    /**
     * @return mixed
     */
    public static function getErrorType()
    {
        return self::$_type;
    }

    /**
     * @return array
     */
    public static function getErrors()
    {
        switch (self::$_type) {
            case self::WARNING:
                return array_unique(self::getWarningErrors());
                break;
            case self::DANGER:
                return array_unique(self::getDangerErrors());
                break;
            case self::INFO:
                return array_unique(self::getInfoErrors());
                break;
            case self::SUCCESS:
                return array_unique(self::getSuccessErrors());
                break;
        }
    }

    /**
     * @return array
     */
    private static function getSuccessErrors()
    {
        return array_unique(self::$_successErrors);
    }

    /**
     * @return array
     */
    private static function getDangerErrors()
    {
        return array_unique(self::$_dangerErrors);
    }


    /**
     * @return array
     */
    private static function getWarningErrors()
    {
        return array_unique(self::$_warningErrors);
    }

    /**
     * @return array
     */
    private static function getInfoErrors()
    {
        return array_unique(self::$_infoErrors);
    }


    /**
     * @return bool
     */
    public static function countAllErrors($type = 'all')
    {
        if (!empty($type)) {
            switch ($type) {
                case 'info':
                    return count(self::$_infoErrors) > 0 ? true : false;
                    break;
                case 'danger':
                    return count(self::$_dangerErrors) > 0 ? true : false;
                    break;
                case 'warning':
                    return count(self::$_warningErrors) > 0 ? true : false;
                    break;
                case 'success':
                    return count(self::$_successErrors) > 0 ? true : false;
                    break;
                case 'all':
                    self::$_errors = array_merge(self::$_warningErrors, self::$_dangerErrors, self::$_infoErrors, self::$_successErrors);
                    return count(self::$_errors) > 0 ? true : false;
                    break;
            }
        }
    }
}