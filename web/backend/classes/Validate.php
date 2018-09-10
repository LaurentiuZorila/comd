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
    private $_errors = array();

    /**
     * @var DB|null
     */
    private $_db = null;


    /**
     * Validate constructor.
     */
    public function __construct()
    {
        $this->_db = DB::getInstance();
    }


    /**
     * @param $source
     * @param array $items
     * @return $this
     */
    public function check($source, $items = array())
    {
        foreach ($items as $item => $rules) {
            foreach ($rules as $rule => $rule_value) {
                $value = trim($source[$item]); // Input POST
                $item = escape($item);

                if ($rule === 'required' && empty($value)) {
                    $this->addError("All fields are required");
                } else if (!empty($value)) {
                    switch ($rule) {
                        case 'min':
                            if (strlen($value) < $rule_value) {
                                $this->addError("{$item} must be a minimum of {$rule_value} characters!!");
                            }
                            break;
                        case 'max':
                            if (strlen($value) > $rule_value) {
                                $this->addError("{$item} must be a maximum of {$rule_value} characters!!");
                            }
                            break;
                        case 'matches':
                            if ($value != $source[$rule_value]) {
                                $this->addError("{$rule_value} must match with {$item}!!!");
                            }
                            break;
                        case 'unique':
                            $check = $this->_db->get($rule_value, $where = [$item, '=', $value]);
                            if ($check->count()) {
                                $this->addError("{$item} already exists.");
                            }
                            break;
                        case 'email':
                            if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
                                $this->addError('You need to insert a valid email!');
                            }
                            break;
                        case 'letters':
                            if (is_numeric($value)) {
                                $this->addError("{$item} must contain only letters!");
                            }
                    }
                }
            }
        }

        if (empty($this->_errors)) {
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


    /**
     * @return array
     */
    public function errors()
    {
        return $this->_errors;
    }


    /**
     * @return bool
     */
    public function passed()
    {
        return $this->_passed;
    }
}