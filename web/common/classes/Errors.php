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
        self::SUCCESS => 'Well done.',
        self::WARNING => 'Make attention.',
        self::DANGER  => 'Something is going wrong.',
        self::INFO    => 'Info!'
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
                return self::getWarningErrors();
                break;
            case self::DANGER:
                return array_unique(self::getDangerErrors());
                break;
            case self::INFO:
                return self::getInfoErrors();
                break;
            case self::SUCCESS:
                return self::getSuccessErrors();
                break;
        }
    }

    /**
     * @return array
     */
    private static function getSuccessErrors()
    {
        return self::$_successErrors;
    }

    /**
     * @return array
     */
    private static function getDangerErrors()
    {
        return self::$_dangerErrors;
    }


    /**
     * @return array
     */
    private static function getWarningErrors()
    {
        return self::$_warningErrors;
    }

    /**
     * @return array
     */
    private static function getInfoErrors()
    {
        return self::$_infoErrors;
    }


    /**
     * @return bool
     */
    public static function countAllErrors()
    {
        self::$_errors = array_merge(self::$_warningErrors, self::$_dangerErrors, self::$_infoErrors, self::$_successErrors);
        return count(self::$_errors) > 0 ? true : false;
    }
}