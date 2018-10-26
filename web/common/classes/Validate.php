<?php

/**
 * Class Validate
 */
class Validate
{
    /**
     * @var bool
     */
    private $_passed = false;

    /**
     * @var array
     */
    private $_errors = [];

    /**
     * @var BackendDB|null
     */
    private $_db = null;



    /**
     * Validate constructor.
     */
    public function __construct()
    {
        $this->_db = CommonDB::getInstance();
    }


    /**
     * @param $source
     * @param array $items
     * @return $this
     */
    public function check($source, $items = [])
    {
        foreach ($items as $item => $rules) {
            foreach ($rules as $rule => $rule_value) {

                $value      = trim($source[$item]); // Input POST
                $item       = escape($item);

                if (strpos($item, '_') > 0) {
                    $needlePosition = strpos($item, '_');
                    $firstItem      = substr($item, 0, $needlePosition);
                    $secondItem     = substr($item, $needlePosition + 1);
                    $errorItem      = ucfirst($firstItem) . ' ' . ucfirst($secondItem);
                    $errorItem      = escape($errorItem);
                } else {
                    $errorItem = ucfirst($item);
                    $errorItem = escape($errorItem);
                }

                if ($rule === 'required' && empty($value)) {
                    Errors::setErrorType('danger', 'All fields are required');
                } else if (!empty($value)) {
                    switch ($rule) {
                        case 'min':
                            if (trim(strlen($value)) < $rule_value) {
                                Errors::setErrorType('danger', sprintf("%s must be a minimum of %d characters!", $errorItem, $rule_value));
                            }
                            break;
                        case 'max':
                            if (trim(strlen($value)) > $rule_value) {
                                Errors::setErrorType('danger', sprintf("%s must be a maximum of %d characters!", $errorItem, $rule_value));
                            }
                            break;
                        case 'matches':
                            if ($value != $source[$rule_value]) {
                                Errors::setErrorType('danger', "Passwords doesn't match!");
                            }
                            break;
                        case 'unique':
                            $check = $this->_db->get($rule_value, $where = [$item, '=', $value]);
                            if ($check->count()) {
                                Errors::setErrorType('danger', sprintf("%s already exists!", $errorItem));
                            }
                            break;
                        case 'email':
                            if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
                                Errors::setErrorType('danger', 'You need to insert a valid email!');
                            }
                            break;
                        case 'letters':
                            if (is_numeric($value)) {
                                Errors::setErrorType('danger', sprintf("%s must contain only letters!", $errorItem));
                            }
                            break;
                        case 'characters':
                            if (is_numeric($value) && ctype_alpha($value)) {
                                Errors::setErrorType('danger', sprintf("%s must contain only characters!", $errorItem));
                            }
                            break;
                        case 'extension':
                            $path = $_FILES['fileToUpload']['name'];
                            $extension  = pathinfo($path, PATHINFO_EXTENSION);
                            if (in_array($extension, $rule_value)) {
                                Errors::setErrorType('danger', 'Your file must have CSV extension.');
                            }
                            break;
                    }
                }
            }
        }

        if (empty(Errors::countAllErrors())) {
            $this->_passed = true;
        }

        return $this;
    }


    /**
     * @param $error
     */
    private function addError($error)
    {
        $this->_errors[] = $error;
    }


    /***
     * @return array
     */
    public function errors()
    {
        return array_unique($this->_errors);
    }

    /***
     * @return bool
     */
    public function countErrors()
    {
        if (count($this->errors()) > 0) {
            return true;
        } elseif (count($this->errors()) === 0) {
            return false;
        }

    }

    /**
     * @return bool
     */
    public function passed()
    {
        return $this->_passed;
    }
}